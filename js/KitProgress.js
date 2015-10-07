function KitProgress(c,h){
    this.color = c;
    this.height = h;

    this.myId = "KitProgressBar";
    this.delay = 10;
    this.randInt = 10;
    this.def = 15;
    this.endDuration = 0.5;

    this.elem;
    this.timer;
    this.now;
    this.step;
    this.max;
    this.counter = 0;
    this.blocked = false;

    this.isBlocked = function(){
        return this.blocked;
    }

    this.init = function(){
        var html = $("<div id='"+this.myId+"'></div>");
        $("body").prepend(html);
        this.elem = $("#"+this.myId);
        this.elem.css({
            "background-color" : this.color,
            "position" : "fixed",
            "left" : 0,
            "top" : 0,
            "display" : "none",
            "height" : this.height,
            "z-index" : 9999
        });
    }
    this.setColor = function(c){
        this.color = c;
        this.elem.css({"background-color" : this.color});
    }
    this.start = function(seconds){
        this.blocked = true;
        this.now = 0;
        this.max = 100;
        this.elem.fadeIn(100);
        this.elem.css({
            "width" : 0
        });
        this.inc(seconds*Math.ceil((this.counter+1)/2));
        this.counter++;
        console.log("start "+this.counter);
    }
    this.incPar = function(int){
        this.randInt = int;
        this.inc();
    }
    this.inc = function(s){
        clearInterval(this.timer);
        var seconds = (s)?s:2;
        var m = ( this.now + this.randInt <= 80 )?(this.now+this.randInt):80;
        this.max = (s)?100:(Math.random() * (m - (this.now+5) ) + (this.now+5));
        console.log(this.now+" "+this.max);
        this.step = (100-this.now)/(seconds*1000/this.delay);
        var tmp = this;
        var tmpS = s;
        tmp.timer = setInterval(function(){
            var st = ( tmpS ) ? ( tmp.step * ((100-tmp.now)/100) ):tmp.step;
            tmp.now += st;
            tmp.elem.css("width",tmp.now+"%");
            if (tmp.now>=tmp.max){
                clearInterval(tmp.timer);
                tmp.inc(tmp.def);
            }
        },tmp.delay);
    }
    this.end = function(callback){
        this.counter--;
        console.log("end "+this.counter);
        if( this.counter == 0 ){
            clearInterval(this.timer);
            this.step = (100-this.now)/(this.endDuration*1000/this.delay);
            var tmp = this;
            tmp.timer = setInterval(function(){
                tmp.now += tmp.step;
                tmp.elem.css("width",tmp.now+"%");
                if (tmp.now>=100){
                    tmp.elem.fadeOut(200);
                    clearInterval(tmp.timer);
                    tmp.blocked = false;
                    if( callback ) callback();
                }
            },tmp.delay);
        }else{
            callback();
        }
    }
    this.init();
}