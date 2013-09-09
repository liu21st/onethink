$(function(){
	//图标隐藏菜单
	$(".entrance").hover(function(){
		$(this).children(".user-menu").show();
	},function(){
		$(this).children(".user-menu").hide();
	});
});
$(function(){
	$('.action .detailed').each(function(){
		$(this).click(function() {
        	detailed_content();
        	return false;
        });
  	});
});