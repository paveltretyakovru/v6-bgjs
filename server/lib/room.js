module.exports = Room;

function Room(id, owner, players, data){
    this.id = id;
    this.owner = owner;
    this.players = players;
    this.inviteData = data;
    this.mode = data.mode;
    this.timeout = null;

    //game data
    this.game = {
        state:"waiting"
    };

    // players data
    this.data = {};
    for (var i = 0; i < players.length; i++) {
        this.data[players[i].userId] = {
            ready: false,
            timeouts:0
        };
    }
}

Room.prototype.name = '__Room__';


Room.prototype.getPlayersId = function(){
    var ids = [];
    for (var i = 0; i < this.players.length; i++) ids.push(this.players[i].userId);
    return ids;
};


Room.prototype.getInfo = function(){
    return {
        room: this.id,
        owner: this.owner.userId,
        data: this.inviteData,
        players: this.getPlayersId(),
        mode: this.mode
    };
};


Room.prototype.checkPlayersReady = function(){
    for (var i = 0; i < this.players.length; i++){
        if (this.data[this.players[i].userId].ready == false) return false;
    }
    return true;
};

/**
 * game states:
 * waiting - waiting users ready
 * playing - users play
 * end - game round end
 */
