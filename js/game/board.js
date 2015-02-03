/* 
    *
    *               *** BOARD.JS ***
    *
    * Для корректной рабоыт объекта необходимо подключить библиотеку Kinetic.js
*/

var Board = function(){}

/* configs values */
Board.prototype.width       = 600;
Board.prototype.height      = 600;
Board.prototype.image       = 'images/tables/2dboard.png';
Board.prototype.htmlid      = 'container';
Board.prototype.imagex      = 0;
Board.prototype.imagey      = 0;
Board.prototype.pieceheight = 34;
Board.prototype.topy        = 28;
Board.prototype.bottomy     = 540;
Board.prototype.startside   = 'right'

/* empty values */
Board.prototype.stage       = {};
Board.prototype.mainlayer   = {};
Board.prototype.piececolor  = '';   // black || white

/* functions values */

Board.prototype.addPiece = function(pieceid , fieldnum){
    if(this.checkCorrectFieldNum(fieldnum)){
        this.fields[fieldnum].pieces.push(pieceid);
    }else{
        console.error('Ошибка с типом поля');
    }
};

/* 
    #
    # fun calcLastFieldPos(num field) : obj { int x , int y }
    # Вычисляем координаты последней позиции на поле
    # int fieldnum - номер поля
    #
*/
Board.prototype.calcLastFieldPos = function(fieldnum){
    
    if(this.checkCorrectFieldNum(fieldnum)){
        // номер последней позиции в поле
        
        var lastnum = this.fields[fieldnum].pieces.length;
            
        // field x
        var fx  = this.fields[fieldnum].x;
        var y   = 0;
        
        if(this.startside === 'right'){
        
            // определяем y
            if(fieldnum >= 13){
                y = this.bottomy - lastnum * this.pieceheight;
            }else{
                y = this.topy + lastnum * this.pieceheight;
            }
        
        }else{
            
            // определяем y
            if(fieldnum < 13){
                y = this.bottomy - lastnum * this.pieceheight;
            }else{
                y = this.topy + lastnum * this.pieceheight;
            }
            
        }
        
        return { x : fx , y : y };
    }
};

Board.prototype.calcPosCoords = function(pos){
    var field   = pos[0];
    var index   = pos[1];
    
    if(this.checkCorrectFieldNum(field)){
        // номер последней позиции в поле
        
        var lastnum = index;
            
        // field x
        var fx  = this.fields[field].x;
        var y   = 0;
        
        if(this.startside === 'right'){
        
            // определяем y
            if(field >= 13){
                y = this.bottomy - lastnum * this.pieceheight;
            }else{
                y = this.topy + lastnum * this.pieceheight;
            }
        
        }else{
            
            // определяем y
            if(field < 13){
                y = this.bottomy - lastnum * this.pieceheight;
            }else{
                y = this.topy + lastnum * this.pieceheight;
            }
        }
        
        return { x : fx , y : y };
    }
};

/*
    #
    # fun checkCorrectFieldNum(int fieldnum) : bool
    # проверяем корректность переменной обращаемой к полю доски
    #
*/

Board.prototype.checkCorrectFieldNum = function(fieldnum){
    if(fieldnum !== undefined && typeof(fieldnum) === 'number'){
        if(this.fields[fieldnum] !== undefined){
            return true;
        }else{
            console.error('Передан неправильный номер поля');
            return false;
        }
    }else{
        console.error('Не определен параметр fieldnum, либо неправильного типа: ' , fieldnum , typeof(fieldnum));
        return false;
    }
};

/*
    #
    # Вычисляет номер поля по координатам
    #
*/
Board.prototype.calcField = function(x , y , params){
    //console.log('CALC FIELD!!!');
    var fields 		= this.fields;
	var width 		= this.pieceheight / 2 + 5;
	var top_height 	= this.height / 2;
	var num 		= 1;
	
	// если игрок расположен изначально слева
	if(this.startside === 'left'){
	    
	    if(x > fields[6].x && x < fields[7].x - 21){
    	    if(y >= top_height){
    	        num = 6;
    	    }else{
    	        num = 18;
    	    }
    	}
	
    	for(var i = 1; i < fields.length; i++){
    		if(x <= fields[i].x + width && x >= fields[i].x - width){
    			if(y <= top_height && i >= 13){
    				num = i;
    			}else if(y > top_height && i <= 12){
    				num = i;
    			}
    		}
    	}
    	
    	if(x > fields[12].x){
    		if(y >= top_height){
    			num = 12;
    		}else{
    			num = 13;
    		}
    	}
    	
    	if(x < fields[1].x){
    		if(y >= top_height){
    			num = 1;
    		}else{
    			num = 24;
    		}
    	}
    	
    // если игрок расположен изначально справа
	}else{
	    
	    if(x < fields[6].x && x > fields[7].x - 21){
    	    if(y >= top_height){
    	        num = 18;
    	    }else{
    	        num = 6;
    	    }
    	}
	    
	    for(var i = 1; i < fields.length; i++){
    		if(x <= fields[i].x + width && x >= fields[i].x - width){
    			if(y <= top_height && i <= 13){
    				num = i;
    			}else if(y > top_height && i >= 12){
    				num = i;
    			}
    		}
    	}
    	
    	if(x < fields[12].x){
    		if(y >= top_height){
    			num = 13;
    		}else{
    			num = 12;
    		}
    	}
    	
    	if(x > fields[1].x){
    		if(y >= top_height){
    			num = 24;
    		}else{
    			num = 1;
    		}
    	}
	    
	}
	
	//console.info('Calc field:' , params , num);

    // проверяем перетягивают ли фишку на первое поле
    if(params !== undefined){
            if('oldfield' in params){
                    if(num === 24 && params.oldfield < 7 && params.oldfield !== 0){
                            return 1;
                    }
            }
    }
	
	return num;
};

