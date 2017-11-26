
// function makeElements(tag, attrs, isSVG) {
//     var elem;
//     (isSVG) ? elem=  document.createElementNS('http://www.w3.org/2000/svg', tag)
//         : elem = document.createElement(tag);
//
//     for (var k in attrs) {
//         elem.setAttribute(k, attrs[k]);
//     }
//     return elem;
// }
//
//
// function createPlayground() {
//     for (x = 0, i = 0; x < 500; x += 50) {
//         for (y = 0; y < 500; y += 50) {
//             var square = makeElements('rect', {
//                 x: x,
//                 y: y,
//                 width: 100,
//                 height: 100,
//                 fill: 'rgb(' + Math.floor(Math.random()*255) +','+ Math.floor(Math.random()*255) +','+ Math.floor(Math.random()*255) +')'
//             }, true);
//             $('#gameBoard').append(square);
//
//             square.onmousedown = function () {
//                 console.log($(this).attr('fill'));
//             }
//         }
//         i++;
//     }
//}




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
        BOARDX:40,				//starting pos of board
        BOARDY:40,				//look above
        boardArr:new Array(),		//2d array [row][col]
        pieceArr:new Array(),		//2d array [player][piece] (player is either 0 or 1)
        BOARDWIDTH:0,				//how many squares across
        BOARDHEIGHT:0,			//how many squares down
        CELLSIZE:0,
        PLAYERONE: '',
        PLAYERTWO: '',
        INVITED_DIV: '<div id="invite_message_sent">' +
        '<h3>Your request has been sent.</h3>' +
        '<span>Game will begin once the user accept the request</span></div>',
        PLAYGOUND_DIV:' <div id="playground" style="position:relative;border-right: 1px solid #4C516D;">' +
        '<svg xmlns="http://www.w3.org/2000/svg" version="1.1"  width="600px" height="600px">' +
        'Sorry! Your browser doesn\'t support SVG.' +
        '</svg>' +
        '<div style="position:absolute;top:30px;right:30px"><input type="button" value="Roll">' +
        '<div id="dice"></div></div>' +
        '</div>',

        init: function (res) {
        //create a parent to stick board in...
        var gEle = document.createElementNS(game.svgns, 'g');
        gEle.setAttributeNS(null, 'transform', 'translate(' + game.BOARDX + ',' + game.BOARDY + ')');
        gEle.setAttributeNS(null, 'id', 'gId_' + res.data.gameData.gameID);
        //stick g on board
        document.getElementsByTagName('svg')[0].appendChild(gEle);
        //create the board...
        game.BOARDHEIGHT = res.data.gameData.height;
        game.BOARDWIDTH =  res.data.gameData.width;
        game.CELLSIZE  = res.data.gameData.size;
        console.log(game.BOARDWIDTH);
            console.log(game.BOARDHEIGHT);
         var num = 100;

        for (i = 0; i <game.BOARDWIDTH ; i++) {
            game.boardArr[i] = new Array();
            for (j = 0; j <game.BOARDHEIGHT; j++) {
                game.boardArr[i][j] = new Cell(document.getElementById('gId_' + res.data.gameData.gameID), 'cell_' + j + i, game.CELLSIZE, i, j,num);
                (i%2===0)?num--:num++;
            }
            (i%2===0)?num++:num--;
            num-=10;
        }
        PLAYERONE=new Piece('game_'+res.data.gameData.gameID,0,Number(res.data.gameData.positionX),Number(res.data.gameData.positionY),'SnakeLadder',0);
        PLAYERTWO=new Piece('game_'+res.data.gameData.gameID,1,Number(res.data.gameData.positionX)+1,Number(res.data.gameData.positionY),'SnakeLadder',0);
        $('#gameID').attr('value',res.data.gameData.gameID);
        document.querySelector('input[type=button]').addEventListener('click', function(){game.roll();});
        },

    roll: function(){
        url = './games';
        gameID= $('#gameID').attr('value');
        manager.myXHR('POST', url, {gameID:gameID}).done(function(res){
            var output ='';
            var faceValue = res.diceValue;
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
    }
}
