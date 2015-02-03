var EventEmitter = require('events').EventEmitter;
var Room = require('./room.js');
var util = require('util');

module.exports = GameManager;

function GameManager(server){
    EventEmitter.call(this);

    var self = this;
    this.server = server;
    this.engine = server.engine;
    this.turnTime = server.conf.turnTime * 1000;
    this.maxTimeouts = server.conf.maxTimeouts;

    // bindEvents
    server.on('user_leave', function(user){
        if (user.currentRoom) {
            self.onUserLeave(user.currentRoom, user);
        }
    });
    server.on('user_relogin', function(user){

    });
    server.inviteManager.on('invite_accepted', function(invite){
        util.log('log;', 'invite_accepted',invite.owner.userId);
        if (!invite.owner || !invite.players || invite.players.length<2){
            util.log('err;', 'game_manager;', 'wrong invite!', invite);
        }
        self.createGame(invite.owner, invite.players, invite.data);
    });
}

util.inherits(GameManager, EventEmitter);


GameManager.prototype.onMessage = function(message, type){
    var room;
    if (type != 'watch') room = this.getUserRoom(message.sender, true);
    else room = this.server.storage.getRoom(message.data.roomId);

    if (!room){
        util.log('err; ', 'GameManager', 'no room to continue', message);
        return;
    }

    switch (type){
        case 'ready': // player ready to play
            this.setUserReady(room, message.sender, message.data);
            break;
        case 'turn': // all players turns
            this.onUserTurn(room, message.sender, message.data);
            break;
        case 'throw': // player capitulation
            break;
        case 'event': // all events, draw, turn back, others
            this.onUserEvent(room, message.sender, message.data);
            break;
        case 'watch': // user begin spectate
            this.onUserWatch(room, message.sender);
            break;
        case 'leave': // user leave room
            this.onUserLeave(room, message.sender);
            break;
    }
};


GameManager.prototype.createGame = function(owner, players, data){
    delete data.from;
    delete data.target;
    if (!data.mode) throw new Error('game mode undefined!');
    var room = this.createRoom(owner, players, data);
    var info = room.getInfo();

    this.server.router.send({
        module: 'server',
        type: 'new_game',
        target: this.server.game,
        data: info
    });

    this.server.storage.pushRoom(room);
};


GameManager.prototype.createRoom = function(owner, players, data){
    var id = this.generateRoomId(owner, data.mode);
    var room = new Room(id, owner, players, data);

    for (var i = 0; i < players.length; i++) {
        players[i].enterRoom(room);
    }

    return room;
};


GameManager.prototype.setUserReady = function(room, user, ready){
    util.log('log;', 'GameManager.setUserReady room:', room.id, 'user:',  user.userId, ready);
    if (typeof ready != "boolean") ready = true;
    if (room.game.state != "waiting") {
        util.log('err;', 'GameManager.setUserReady', 'game already started!', room, user.userId, ready);
        return;
    }
    room.data[user.userId].ready = ready;

    this.server.router.send({
        module: 'game_manager',
        type: 'ready',
        target: room,
        data: {
            user:user.userId,
            ready:ready
        }
    });

    if (room.checkPlayersReady()){ // all users ready
        // initializing game before start, and get data to send players
        var game = room.game;
        game.initData = this.initGame(room);
        game.state = "playing";
        game.history = [];
        game.shistory = "";
        game.playerTurns = [];

        this.server.router.send({
            module: 'game_manager',
            type: 'round_start',
            target: room,
            data: game.initData
        });
    }
};


GameManager.prototype.onUserTurn = function(room, user, turn){
    util.log('log;', 'GameManager.onUserTurn room:', room.id, 'user:',  user.userId);
    var game = room.game;
    if (game.state != 'playing'){
        this.sendError(user, 'game_not_started!');
        return;
    }
    // check user is current, check turn is valid, ask engine what to do, send to all in room
    if (game.current != user) { // wrong user
        this.sendError(user, 'not_your_turn');
        return;
    }

    // do turn in engine
    var userTurn;
    if (typeof this.engine.doTurn == "function") userTurn = this.engine.doTurn(room, user, turn);
    else userTurn = turn;
    if (!userTurn || typeof userTurn != "object") { // wrong turn
        util.log('err;', 'GameManager.onUserTurn, wrong turn: ', userTurn);
        this.server.router.send({
            module: 'game_manager',
            type: 'error',
            target: user,
            data: 'wrong_turn'
        });
        return;
    }

    // switch player
    var nextPlayer = this.switchPlayer(room, user, turn);
    game.playerTurns.push(userTurn);
    if (nextPlayer != game.current ) {
        if (room.timeout) clearTimeout(room.timeout);
        room.data[game.current.userId].timeouts = 0;
        turn.nextPlayer = nextPlayer.userId;
        this.savePlayerTurn(room);
    }

    // send turn
    this.server.router.send({
        module: 'game_manager',
        type: 'turn',
        target:  room,
        data: {user:user.userId, turn:userTurn}
    });

    // check endGame
    if (!this.checkGameEnd(room, user, turn) && nextPlayer != game.current) {// switch
        game.current = nextPlayer;
        room.timeout = setTimeout(function (){
            this.onTimeout(room, room.game.current);
        }.bind(this), this.turnTime)
    }
};


