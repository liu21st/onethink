// +----------------------------------------------------------------------
// | ThinkJS -- Think MVC Javascript Framework                                                           
// +----------------------------------------------------------------------
// | Copyright (c) 2008 http://thinkphp.cn All rights reserved.      
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>                                  
// +----------------------------------------------------------------------
// $Id$

/* AJAX Star Rating v1.0.2, Programming by Ulyses */
/* Updated February 7th, 2007 */

function $(o) { return((typeof(o)=='object'?o:document).getElementById(o)); }
function $S(o) { return($(o).style); }
function agent(v) { return(Math.max(navigator.userAgent.toLowerCase().indexOf(v),0)); }
function abPos(o) { var o=(typeof(o)=='object'?o:$(o)), z={X:0,Y:0}; while(o!=null) { z.X+=o.offsetLeft; z.Y+=o.offsetTop; o=o.offsetParent; }; return(z); }
function XY(e,v) { var o=agent('msie')?{'X':event.clientX+document.body.scrollLeft,'Y':event.clientY+document.body.scrollTop}:{'X':e.pageX,'Y':e.pageY}; return(v?o[v]:o); }

var star={

    /* Mouse Events */
    
    'cur':function(e,o) { if(star.stop) { star.stop=0;

        document.onmousemove=function(e) { var n=star.num;
        
            var p=abPos($('star'+n)), x=XY(e), oX=x.X-p.X, oY=x.Y-p.Y; star.num=o.id.substr(4);

            if(oX<1 || oX>84 || oY<0 || oY>19) { star.stop=1; star.revert(); }
            
            else {

                $S('starCur'+n).width=oX+'px';
                $S('starUser'+n).color='#111';
                $('starUser'+n).innerHTML=Math.round(oX/84*100)+'%';
            }
        };
    } },
    'update':function(e,o) { var n=star.num, v=parseInt($('starUser'+n).innerHTML);
    
        n=o.id.substr(4); $('starCur'+n).title=v;

        req=new XMLHttpRequest(); req.open('GET','/AJAX_Star_Vote.php?vote='+(v/100),false); req.send(null);    

    },
    'revert':function() { var n=star.num, v=parseInt($('starCur'+n).title);
    
        $S('starCur'+n).width=Math.round(v*84/100)+'px';
        $('starUser'+n).innerHTML=(v>0?Math.round(v)+'%':'');
        $('starUser'+n).style.color='#888';
        
        document.onmousemove='';

    },

    /* Data */

    'stop':1,
    
    'num':0

};