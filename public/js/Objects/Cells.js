//////////////////////////////////////////////////////
// Class: Cell										//
// Description:  This will create a cell object		//
// (board square) that you can reference from the 	//
// game. 											//
// Arguments:										//
//		size - tell the object it's width & height	//
//		??
//		??
//		??
//		??
//////////////////////////////////////////////////////
//Cell constructor()
function Cell(parent,id,size,row,col){
    this.parent=parent;
    this.id=id;
    this.size=size;
    this.row=row;
    this.col=col;
    //initialize other instance vars
    //this.occupied=''; //hold the id of the piece
    this.y=this.size*this.row;
    this.x=this.size*this.col;
    this.color= 'white';
    this.droppable=(((this.row+this.col)%2) == 0)? true:false;

    this.object=this.create();
    this.parent.appendChild(this.object);
    //this.myBBox = this.getMyBBox();
    console.log(this.myBBox);
}



//////////////////////////////////////////////////////
// Cell : Methods									//
// Description:  All of the methods for the			//
// Cell Class (remember WHY we want these to be		//
// seperate from the object constructor!)			//
//////////////////////////////////////////////////////
Cell.prototype={
    create:function(){
        var rectEle=document.createElementNS(game.svgns,'rect');
        rectEle.setAttributeNS(null,'x',this.x+'px');
        rectEle.setAttributeNS(null,'y',this.y+'px');
        rectEle.setAttributeNS(null,'width',this.size+'px');
        rectEle.setAttributeNS(null,'height',this.size+'px');
        rectEle.setAttributeNS(null,'class', 'cells_white');
        rectEle.setAttributeNS(null,'id',this.id);
        rectEle.onclick=function(){alert(this.id);};
        return rectEle;
    },
    //get my bbox
    getMyBBox:function(){
        return this.object.getBBox();
    },
    //get CenterX
    getCenterX:function(){
        return (game.BOARDX+this.x+(this.size/2) );
    },
    //get CenterY
    getCenterY:function(){
        return (game.BOARDY+this.y+(this.size/2) );
    },
    //set a cell to occupied
    isOccupied:function(pieceId){
        this.occupied=pieceId;
    },
    //set cell to empty
    notOccupied:function(){
        this.occupied='';
    },
    PI:3.1415697
}