GameManager.prototype.switchPlayer = function(room, user, turn){
    var nextPlayer;
    if (turn == 'timeout' && typeof this.engine.onTimeout == "function") nextPlayer = this.engine.onTimeout(room, user);
    else
    if (turn != 'timeout' && typeof this.engine.switchPlayer == "function") nextPlayer = this.engine.switchPlayer(room, user, turn);

    if (!nextPlayer) {
        for (var i = 0; i < room.players.length; i++)
            if (room.players[i] != user) {
                nextPlayer = room.players[i];
                break;
            }
    }
    return nextPlayer;
};


GameManager.prototype.checkGameEnd = function(room, user, turn){
    var result = false; // false - game not end, null - draw, user - winner
    if (typeof this.engine.getGameResult == "function") result = this.engine.getGameResult(room, user, turn);
    if (result) { // game end
        this.savePlayerTurn(room);
        this.onRoundEnd(room, result);
        return true;
    }
    return false;
};


GameManager.prototype.savePlayerTurn = function(room){
    var game = room.game;
    if (game.playerTurns.length == 1) game.playerTurns = game.playerTurns[0];
    game.history.push(game.playerTurns);
    try{
        game.shistory += JSON.stringify(game.playerTurns)+'@';
    } catch (e){
        util.log('error;', 'GameManager.savePlayerTurn', 'json stringify error!', game.playerTurns,  e)
    }
    game.playerTurns = [];
};


GameManager.prototype.onUserEvent = function(room, user, event){
    // check event type, throw, ask draw, ask moveback
    if (room.game.state != "playing") {
        util.log('error', 'event in not started game room:', room.id, user.userId);
        this.sendError(user, 'event in not started game room: ' + room.id);
        return;
    }
    if (!event.type) {
        util.log('error', 'wrong event type ', room.id, user.userId);
        this.sendError(user, 'wrong event type room: ' + room.id);
        return;
    }
    switch (event.type){
        case 'throw': this.onThrow(room, user, event); break;
        case 'draw': this.onDraw(room, user, event); break;
    }

};


GameManager.prototype.onThrow = function(room, user, event){
    event = event || 'throw';
    for (var i = 0; i < room.players.length; i++)
        if (room.players[i] != user) {
            this.onRoundEnd(room, {
                winner: room.players[i],
                action: event
            });
            return;
        }
};


GameManager.prototype.onDraw = function(room, user, event){
    // TODO: check user can ask draw
    switch (event.action){
        case 'ask':
            if (!room.game.askDraw) {
                room.game.askDraw = user;
                this.server.router.send({
                    module: 'game_manager',
                    type: 'event',
                    target: room,
                    data: {
                        user: user.userId,
                        type: 'draw',
                        action: 'ask'
                    }
                });
            } else {
                if (room.game.askDraw == user) { // already asked
                    util.log('log;', 'GameManager.onDraw', 'user already ask draw', user.userId);
                } else { // draw
                    util.log('log;', 'GameManager.onDraw', 'auto draw', user.userId, room.game.askDraw);
                    this.onRoundEnd(room, {action: 'draw'});
                }
            }
            break;
        case 'cancel':
            if (room.game.askDraw && room.game.askDraw != user) {
                this.server.router.send({
                    module: 'game_manager',
                    type: 'event',
                    target: room.game.askDraw,
                    data: {
                        user: user.userId,
                        type: 'draw',
                        action: 'cancel'
                    }
                });
                room.game.askDraw = null;
            } else {
                util.log('warn;', 'GameManager.onDraw', 'wrong cancel draw', user.userId, room.game.askDraw);
            }
            break;
        case 'accept':
            if (room.game.askDraw && room.game.askDraw != user) {
                util.log('log;', 'GameManager.onDraw', 'draw', user.userId, room.game.askDraw);
                this.onRoundEnd(room, {action: 'draw'});
                room.game.askDraw = null;
            } else {
                util.log('warn;', 'GameManager.onDraw', 'wrong accept draw', user.userId, room.game.askDraw);
            }
            break;
    }

};


