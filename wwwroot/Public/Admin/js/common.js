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

	(function(){
		//按钮组
		var timer;
		$(".btn-group").hover(function(){
			$(this).find(".dropdown").show();
		
		},function(){
			timer = setTimeout(function(){
				$(this).find(".dropdown").hide();
			},1000)
		})
	})();
});

