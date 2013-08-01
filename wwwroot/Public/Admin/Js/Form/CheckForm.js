/*
+--------------------------------------------------------
| 项目: 流年基类库--JS脚本库
| 版本: 0.1
| 作者: Liu21st < Liu21st2002@msn.com >
| 文件: 
| 功能: 
+--------------------------------------------------------
| 版权声明: Copyright◎ 2004-2005 世纪流年 版权所有
| WebURL:	http://blog.liu21st.com
| EMail:	liu21st@gmail.com
+--------------------------------------------------------
*/

/*使用说明
+--------------------------------------------------------
  <form name="form1" onsubmit="return CheckForm(this)">
    <input type="text" name="id" check="^\S+$" warning="id不能为空,且不能含有空格">
    <input type="submit">
    </form>

该方法主要就是设置各个验证项的正则表达式
基本技巧
判断位数 ^.{4,20}
+--------------------------------------------------------
*/

	//预定义验证格式
	var RegNames = new Array();
	RegNames	=	['Email','Phone','Require','Number','Zip'];
	var RegArray = {
		Require : /.+/,
		Email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
		Phone : /^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/,
		Mobile : /^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$/,
		Url : /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
		Currency : /^\d+(\.\d+)?$/,
		Number : /^\d+$/,
		Zip : /^[1-9]\d{5}$/,
		QQ : /^[1-9]\d{4,8}$/,
		Integer : /^[-\+]?\d+$/,
		Double : /^[-\+]?\d+(\.\d+)?$/,
		English : /^[A-Za-z]+$/,
		Chinese :  /^[\u0391-\uFFE5]+$/,
		Username : /^[a-z]\w{3,}$/i,
		UnSafe : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/
	}

	//主函数
	function CheckForm(oForm,target)
	{
		var els = oForm.elements;
		//遍历所有表元素
		for(var i=0;i<els.length;i++)
		{
			//是否需要验证
			if(els[i].check)
			{
				//取得验证的正则字符串
				var sReg = Filter(trim(els[i].check));
				//取得表单的值,用通用取值函数
				var sVal = GetValue(els[i]);
				//字符串->正则表达式,不区分大小写
				var reg = new RegExp(sReg,"i");
				if(!reg.test(sVal))
				{
					//验证不通过,弹出提示warning
					//els[i].styles.border = '1pt solid orange';
					if (target==undefined)
					{
						alert(els[i].warning);
					}else {
						$(target).style.display='block';
						$(target).innerHTML	=	'<div style="font-weight:bold;color:red">'+els[i].warning+'</div>';
						this.intval = window.setTimeout(function (){
							//var myFx = new Fx.Style(target, 'opacity',{duration:1000}).custom(1,0);
							$(target).style.display='none';$(target).innerHTML='';
							},3000);
					}

					//该表单元素取得焦点,用通用返回函数
					GoBack(els[i]);
					return false;
				}
			}
		}
		return true;
	}

	function trim(s) 
	{
		return s.replace( /^\s*/, "" ).replace( /\s*$/, "" );
    }

	//过滤和转换正则表达式
	//支持预定义正则和表达式两种
	//预定义正则参考RegNames数组
	function Filter(str)
	{
		if (RegNames.toString().indexOf(str)!=-1)
		{
			return RegArray[str].toString().replace( /^\/*/, "" ).replace( /\/*$/, "" );
		}
		return str;
	}

	//通用取值函数分三类进行取值
	//文本输入框,直接取值el.value
	//单多选,遍历所有选项取得被选中的个数返回结果"00"表示选中两个
	//单多下拉菜单,遍历所有选项取得被选中的个数返回结果"0"表示选中一个
	function GetValue(el)
	{
		//取得表单元素的类型
		var sType = el.type;
		switch(sType)
		{
			case "text":
			case "hidden":
			case "password":
			case "file":
			case "textarea":  return el.value;
			case "checkbox":
			case "radio": return GetValueChoose(el);
			case "select-one":
			case "select-multiple": return GetValueSel(el);
		}
	}

		//取得radio,checkbox的选中数,用"0"来表示选中的个数,我们写正则的时候就可以通过0{1,}来表示选中个数
		function GetValueChoose(el)
		{
			var sValue = "";
			//取得第一个元素的name,搜索这个元素组
			var tmpels = document.getElementsByName(el.name);
			for(var i=0;i<tmpels.length;i++)
			{
				if(tmpels[i].checked)
				{
					sValue += "0";
				}
			}
			return sValue;
		}
		//取得select的选中数,用"0"来表示选中的个数,我们写正则的时候就可以通过0{1,}来表示选中个数
		function GetValueSel(el)
		{
			var sValue = "";
			for(var i=0;i<el.options.length;i++)
			{
				//单选下拉框提示选项设置为value=""
				if(el.options[i].selected && el.options[i].value!="")
				{
					sValue += "0";
				}
			}
			return sValue;
		}
	//通用返回函数,验证没通过返回的效果.分三类进行取值
	//文本输入框,光标定位在文本输入框的末尾
	//单多选,第一选项取得焦点
	//单多下拉菜单,取得焦点
	function GoBack(el)
	{
		//取得表单元素的类型
		var sType = el.type;
		switch(sType)
		{
			case "text":
			case "hidden":
			case "password":
			case "file":
			case "textarea": 
				el.focus();
				var rng = el.createTextRange(); 
				rng.collapse(false); 
				rng.select();
			case "checkbox":
			case "radio": 
				var els = document.getElementsByName(el.name);
				els[0].focus();
			case "select-one":
			case "select-multiple":
				el.focus();
		}
	}


/*
使用范例

<script language="JavaScript" src="Check.js"></script>

<form name="form1" onsubmit="return CheckForm(this)">
test:<input type="text" name="test">不验证<br>
账号:<input type="text" check="^\S+$" warning="账号不能为空,且不能含有空格" name="id">不能为空<br>
密码:<input type="password" check="\S{6,}" warning="密码六位以上" name="id">六位以上<br>
电话:<input type="text" check="^\d+$" warning="电话号码含有非法字符" name="number" value=""><br>
相片上传:<input type="file" check="(.*)(\.jpg|\.bmp)$" warning="相片应该为JPG,BMP格式的" name="pic" value="1"><br>
出生日期:<input type="text" check="^\d{4}\-\d{1,2}-\d{1,2}$" warning="日期格式2004-08-10"  name="dt" value="">日期格式2004-08-10<br>
省份:
<select name="sel" check="^0$" warning="请选择所在省份">
<option value="">请选择
<option value="1">福建省
<option value="2">湖北省
</select>
<br>
选择你喜欢的运动:<br>
游泳<input type="checkbox" name="c" check="^0{2,}$" warning="请选择2项或以上">
篮球<input type="checkbox" name="c">
足球<input type="checkbox" name="c">
排球<input type="checkbox" name="c">
<br>
你的学历:
大学<input type="radio" name="r" check="^0$" warning="请选择一项学历">
中学<input type="radio" name="r">
小学<input type="radio" name="r">
<br>
个人介绍:
<textarea name="txts" check="^[\s|\S]{20,}$" warning="个人介绍不能为空,且不少于20字"></textarea>20个字以上
<input type="submit">
</form>

*/