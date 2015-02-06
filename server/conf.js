module.exports = {
    game: 'backgammon',
    port: 8080,
    pingTimeout:1000000,
    pingInterval:50000,
    logLevel:3,
    turnTime: 600,
    maxTimeouts:1,
    db:{
        connectionLimit : 4,
        host            : 'localhost',
        user            : 'root',
        password        : 'root',
        database        : 'logicgame'
    },
    closeOldConnection: true,
    mode: 'test'
};