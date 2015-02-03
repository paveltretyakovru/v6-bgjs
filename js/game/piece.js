var Piece = function(color , id , layer , stage , type , imageObjects , game , board){
    var self = this;
    var imagesrc = '';
    var controll = true;
    
    if(imageObjects !== undefined){
        this.whiteObj = imageObjects.white;
        this.blackObj = imageObjects.black;
        this.lightObj = imageObjects.light;
    }else{
        controll = false;
        console.error("Ошибка при загрузки изображений фишек");
    }
    
    if(layer !== undefined && stage !== undefined && id !== undefined && type !== undefined){
        this.layer  = layer;
        this.stage  = stage;
        this.type   = type;
        this.id     = id;
        this.color  = color;
        this.game   = game;
        this.board  = board;
    }else{
        controll    = false;
        console.error('Один из параметров создания фишки равен undefined: ' , color , id , type);
    }
    
    switch (color) {
        case 'white':
            imagesrc    = 'images/pieces/white.png';
            break;
        case 'black':
            imagesrc    = 'images/pieces/black.png';
            break;
        case 'light':
            imagesrc    = 'images/pieces/light.png';
            break;
        default:
            console.error('Передан неизвестный цвет фишек');
            controll = false;
    }
    
    if(controll){
        // инициализируем изображение
        var pimage;
        
        if(color === 'white'){
            pimage = this.whiteObj;
        }else if(color === 'black'){
            pimage = this.blackObj;
        }else if(color === 'light'){
            pimage = this.lightObj;
        }
        
        //pimage = new Image();
        //pimage.src = imagesrc;
        
        // создаем Kineticjs изображение
        var pimageobj = new Kinetic.Image({
            x       : 0 ,
            y       : 0 ,
            id      : this.id ,
            width   : this.width ,
            height  : this.height ,
            image   : pimage ,
            
            //draggable : true
        });
        
        this.obj = pimageobj;
        
        this.layer.add(pimageobj);
        this.stage.batchDraw();

    }else{
        console.error("При создании фишки произошла ошибка. Проверьте переданные аргументы");
    }
};

Piece.prototype.moveTo = function( x , y){
    var self = this;
    
    /* Вычисляем время полета фишек */
    var d = Math.sqrt( Math.pow(x - this.obj.x() , 2) + Math.pow(y - this.obj.y() , 2) );
	var movetime = d * 0.002;
    
    var tween = new Kinetic.Tween({
		node : self.obj ,
		duration : movetime ,
		x : x ,
		y : y ,
		onFinish : function(){
		    
		    if(self.color === 'white'){
		        self.obj.setImage(self.whiteObj);
		    }else{
		        self.obj.setImage(self.blackObj);
		    }
		    
		    self.game.selectedpiece = false;
		    //self.board.mainlayer.off('click');
		    //self.obj.off('click');
		    
		    if(self.house){
		        //self.obj.destroy();
		        
		        /*
		            Теперь фишку не уничтожаем, а скрываем, если вдруг шеф
		            захочет вывести снова её из дома в конце игры
		        */
		        self.obj.hide();
                self.stage.batchDraw();
		    }
		}
	});

	tween.play();
	
	ion.sound.play("piece");
	
	return movetime;
};

Piece.prototype.x       = 0;
Piece.prototype.y       = 0;
Piece.prototype.field   = 0;
Piece.prototype.width   = 34;
Piece.prototype.height  = 34;
Piece.prototype.movetime= 0.5;

Piece.prototype.id      = '';
Piece.prototype.type    = '';
Piece.prototype.color   = '';
Piece.prototype.house   = false;
Piece.prototype.last    = false;

Piece.prototype.layer   = {};
Piece.prototype.stage   = {};
Piece.prototype.obj     = {};
Piece.prototype.oldpos  = {};

Piece.prototype.blackObj;
Piece.prototype.whiteObj;
Piece.prototype.lightObj;
Piece.prototype.game;
Piece.prototype.board;
Piece.prototype.nextp;