GameManager.prototype.onTimeout = function(room, user){
    room.data[user.userId].timeouts++;
    clearTimeout(room.timeout);
    util.log('log;', 'GameManager.onTimeout;', room.id, user.userId, room.data[user.userId].timeouts);
    //TODO: check switch player or not, engine function user timeout
 // player auto skip turn, switch players
    var nextPlayer = this.switchPlayer(room, user, 'timeout');
    room.game.playerTurns.push({user: user.userId, action: 'timeout', nextPlayer: nextPlayer.userId});
    this.savePlayerTurn(room);

    this.server.router.send({
        module: 'game_manager',
        type: 'event',
        target: room.id,
        data: {
            user: user.userId,
            type: 'timeout',
            nextPlayer: nextPlayer.userId
        }
    });
    if (room.data[user.userId].timeouts == this.maxTimeouts) { // lose
        this.onThrow(room, user, 'timeout')
    } else {
        room.game.current = nextPlayer;
        room.timeout = setTimeout(function () {
            this.onTimeout(room, room.game.current);
        }.bind(this), this.turnTime)
    }
};


GameManager.prototype.onUserWatch = function(room, user){

};


GameManager.prototype.onUserLeave = function(room, user){
    //TODO: round end; save result in other function;
    util.log('log;', 'GameManager.onUserLeave', user.userId);
    var i;

    // other user win if game start
    if (room.game.state == "playing")
    for (i = 0; i < room.players.length; i++)
        if (room.players[i] != user) {
            this.onRoundEnd(room, {
                winner: room.players[i],
                action: 'user_leave'
            });
            break;
        }

    this.server.router.send({
        module: 'game_manager',
        type: 'user_leave',
        target: room,
        data: user.userId
    });

    util.log('log;', 'closeRoom', room.id);
    for (i = 0; i < room.players.length; i++) room.players[i].leaveRoom();
    this.server.storage.popRoom(room);

    this.server.router.send({
        module: 'server',
        type: 'end_game',
        target: this.server.game,
        data: {players:room.getPlayersId(), room:room.id}
    });
};


GameManager.prototype.onRoundEnd = function(room, result){
    if (room.timeout) clearTimeout(room.timeout);
    // TODO: save results
    if (result.save) {
        // TODO: get new score, ratings
    }

    if (result.winner && result.winner.userId)
        result.winner = result.winner.userId;
    util.log('log;', 'GameManager.onRoundEnd, room:', room.id, 'result and action:', result.winner, result.action);
    result.history = room.game.history;
    result.shistory = room.game.shistory;
    room.game.state = "waiting";
    for (var i = 0; i < room.players.length; i++){
        room.data[room.players[i].userId].ready = false;
        room.data[room.players[i].userId].timeouts = 0;
    }

    this.server.router.send({
        module: 'game_manager',
        type: 'round_end',
        target: room,
        data: result
    });
};


GameManager.prototype.initGame = function(room) {
    var userData = {
        inviteData: room.inviteData
    };
    // TODO: async initGame and error handler
    if (typeof this.engine.initGame == "function") userData = this.engine.initGame(room) || userData;
    if (typeof this.engine.setFirst == "function") room.game.current =  this.engine.setFirst(room);
    else  room.game.current = room.owner;
    if (!room.game.current || !room.game.current.userId){
        throw new Error('first player is undefined! '+room.id)
    }
    room.game.first = room.game.current;
    room.game.askDraw = null;
    userData.first = room.game.current.userId;
    userData.id = room.id;
    userData.owner = room.owner.userId;
    userData.players = [];
    for (var i = 0; i < room.players.length; i++) userData.players.push(room.players[i].userId);
    return userData;
};


GameManager.prototype.getUserRoom = function(user, notSpectator){
    if (typeof notSpectator != "boolean") notSpectator = true;
    if (!user.currentRoom) return null;
    if (!notSpectator) return user.currentRoom;
    if (user.currentRoom.players.indexOf(user) != -1) return user.currentRoom;
    else {
        util.log('warn; ', 'user is not a player in room!', user.currentRoom, user);
        return null;
    }
};


GameManager.prototype.sendError = function(user, error){
    this.server.router.send({
        module: 'game_manager',
        type: 'error',
        target:  user,
        data: error
    });
};


GameManager.prototype.generateRoomId = function(owner, type){
    //game format name: "game_type_userId_socketId_hh.mm.ss"
    var now = new Date();
    return this.server.game + "_" + type + "_" + owner.userId + "_" + owner.socket.id
        + "_" + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
};