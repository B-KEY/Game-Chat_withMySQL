//
// var rollTheDice = function() {
//     var i,
//         faceValue,
//         output = '',
//         diceCount = document.querySelector('input[type=number]').value || 1;
//     for (let i = 0; i < diceCount; i++) {
//         faceValue = Math.floor(Math.random() * 6);
//         output += "&#x268" + faceValue + "; ";
//     }
//     document.getElementById('dice').innerHTML = output;
// }


var game = {
    xhtmlns:"http://www.w3.org/1999/xhtml",
    svgns:"http://www.w3.org/2000/svg",
    BOARDX: 10,				//starting pos of board
    BOARDY: 10,				//look above
    boardArr: new Array(),		//2d array [row][col]

    // all this come from the database
    BOARDWIDTH: 0,				//how many squares across
    BOARDHEIGHT: 0,			//how many squares down
    CELLSIZE: 0,
    turn: '',
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
    update: '',


    drawGame: function (gameData){
        console.log(gameData);
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
        game.turn = gameData.game.whoseturn;

        game.id = gameData.game.id;
        var num = 1;

        // This draws the board
        for (i = 9,x = 0; i >=0  ; i--, x++) {
            game.boardArr[x] = new Array();
            for (var j = 0; j <game.BOARDHEIGHT; j++) {
                game.boardArr[x][((num-1)%10)] = new Cell(document.getElementById('gId_' + gameData.game.id),
                    'cell_' + x + ((num-1)%10),
                    game.CELLSIZE,
                    i,
                    j,
                    num);
                (x%2===0)?num++:num--;
            }
            (x%2===0)?num--:num++;
            num+=10;
        }
        if(variable._receiverId$.attr('value') == gameData.player0.id){
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
            gameData[variable._opponent].score
        );

        variable._gameId$.attr('value',gameData.game.id);
        document.querySelector('input[type=button]').addEventListener('click', function(){game.roll();});
        game.drawSnakesAndLadder();
        //put the drop code on the document...
        document.getElementsByTagName('svg')[0].addEventListener('mouseup',drag.releaseMove,false);
        //put the go() method on the svg doc.
        document.getElementsByTagName('svg')[0].addEventListener('mousemove',drag.go,false);
        variable._error$ = $('#error');
    },

    roll: function() {
        if(game.thisPlayer.rolled == 'yes' && game.turn != game.thisPlayer.id) {
            util.allWarning('Not your turn');
            return;
        }
        if(game.thisPlayer.rolled == 'yes' && game.turn == game.thisPlayer.id) {
            util.allWarning('You already rolled. Please Drag!');
            return;
        }
        if( game.thisPlayer.rolled == 'no' && game.turn == game.thisPlayer.id) {
            manager.myXHR ('POST', './games', { gameId: game.id, id:game.opponent.id}).done(function(res) {
                var output ='';
                var faceValue = '' + Number(res.data.gameData.game.diceValue)-1;
                output += "&#x268" + faceValue + "; ";
                document.getElementById('dice').innerHTML = output;
                if(game.updateGameData(res.data.gameData))return;
                setTimeout(game.updateBoard,5000);
            });
        }
    },

    drawSnakesAndLadder: function() {

        // 95 - 75
        var arrEllipse = [10, 75, 15, 7];
        var arrLine = [20, 20, 80, 180];
        var arrTranslate = [280, -35];
        game.drawSnake(arrTranslate, arrEllipse, arrLine);
        // 98 - 78
         arrEllipse = [10, 75, 15, 7];
         arrLine = [20, 20, 80, 180];
         arrTranslate = [120, -35];
        game.drawSnake(arrTranslate, arrEllipse, arrLine);
        //87 - 24
         arrEllipse = [10, 75, 15, 7];
         arrLine = [20, -140, 80, 380]
         arrTranslate = [330, 20]
        game.drawSnake(arrTranslate, arrEllipse, arrLine);

        // 54 -34
         arrEllipse = [10, 75, 15, 7];
         arrLine = [20, 20, 80, 180];
         arrTranslate = [330, 160];
        game.drawSnake(arrTranslate, arrEllipse, arrLine);

        // 64 - 60
         arrEllipse = [10, 75, 15, 7];
         arrLine = [20,-140, 80, 140]
         arrTranslate = [180, 100]
        game.drawSnake(arrTranslate, arrEllipse, arrLine);

        // 17 -7
         arrEllipse = [10, 75, 15, 7];
         arrLine = [20,160, 80, 140]
         arrTranslate = [180, 350]
        game.drawSnake(arrTranslate, arrEllipse, arrLine);

    //ladders
         // 4-14
        var arrLine1 = [20, -130, 75, 130];
        var arrLine2 = [20,-130, 95, 150]
        var arrTranslate = [300, 350]
        game.drawLadder(arrTranslate, arrLine1, arrLine2);

        //20-38
         arrLine1 = [10, -120, 75, 180];
         arrLine2 = [10, -120, 95, 200]
         arrTranslate = [140, 250]
        game.drawLadder(arrTranslate, arrLine1, arrLine2);

        //28-84
         arrLine1 = [10, 200, 90, 370];
         arrLine2 = [20, 220, 70, 370]
        arrTranslate = [180, 10]
        game.drawLadder(arrTranslate, arrLine1, arrLine2);

         // 81- 63

        arrLine1 = [10, 100, 90, 180];
        arrLine2 = [20, 120, 70, 180]
        arrTranslate = [20, 10]
        game.drawLadder(arrTranslate, arrLine1, arrLine2);

        //59-40
        arrLine1 = [10, -60, 75, 160];
        arrLine2 = [10, -60, 95, 180]
        arrTranslate = [70, 160]
        game.drawLadder(arrTranslate, arrLine1, arrLine2);


    },
    drawSnake : function(arrTranslate,arrEllipse, arrLine) {
        var gEle = document.createElementNS(game.svgns, 'g');
        gEle.setAttributeNS(null, 'transform', 'translate(' + arrTranslate[0] + ',' + arrTranslate[1]  + ')');
        //stick g on board

        var ellipse = document.createElementNS(game.svgns, 'ellipse');
        ellipse.setAttributeNS(null,'cx', arrEllipse[0]);
        ellipse.setAttributeNS(null,'cy',arrEllipse[1]);
        ellipse.setAttributeNS(null,'rx',arrEllipse[2]);
        ellipse.setAttributeNS(null,'ry',arrEllipse[3]);
        ellipse.setAttributeNS(null,'stroke',"#4C516D");
        ellipse.setAttributeNS(null,'fill',"red");
        ellipse.setAttributeNS(null,'stroke-width','5px');
        ellipse.setAttributeNS(null,'opacity', '1');

        var line1 = document.createElementNS(game.svgns,'line');
        line1.setAttributeNS(null, 'x1', arrLine[0]);
        line1.setAttributeNS(null, 'x2', arrLine[1]);
        line1.setAttributeNS(null, 'y1', arrLine[2]);
        line1.setAttributeNS(null,'y2', arrLine[3]);
        line1.setAttributeNS(null, 'stroke',"#4C516D");
        line1.setAttributeNS(null, 'stroke-width','8px');
        line1.setAttributeNS(null, 'opacity', '0.6');

        gEle.appendChild(ellipse);
        gEle.appendChild(line1);
        document.getElementsByTagName('svg')[0].appendChild(gEle);
    },
    drawLadder: function(arrTranslate, arrLine1, arrLine2) {


         var gEle = document.createElementNS(game.svgns, 'g');
         gEle.setAttributeNS(null, 'transform', 'translate(' + arrTranslate[0] + ',' + arrTranslate[1] + ')');
        var line1 = document.createElementNS(game.svgns,'line');
        line1.setAttributeNS(null, 'x1', arrLine1[0]);
        line1.setAttributeNS(null, 'x2', arrLine1[1]);
        line1.setAttributeNS(null, 'y1', arrLine1[2]);
        line1.setAttributeNS(null,'y2', arrLine1[3]);
        line1.setAttributeNS(null, 'stroke',"green");
        line1.setAttributeNS(null, 'stroke-width','4px');
        line1.setAttributeNS(null, 'opacity', '0.5');

        var line2 = document.createElementNS(game.svgns,'line');
        line2.setAttributeNS(null, 'x1', arrLine2[0]);
        line2.setAttributeNS(null, 'x2', arrLine2[1]);
        line2.setAttributeNS(null, 'y1', arrLine2[2]);
        line2.setAttributeNS(null,'y2', arrLine2[3]);
        line2.setAttributeNS(null, 'stroke',"green");
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

    },
    updateGameData: function(gameData) {
        console.log(gameData);
        if(gameData.result){
            var text = '';
            if( gameData.result.winner == game.thisPlayer.id){
                text = game.thisPlayer.name;
            }else {
                 text = gameData.opponent.name;
            }
            alert('you won the game');
            $('.modal-content p').text(text + 'won this game');
            modal.style.display = "block";
            variable._gameSection$.empty();
            return true;
        }
        game.turn = gameData.game.whoseTurn;
        game.thisPlayer.rolled = gameData.player.rolled;
        game.thisPlayer.score = gameData.player.score;
        return false;

    },
    updateBoard: function() {
        if(game.turn != game.thisPlayer.id){
            return;
        }
        url = './game/change';
        manager.myXHR('POST', url,{gameId: game.id, id: game.opponent.id}).done(function(res){
            game.updateGameData(res.data.gameData);
            util.setTransform(
                game.thisPlayer.piece.id,
                game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].getCenterX(),
                game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].getCenterY()
            );
            game.thisPlayer.piece.changeCell(
                game.boardArr[Number(res.data.gameData.player.x)][Number(res.data.gameData.player.y)].id,
                Number(res.data.gameData.player.x),
                Number(res.data.gameData.player.y)
            );
            variable._player0Score$.text(' : ' + (Number(res.data.gameData.player.score)));
            game.update = setInterval(game.getUpdate, 2000);

        });
    },

    getUpdate : function () {
            if(game.turn == game.thisPlayer.id){
                clearInterval(game.update);
            }
            url = './games/'+ game.id;
            manager.myXHR('GET', url).done(function(res){
                console.log(res);
                if(res.data.gameData.result){
                    var text = '';
                    if( res.data.gameData.result.winner == game.thisPlayer.id){
                        text = game.thisPlayer.name;
                    }else {
                        text = gameData.opponent.name;
                    }
                    alert('you won the game');
                    $('.modal-content p').text(text + 'won this game');
                    modal.style.display = "block";
                    variable._gameSection$.empty();
                    return;
                }
                game.turn = res.data.gameData.game.whoseTurn;
                if(res.data.gameData.opponent) {
                    game.opponent.score = res.data.gameData.opponent.score;
                    game.opponent.rolled = res.data.gameData.opponent.rolled;
                        game.thisPlayer.rolled = res.data.gameData.thisPlayer.rolled;
                    util.setTransform(
                        game.opponent.piece.id,
                        game.boardArr[Number(res.data.gameData.opponent.x)][Number(res.data.gameData.opponent.y)].getCenterX(),
                        game.boardArr[Number(res.data.gameData.opponent.x)][Number(res.data.gameData.opponent.y)].getCenterY()
                    );
                    game.opponent.piece.changeCell(
                        game.boardArr[Number(res.data.gameData.opponent.x)][Number(res.data.gameData.opponent.y)].id,
                        Number(res.data.gameData.opponent.x),
                        Number(res.data.gameData.opponent.y)
                    );
                    variable._player1Score$.text(' : ' + (Number(res.data.gameData.opponent.score)));
                }
            });
    },
    canChangeUser : function () {

      if( game.turn == game.thisPlayer.id && game.thisPlayer.rolled == 'yes'){
          return true;
      }
      clearInterval(game.update);
      return false;
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
        if(game.turn != game.thisPlayer.id)
        {
            util.allWarning('Can\'t Drag. Not your turn');
            return;
        }
        if(game.thisPlayer.rolled != 'yes' && game.turn == game.thisPlayer.id) {
            util.allWarning('Please roll the dice first!');
            return;
        }
        drag.mover = which;

        drag.myX=game.thisPlayer.piece.x;
        drag.myY=game.thisPlayer.piece.y;

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
                game.updateBoard();


            }else{
                //move back
                util.setTransform(drag.mover,drag.myX,drag.myY);

            }

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
        //go through ALL of the board
        for(i=0;i<game.BOARDWIDTH;i++){
            for(j=0;j<game.BOARDHEIGHT;j++){
                var drop = game.boardArr[i][j].myBBox;

                if(x>drop.x && x<(drop.x+drop.width) && y>drop.y && y<(drop.y+drop.height)){

                   util.setTransform(id,game.boardArr[i][j].getCenterX(),game.boardArr[i][j].getCenterY());
                   game.thisPlayer.piece.changeCell(game.boardArr[i][j].id,i,j);
                   // util.changeTurn();
                    return true;
                }
            }
        }
        return false;
    },



}
