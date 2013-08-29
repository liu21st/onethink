var scrolltotop={
	//startline: 鼠标向下滚动了100px后出现#topcontrol
	//scrollto: 它的值可以是整数，也可以是一个id标记。若为整数（假设为n），则滑动到距离top的n像素处；若为id标记，则滑动到该id标记所在的同等高处
	//scrollduration:滑动的速度
	//fadeduration:#topcontrol这个div的淡入淡出速度，第一个参数为淡入速度，第二个参数为淡出速度
	//controlHTML:控制向上滑动的html源码，默认为<img src="up.png" style="width:48px; height:48px" />，可以自行更改。该处的html代码会被包含在一个id标记为#topcontrol的div中。
	//controlattrs:控制#topcontrol这个div距离右下角的像素距离
	//anchorkeyword:滑动到的id标签
	/*state: isvisible:是否#topcontrol这个div为可见
			shouldvisible:是否#topcontrol这个div该出现
	*/

	setting: {startline:100, scrollto: 0, scrollduration:500, fadeduration:[500, 100]},
	controlHTML: '<a href="#top" class="top_stick">&nbsp;</a>',
	controlattrs: {offsetx:20, offsety:30},
	anchorkeyword: '#top',

	state: {isvisible:false, shouldvisible:false},

	scrollup:function(){
		if (!this.cssfixedsupport) {
			this.$control.css({opacity:0})
		};//点击后隐藏#topcontrol这个div
		var dest=isNaN(this.setting.scrollto)? this.setting.scrollto : parseInt(this.setting.scrollto);
		if (typeof dest=="string" && jQuery('#'+dest).length==1) { //检查若scrollto的值是一个id标记的话
			dest=jQuery('#'+dest).offset().top;
		} else { //检查若scrollto的值是一个整数
			dest=this.setting.scrollto;
		};
		this.$body.animate({scrollTop: dest}, this.setting.scrollduration);
	},

	keepfixed:function(){
		//获得浏览器的窗口对象
		var $window=jQuery(window);
		//获得#topcontrol这个div的x轴坐标
		var controlx=$window.scrollLeft() + $window.width() - this.$control.width() - this.controlattrs.offsetx;
		//获得#topcontrol这个div的y轴坐标
		var controly=$window.scrollTop() + $window.height() - this.$control.height() - this.controlattrs.offsety;
		//随着滑动块的滑动#topcontrol这个div跟随着滑动
		this.$control.css({left:controlx+'px', top:controly+'px'});
	},

	togglecontrol:function(){
		//当前窗口的滑动块的高度
		var scrolltop=jQuery(window).scrollTop();
		if (!this.cssfixedsupport) {
			this.keepfixed();
		};
		//若设置了startline这个参数，则shouldvisible为true
		this.state.shouldvisible=(scrolltop>=this.setting.startline)? true : false;
		//若shouldvisible为true，且!isvisible为true
		if (this.state.shouldvisible && !this.state.isvisible){
			this.$control.stop().animate({opacity:1}, this.setting.fadeduration[0]);
			this.state.isvisible=true;
		} //若shouldvisible为false，且isvisible为false
		else if (this.state.shouldvisible==false && this.state.isvisible){
			this.$control.stop().animate({opacity:0}, this.setting.fadeduration[1]);
			this.state.isvisible=false;
		}
	},

	init:function(){
		jQuery(document).ready(function($){
			var mainobj=scrolltotop;
			var iebrws=document.all;
			mainobj.cssfixedsupport=!iebrws || iebrws && document.compatMode=="CSS1Compat" && window.XMLHttpRequest; //not IE or IE7+ browsers in standards mode
			mainobj.$body=(window.opera)? (document.compatMode=="CSS1Compat"? $('html') : $('body')) : $('html,body');

			//包含#topcontrol这个div
			mainobj.$control=$('<div id="topcontrol">'+mainobj.controlHTML+'</div>')
				.css({position:mainobj.cssfixedsupport? 'fixed' : 'absolute', bottom:mainobj.controlattrs.offsety, right:mainobj.controlattrs.offsetx, opacity:0, cursor:'pointer'})
				.attr({title:'返回顶部'})
				.click(function(){mainobj.scrollup(); return false;})
				.appendTo('body');

			if (document.all && !window.XMLHttpRequest && mainobj.$control.text()!='') {//loose check for IE6 and below, plus whether control contains any text
				mainobj.$control.css({width:mainobj.$control.width()}); //IE6- seems to require an explicit width on a DIV containing text
			};

			mainobj.togglecontrol();

			//点击控制
			$('a[href="' + mainobj.anchorkeyword +'"]').click(function(){
				mainobj.scrollup();
				return false;
			});

			$(window).bind('scroll resize', function(e){
				mainobj.togglecontrol();
			});
		});
	}
};

scrolltotop.init();