/*
    #
    # Создание доски
    #
*/
Board.prototype.init = function(){
    var self = this;
    
    // создаем главнй объект игрового поля
    this.stage = new Kinetic.Stage({
            container   : this.htmlid ,
            width       : this.width ,
            height      : this.height
        });
    
    // инициализируем изображение
    var bimage = new Image();
    
    // создаем Kineticjs изображение
    bimage.onload = function(){
        var bimageobj = new Kinetic.Image({
            x       : self.imagex ,
            y       : self.imagey ,
            width   : self.width ,
            height  : self.height ,
            image   : bimage
        });
        
         // создаем основной слой
        self.mainlayer = new Kinetic.Layer();
        self.mainlayer.add(bimageobj);
        
        self.stage.add(self.mainlayer);
        self.stage.batchDraw();
    }
    
    bimage.src = this.image;
};

if(Board.prototype.startside === 'left'){
    Board.prototype.fields = [
        0 	,	// Заглушка для нулевого элемента массива
        // BOTTOM FIELDS
    	{x : 26  , pieces : [] }	,	// 1
    	{x : 71  , pieces : [] } 	,	// 2
    	{x : 114 , pieces : [] } 	,	// 3
    	{x : 157 , pieces : [] } 	,	// 4
    	{x : 200 , pieces : [] } 	,	// 5
    	{x : 243 , pieces : [] } 	,	// 6
    	{x : 325 , pieces : [] } 	,	// 7
    	{x : 368 , pieces : [] } 	,	// 8
    	{x : 413 , pieces : [] } 	,	// 9
    	{x : 456 , pieces : [] } 	,	// 10
    	{x : 499 , pieces : [] } 	,	// 11
    	{x : 542 , pieces : [] } 	,	// 12
        
        // TOP FIELDS
    	{x : 544 , pieces : [] }	,	// 13
    	{x : 500 , pieces : [] } 	,	// 14
    	{x : 457 , pieces : [] } 	,	// 15
    	{x : 414 , pieces : [] } 	,	// 16
    	{x : 370 , pieces : [] } 	,	// 17
    	{x : 326 , pieces : [] } 	,	// 18
    	{x : 244 , pieces : [] } 	,	// 19
    	{x : 201 , pieces : [] } 	,	// 20
    	{x : 156 , pieces : [] } 	,	// 21
    	{x : 115 , pieces : [] } 	,	// 22
    	{x : 68  , pieces : [] } 	,	// 23
    	{x : 26  , pieces : [] } 		// 24
    ];    
}else{
    Board.prototype.fields = [
        0 	,	// Заглушка для нулевого элемента массива
        // TOP FIELDS
    	{x : 544 , pieces : [] }	,	// 13
    	{x : 500 , pieces : [] } 	,	// 14
    	{x : 457 , pieces : [] } 	,	// 15
    	{x : 414 , pieces : [] } 	,	// 16
    	{x : 370 , pieces : [] } 	,	// 17
    	{x : 326 , pieces : [] } 	,	// 18
    	{x : 244 , pieces : [] } 	,	// 19
    	{x : 201 , pieces : [] } 	,	// 20
    	{x : 156 , pieces : [] } 	,	// 21
    	{x : 115 , pieces : [] } 	,	// 22
    	{x : 68  , pieces : [] } 	,	// 23
    	{x : 26  , pieces : [] } 	,	// 24
        
    	// BOTTOM FIELDS
    	{x : 26  , pieces : [] }	,	// 1
    	{x : 71  , pieces : [] } 	,	// 2
    	{x : 114 , pieces : [] } 	,	// 3
    	{x : 157 , pieces : [] } 	,	// 4
    	{x : 200 , pieces : [] } 	,	// 5
    	{x : 243 , pieces : [] } 	,	// 6
    	{x : 325 , pieces : [] } 	,	// 7
    	{x : 368 , pieces : [] } 	,	// 8
    	{x : 413 , pieces : [] } 	,	// 9
    	{x : 456 , pieces : [] } 	,	// 10
    	{x : 499 , pieces : [] } 	,	// 11
    	{x : 542 , pieces : [] } 		// 12
    ];
}