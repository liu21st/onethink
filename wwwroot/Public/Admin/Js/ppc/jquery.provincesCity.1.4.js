/**
 * jQuery :  城市联动插件
 * @author   XiaoDong <cssrain@gmail.com>
 *			 http://www.cssrain.cn
 * @example  $("#test").ProvinceCity();
 * @params   暂无
 */
$.fn.ProvinceCity = function(str1, str2, str3){
	var _self = this;
    var str1 = '' || str1,
        str2 = '' || str2,
        str3 = '' || str3;
	//定义3个默认值
	_self.data("province",["请选择省", ""]);
	_self.data("city1",["请选择市", ""]);
	_self.data("city2",["请选择县/区", ""]);
	//插入3个空的下拉框
	_self.append("<select name='province'></select>");
	_self.append("<select name='city'></select>");
	_self.append("<select name='county'></select>");
	//分别获取3个下拉框
	var $sel1 = _self.find("select").eq(0);
	var $sel2 = _self.find("select").eq(1);
	var $sel3 = _self.find("select").eq(2);
	//默认省级下拉
	if(_self.data("province")){
		$sel1.append("<option value='"+_self.data("province")[1]+"'>"+_self.data("province")[0]+"</option>");
	}
	$.each( GP , function(index,data){
		if (str1 != '' && str1 == data) {
			var selected=' selected="true"';
			$sel1.append("<option value='"+data+"''"+ selected +"'>"+data+"</option>");
		}else {
			$sel1.append("<option value='"+data+"'>"+data+"</option>");
		}
	});
	//默认的1级城市下拉
	if(_self.data("city1")){
		$sel2.append("<option value='"+_self.data("city1")[1]+"'>"+_self.data("city1")[0]+"</option>");
	}
	//默认的2级城市下拉
	if(_self.data("city2")){
		$sel3.append("<option value='"+_self.data("city2")[1]+"'>"+_self.data("city2")[0]+"</option>");
	}
	//省级联动 控制
	var index1 = "" ;
	$sel1.change(function(){
		//清空其它2个下拉框
		//$sel2[0].options.length=0;
        //$sel3[0].options.length=0;
        $sel2.empty();
        $sel3.empty();
		index1 = this.selectedIndex;
		if(index1==0){	//当选择的为 “请选择” 时
			if(_self.data("city1")){
				$sel2.append("<option value='"+_self.data("city1")[1]+"'>"+_self.data("city1")[0]+"</option>");
			}
			if(_self.data("city2")){
				$sel3.append("<option value='"+_self.data("city2")[1]+"'>"+_self.data("city2")[0]+"</option>");
			}
		}else{
			$.each( GT[index1-1] , function(index,data){

				if (str2 != '' && str2 == data) {
					$sel2[0].options.add(new Option(data,data));
                    $sel2.val(data);
				}else {
                    $sel2[0].options.add(new Option(data,data));
				}

				$.each( GC[index1-1][index] , function(index,data){
					if (str3 != '' && str3 == data) {
                        $sel3[0].options.add(new Option(data,data));
						$sel3.val(data);
					}else {
						$sel3[0].options.add(new Option(data,data));
					}
				});
			});
            $sel2.change();
		}
	}).change();
	//1级城市联动 控制
	var index2 = "" ;
	$sel2.change(function(){
		$sel3.empty();
		index2 = this.selectedIndex;
		$.each( GC[index1-1][index2] , function(index,data){
			$sel3[0].options.add(new Option(data,data));
		});
	});
	return _self;
};