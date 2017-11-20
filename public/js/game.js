
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


document.querySelector('input[type=button]').addEventListener('click', function(){game.roll();});

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
        //pieceArr:new Array(),		//2d array [player][piece] (player is either 0 or 1)
        BOARDWIDTH:10,				//how many squares across
        BOARDHEIGHT:10,			//how many squares down
        CELLSIZE:50,
        init: function () {
        //create a parent to stick board in...
        var gEle = document.createElementNS(game.svgns, 'g');
        gEle.setAttributeNS(null, 'transform', 'translate(' + game.BOARDX + ',' + game.BOARDY + ')');
        gEle.setAttributeNS(null, 'id', 'gId_' + 'something');
        //stick g on board
        document.getElementsByTagName('svg')[0].appendChild(gEle);
        //create the board...
        //var x = new Cell(document.getElementById('someIDsetByTheServer'),'cell_00',CELLSIZE,0,0);
        for (i = 0; i < game.BOARDWIDTH; i++) {
            game.boardArr[i] = new Array();
            for (j = 0; j < game.BOARDHEIGHT; j++) {
                game.boardArr[i][j] = new Cell(document.getElementById('gId_' + 'something'), 'cell_' + j + i, game.CELLSIZE, j, i);
            }
        }
    },

    roll: function(){

        url = './games';
        manager.myXHR('POST', url, {id: 1}).done(function(res){
            var output =''
            var faceValue = res.dice_value;
            output += "&#x268" + faceValue + "; ";
            document.getElementById('dice').innerHTML = output;
        });
    }
}