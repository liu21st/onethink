//dom加载完成后执行的js
;$(function(){

	//全选的实现
	$(".check-all").click(function(){
		$(".ids").prop("checked", this.checked);
	});
	$(".ids").click(function(){
		var option = $(".ids");
		option.each(function(i){
			if(!this.checked){
				$(".check-all").prop("checked", false);
				return false;
			}else{
				$(".check-all").prop("checked", true);
			}
		});
	});

    //ajax get请求
    $('.ajax-get').click(function(){
        var target;
        if ( $(this).hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {
            $.get(target).success(function(data){
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
                    }else{
                        updateAlert(data.info + ' 页面即将自动刷新~','alert-success');
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }
                        location.reload();
                    },1500);
                }else{
                    updateAlert(data.info);
                }
            });
          
        }
        return false;
    });

    //ajax post submit请求
    $('.ajax-post').click(function(){
        var target,query,form;
        var target_form = $(this).attr('target-form');
        if ( $(this).hasClass('confirm') ) {
            if(!confirm('确认要执行该操作吗?')){
                return false;
            }
        }
        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);
            if (form.get(0)==undefined){
                return false;
            }else if ( form.get(0).nodeName=='FORM' ){
                target = form.get(0).action;
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                query = form.serialize();
            }else{
                query = form.find('input,select,textarea').serialize();
            }
            $.post(target,query).success(function(data){
                if (data.status==1) {
                    if (data.url) {
                        updateAlert(data.info + ' 页面即将自动跳转~','alert-success');
                    }else{
                        updateAlert(data.info + ' 页面即将自动刷新~','alert-success');
                    }
                    setTimeout(function(){
                        if (data.url) {
                            location.href=data.url;
                        }
                        location.reload();
                    },1500);
                }else{
                    updateAlert(data.info);
                }
            });
        }
        return false;
    });

	/**顶部警告栏*/
	var content = $('#main');
	var top_alert = $('#top-alert');
	top_alert.find('.close').on('click', function () {
		top_alert.removeClass('block').slideUp(200);
		content.animate({marginTop:'-=36'},200);
	});

    window.updateAlert = function (text,c) {
		text = text||'default';
		c = c||false;
		if ( text!='default' ) {
            top_alert.find('.alert-content').text(text);
			if (top_alert.hasClass('block')) {
			} else {
				top_alert.addClass('block').slideDown(200);
				content.animate({marginTop:'+=36'},200);
			}
		} else {
			if (top_alert.hasClass('block')) {
				top_alert.removeClass('block').slideUp(200);
				content.animate({marginTop:'-=36'},200);
			}
		}
		if ( c!=false ) {
            top_alert.removeClass('alert-error alert-warn alert-info alert-success').addClass(c);
		}
	};

	(function(){
		//按钮组
		$(".btn-group").mouseenter(function(){
			var userMenu = $(this).children(".dropdown ");
			userMenu.show();
			clearTimeout(userMenu.data("timeout"));
		}).mouseleave(function(){
			var userMenu = $(this).children(".dropdown");
			userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
			userMenu.data("timeout", setTimeout(function(){userMenu.hide()}, 100));
		});
	})();
});

