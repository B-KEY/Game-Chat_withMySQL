

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
    whoseTurn: '',
    thisPlayer: {
      id: '',
      score: '',
      rolled: '',
      piece:'',
      name:''
    },
    opponent: {
        id: '',
        score: '',
        rolled: '',
        piece:'',
        name:''
    },
    id:'',
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
        game.whoseTurn = gameData.game.whoseTurn;
        var num = 1;

        // This draws the board
        for (i = 9,x = 0; i >=0  ; i--, x++) {
            game.boardArr[x] = new Array();
            for (var j = 0; j <game.BOARDHEIGHT; j++) {
                game.boardArr[x][((num-1)%10)] = new Cell(document.getElementById('gId_' + gameData.game.id), 'cell_' + x + ((num-1)%10), game.CELLSIZE, i, j,num);
                (x%2===0)?num++:num--;
            }
            (x%2===0)?num--:num++;
            num+=10;
        }

        console.log('receiver_id :' + variable._receiverId$.attr('value'));
        console.log('player0 id :' + gameData.player0.id);
        console.log('player1 id' + gameData.player1.id);
        game.id = gameData.game.id;
        if(variable._receiverId$.attr('value') === gameData.player0.id){
            variable._thisPlayer = 'player1';
            variable._opponent = 'player0';

        }else{
            variable._thisPlayer = 'player0';
            variable._opponent = 'player1';
        }

        //assign opponent
        game.opponent.id = gameData[variable._opponent].id;
        game.opponent.score = gameData[variable._opponent].score;
        game.opponent.rolled = gameData[variable._opponent].rolled;
        game.opponent.name = gameData[variable._opponent].name;
        game.opponent.piece = new Piece(
            'game' + gameData.game.id,
            game.opponent.id,
            gameData[variable._opponent].x,
            gameData[variable._opponent].y,
            'SnakeLadder',
            'green'  // this needs to come from database
        );
        // assign this player
        game.thisPlayer.id  = gameData[variable._thisPlayer].id;
        game.thisPlayer.score = gameData[variable._thisPlayer].score;
        game.thisPlayer.rolled = gameData[variable._thisPlayer].rolled;
        game.thisPlayer.name = gameData[variable._thisPlayer].name;
        game.thisPlayer.piece = new Piece(
            'game_' + gameData.game.id,
            game.thisPlayer.id,
            gameData[variable._thisPlayer].x,
            gameData[variable._thisPlayer].y,
            'SnakeLadder',
            'red'   // needs to come from database;
        );

        game.setLables(
            gameData[variable._thisPlayer].name,
            gameData[variable._thisPlayer].score,
            gameData[variable._opponent].name,
            gameData[variable._opponent].score);

        variable._gameId$.attr('value',gameData.game.id);
        document.querySelector('input[type=button]').addEventListener('click', function(){game.roll();});
        game.drawSnakesAndLadder();
        //put the drop code on the document...
        document.getElementsByTagName('svg')[0].addEventListener('mouseup',drag.releaseMove,false);
        //put the go() method on the svg doc.
        document.getElementsByTagName('svg')[0].addEventListener('mousemove',drag.go,false);


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
        //var gameID = variable._gameId.attr('value');
        //var opponent_id = $("#receiver_id").attr('value');
        manager.myXHR (
            'POST',
            './games',
            { gameId:variable._gameId$.attr('value'), id:variable._receiverId$.attr('value')}
            ).done(function(res){
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
        variable._player0Score$.text(' : ' + (Number(player0Score)));
        variable._player1Name$.text(player1Name.substr(0,3));
        variable._player1Score$.text(' : '+ (Number(player1Score)));

    }

}


var drag={
    //the problem of dragging....
    myX:'',						//hold my last pos.
    myY:'',					//hold my last pos.
    mover:'',					//hold the id of the thing I'm moving
    ////setMove/////
    //	set the id of the thing I'm moving...
    ////////////////
    setMove:function(which){
        drag.mover = which;
        console.log("piece Id: "+ which);



        drag.myX=game.thisPlayer.piece.x;
        drag.myY=game.thisPlayer.piece.y;
        console.log("piece location: ", drag.myX, drag.myY);
        game.thisPlayer.piece.putOnTop(which);
        //get the object then re-append it to the document so it is on top!
        /*util.getPiece(which).putOnTop(which);*/
    },
    releaseMove:function(evt){
        if(drag.mover != ''){
            //is it YOUR turn?
            //if(game.whoseTurn == game.thisPlayer.id){
                var hit = drag.checkHit(evt.layerX,evt.layerY,drag.mover);
           // }else{
             //   var hit=false;
                //util.nytwarning();
           // }

            if(hit==true){
                //I'm on the square...
                //send the move to the server!!!
                //ajax.changeServerTurnAjax("changeTurn",38);
                // url = './game/change';
                // manager.myXHR('POST', url,{gameId: variable._gameId$.attr('value'), id: variable._receiverId$.attr('value')}).done(function(res){
                //     console.log('This piece id', game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].getCenterX());
                //     util.setTransform(
                //         game.thisPlayer.piece.id,
                //         game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].getCenterX(),
                //         game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].getCenterY()
                //     );
                //     // util.setTransform('piece_0|0',game.boardArr[Number(res.X)][Number(res.Y)].getCenterX(),game.boardArr[Number(res.X)][Number(res.Y)].getCenterY());
                // });

            }else{
                url = './game/change';
                manager.myXHR('POST', url,{gameId: variable._gameId$.attr('value'), id: variable._receiverId$.attr('value')}).done(function(res){
                    console.log('This piece id', game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].getCenterX());
                    util.setTransform(
                        game.thisPlayer.piece.id,
                        game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].getCenterX(),
                        game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].getCenterY()
                        );
                   // util.setTransform('piece_0|0',game.boardArr[Number(res.X)][Number(res.Y)].getCenterX(),game.boardArr[Number(res.X)][Number(res.Y)].getCenterY());
                    game.thisPlayer.piece.changeCell(
                        game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].id,
                        Number(res.data.gameData.player.x),
                        Number(res.data.gameData.player.y)
                    );
                });
                //move back
               // util.setTransform(drag.mover,drag.myX,drag.myY);

            }
            console.log(drag.mover);
            drag.mover = '';
        }
    },
    go:function(evt){
        if(drag.mover != ''){
            util.setTransform(drag.mover,evt.layerX,evt.layerY);
        }
    },

    checkHit:function(x,y,id){
        x=x-game.BOARDX;
        y=y-game.BOARDY;
        console.log('x, y value: ', x, y);
        console.log(id);
        //go through ALL of the board
        for(i=0;i<game.BOARDWIDTH;i++){
            for(j=0;j<game.BOARDHEIGHT;j++){
                var drop = game.boardArr[i][j].myBBox;

                if(x>drop.x && x<(drop.x+drop.width) && y>drop.y && y<(drop.y+drop.height) && game.boardArr[i][j].droppable && game.boardArr[i][j].occupied == ''){
                    //NEED - check is it a legal move???
                    //if it is - then
                    //put me to the center....
                    util.setTransform(id,game.boardArr[i][j].getCenterX(),game.boardArr[i][j].getCenterY());
                    //fill the new cell
                    //alert(parseInt(which.substring((which.search(/\|/)+1),which.length)));
                    game.thisPlayer.piece.changeCell(game.boardArr[i][j].id,i,j);
                    //////////////////
                    //change the board in the database for the other person to know
                    //ajax.changeBoardAjax(id,i,j,'changeBoard',38);
                    //change who's turn it is
                   // util.changeTurn();
                    return true;
                }
            }
        }
        return false;
    }

}
