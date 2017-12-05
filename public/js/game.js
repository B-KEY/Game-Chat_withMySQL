

var rollTheDice = function() {
    var i,
        faceValue,
        output = '',
        diceCount = document.querySelector('input[type=number]').value || 1;
    for (i = 0; i < diceCount; i++) {
        faceValue = Math.floor(Math.random() * 6);
        output += "&#x268" + faceValue + "; ";
    }
    document.getElementById('dice').innerHTML = output;
}

var game = {
    xhtmlns:"http://www.w3.org/1999/xhtml",
    svgns:"http://www.w3.org/2000/svg",
    BOARDX:10,				//starting pos of board
    BOARDY:10,				//look above
    boardArr:new Array(),		//2d array [row][col]
    BOARDWIDTH:0,				//how many squares across
    BOARDHEIGHT:0,			//how many squares down
    CELLSIZE:0,
    PLAYERONE: '',
    PLAYERTWO: '',

    drawGame: function (gameData){
        //create a parent to stick board in...
        var gEle = document.createElementNS(game.svgns, 'g');
        gEle.setAttributeNS(null, 'transform', 'translate(' + game.BOARDX + ',' + game.BOARDY + ')');
        gEle.setAttributeNS(null, 'id', 'gId_' + gameData.game.id);
        //stick g on board
        document.getElementsByTagName('svg')[0].appendChild(gEle);
        //create the board...
        game.BOARDHEIGHT = gameData.game.height;
        game.BOARDWIDTH =  gameData.game.width;
        game.CELLSIZE  = gameData.game.size;
        var num = 100;

        // This draws the board
        for (i = 0; i <game.BOARDWIDTH ; i++) {
            game.boardArr[i] = new Array();
            for (j = 0; j <game.BOARDHEIGHT; j++) {
                game.boardArr[i][j] = new Cell(document.getElementById('gId_' + gameData.game.id), 'cell_' + j + i, game.CELLSIZE, i, j,num);
                (i%2===0)?num--:num++;
            }
            (i%2===0)?num++:num--;
            num-=10;
        }

        // this creates Pieces
        PLAYERONE = new Piece(
            gameData.game.id,
            gameData.player0.id,
            Number(gameData.player0.x),
            Number(gameData.player0.y),
            'SnakeLadder',
            'red'
        );

        PLAYERTWO = new Piece(
            gameData.game.id,
            gameData.player1.id,
            Number(gameData.player1.x),
            Number(gameData.player1.y),
            'SnakeLadder',
            'green'
        );
        game.setLables(gameData.player0.name, gameData.player0.score, gameData.player1.name, gameData.player1.score);

        $('#gameID').attr('value',gameData.game.id);
        document.querySelector('input[type=button]').addEventListener('click', function(){game.roll();});
        game.drawSnakesAndLadder();
    },

        // init: function (res) {
        // //create a parent to stick board in...
        // var gEle = document.createElementNS(game.svgns, 'g');
        // gEle.setAttributeNS(null, 'transform', 'translate(' + game.BOARDX + ',' + game.BOARDY + ')');
        // gEle.setAttributeNS(null, 'id', 'gId_' + res.data.gameData.gameID);
        // //stick g on board
        // document.getElementsByTagName('svg')[0].appendChild(gEle);
        // //create the board...
        // game.BOARDHEIGHT = res.data.gameData.height;
        // game.BOARDWIDTH =  res.data.gameData.width;
        // game.CELLSIZE  = res.data.gameData.size;
        // console.log(game.BOARDWIDTH);
        //     console.log(game.BOARDHEIGHT);
        //  var num = 100;
        //
        // for (i = 0; i <game.BOARDWIDTH ; i++) {
        //     game.boardArr[i] = new Array();
        //     for (j = 0; j <game.BOARDHEIGHT; j++) {
        //         game.boardArr[i][j] = new Cell(document.getElementById('gId_' + res.data.gameData.gameID), 'cell_' + j + i, game.CELLSIZE, i, j,num);
        //         (i%2===0)?num--:num++;
        //     }
        //     (i%2===0)?num++:num--;
        //     num-=10;
        // }
        //
        // PLAYERONE = new Piece(
        //     res.data.gameData.gameID,res.data.gameData.player1,
        //     Number(res.data.gameData.positionX),
        //     Number(res.data.gameData.positionY),
        //     'SnakeLadder'
        // );
        //
        // PLAYERTWO = new Piece(
        //     res.data.gameData.gameID,
        //     res.data.gameData.player2,
        //     Number(res.data.gameData.positionX),
        //     Number(res.data.gameData.positionY),
        //     'SnakeLadder'
        // );
        //
        // $('#gameID').attr('value',res.data.gameData.gameID);
        // document.querySelector('input[type=button]').addEventListener('click', function(){game.roll();});
        // game.drawSnakesAndLadder();
        // },

    roll: function(){
        console.log("Calling from roll");
        var gameID= $('#gameID').attr('value');
        var opponent_id = $("#receiver_id").attr('value');
        manager.myXHR('POST', './games', {gameId:gameID,id:opponent_id}).done(function(res){
            var output ='';
            var faceValue = res.data.gameData.diceValue;
            output += "&#x268" + faceValue + "; ";
            document.getElementById('dice').innerHTML = output;
             game.playForUser(gameID);
        });
    },
    playForUser : function(gameID){
        url = './games/'+ gameID;
        manager.myXHR('GET', url).done(function(res){
            util.setTransform('piece_0|0',game.boardArr[Number(res.X)][Number(res.Y)].getCenterX(),game.boardArr[Number(res.X)][Number(res.Y)].getCenterY());
        });
    },


    drawSnakesAndLadder: function() {

        var gEle = document.createElementNS(game.svgns, 'g');
        gEle.setAttributeNS(null, 'transform', 'translate(' + 40 + ',' + 45 + ')');
        //stick g on board

        var line1 = document.createElementNS(game.svgns,'line');
        line1.setAttributeNS(null, 'x1', '20');
        line1.setAttributeNS(null, 'x2', '58');
        line1.setAttributeNS(null, 'y1', '75');
        line1.setAttributeNS(null,'y2', '260');
        line1.setAttributeNS(null, 'stroke',"#4C516D");
        line1.setAttributeNS(null, 'stroke-width','4px');
        line1.setAttributeNS(null, 'opacity', '0.5');

        var line2 = document.createElementNS(game.svgns,'line');
        line2.setAttributeNS(null, 'x1', '40');
        line2.setAttributeNS(null, 'x2', '75');
        line2.setAttributeNS(null, 'y1', '75');
        line2.setAttributeNS(null,'y2', '258');
        line2.setAttributeNS(null, 'stroke',"#4C516D");
        line2.setAttributeNS(null, 'stroke-width','4px');
        line2.setAttributeNS(null, 'opacity', '0.5');
        gEle.appendChild(line1);
        gEle.appendChild(line2);
        document.getElementsByTagName('svg')[0].appendChild(gEle);
    },
    setLables: function(player0Name, player0Score, player1Name, player1Score) {
        variable._player0Name$ = $('#player0Name'),
        variable._player0Score$ = $('#player0Score'),
        variable._player1Name$ = $('#player1Name'),
        variable._player1Score$ = $('#player1Score'),

        variable._player0Name$.text(player0Name.substr(0,3));
        variable._player0Score$.text(' : ' + player0Score);
        variable._player1Name$.text(player1Name.substr(0,3));
        variable._player1Score$.text(' : '+ player1Score);

    }

}
