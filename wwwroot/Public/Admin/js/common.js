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
                    location.reload();
                }else{
                    //TODO: 错误提示
                }
            });
          
        }
        return false;
    });

    //ajax post submit请求
    $('.ajax-post').click(function(){
        var target,query,form;
        var target_form = $(this).attr('target-form');
        if( (this.type=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){
            form = $('.'+target_form);
            if ( form.get(0).nodeName=='FORM' ){
                target = form.get(0).action;
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {
                query = form.serialize();
            }else{
                query = form.find('input,select,textarea').serialize();
            }
            $.post(target,query).success(function(data){
                if (data.status==1) {
                    location.reload();
                }else{
                    //TODO: 错误提示
                }
            });
        }
        return false;
    });
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

