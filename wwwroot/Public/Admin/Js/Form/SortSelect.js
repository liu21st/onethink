// +---------------------------------------------------------------------------+
// | FCS -- Fast,Compatible & Simple OOP PHP Framework                         |
// | FCS JS 基类库                                                             |
// +---------------------------------------------------------------------------+
// | Copyright (c) 2005-2006 liu21st.com.  All rights reserved.                |
// | Website: http://www.fcs.org.cn/                                           |
// | Author : Liu21st 流年 <liu21st@gmail.com>                                 |
// +---------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify it   |
// | under the terms of the GNU General Public License as published by the     |
// | Free Software Foundation; either version 2 of the License,  or (at your   |
// | option) any later version.                                                |
// |                                                                           |
// | This program is distributed in the hope that it will be useful,  but      |
// | WITHOUT ANY WARRANTY; without even the implied warranty of                |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General |
// | Public License for more details.                                          |
// +---------------------------------------------------------------------------+

/**
 +------------------------------------------------------------------------------
 * 下拉列表多选类
 +------------------------------------------------------------------------------
 * @package    Form
 * @link       http://www.fcs.org.cn
 * @copyright  Copyright (c) 2005-2006 liu21st.com.  All rights reserved. 
 * @author     liu21st <liu21st@gmail.com>
 * @version    $Id$
 +------------------------------------------------------------------------------
 */
/*使用说明
+--------------------------------------------------------
在页面中添加下面代码
<script language="JavaScript" src="SortSelect.js"></script>
<FORM METHOD=POST name = 'form1' ACTION="">
<input name="search" type="text"><INPUT TYPE="button" onClick="sl.Search()" value="查 询" >
<SELECT name="sort" size="12">
<option value=1>1.name1</option>
<option value=2>2.name2</option>
<option value=3>3.name3</option>
</SELECT>
<INPUT TYPE="button" onClick="sl.fnFirst()" value="第一" >
<INPUT TYPE="button" onClick="sl.sortUp()" value="上移" >
<INPUT TYPE="button" onClick="sl.sortDown()" value="下移" >
<INPUT TYPE="button" onClick="sl.fnEnd()" value="最后" >
<INPUT TYPE="text" NAME="jumpNum" size="5">
<INPUT TYPE="button" onClick="sl.jump()" value="跳转" >
<INPUT TYPE="hidden" name="seqNoList">
<INPUT TYPE="button" onClick="sl.ok()" value="确定">
</FORM>
<SCRIPT LANGUAGE="JavaScript">
<!--
var sl = new SortList('form1','sort','search','jumpNum');
//-->
</SCRIPT>

确定后排序的结果保存在seqNoList中，格式为
编号:序号,编号:序号
+--------------------------------------------------------
*/

function SortSelect(formName,sortPan,searchName,jumpNum){

	//对象属性
	this.FormObj	= document.getElementsByName(formName)[0];
	this.sortPan	= document.getElementsByName(sortPan)[0];
	this.searchObj  = document.getElementsByName(searchName)[0];
	this.jumpNum	= document.getElementsByName(jumpNum)[0];

	//+--------------------------------------------------------
	//| 方法：列表搜索
	//+--------------------------------------------------------

	this.Search	= function(){
		var length = this.sortPan.options.length;
		for (i =0 ;i<length; i++){
			if (this.sortPan.options[i].text.indexOf(this.searchObj.value)!=-1)
			{
				this.sortPan.item(i).selected = true;
				break;
			}
		}
	}
	//+--------------------------------------------------------
	//| 方法：跳转
	//+--------------------------------------------------------
	this.jump = function (){
		var n= this.jumpNum.value;
		var iIndex = this.sortPan.selectedIndex;	
		var i= n-1;
		if( i == iIndex) return;
		if (i<iIndex)	
			{
			for (k=0;k<iIndex-i;k++)	this.sortUp();
			}
		else 
			{
			for (k=0;k<i-iIndex;k++)	this.sortDown();
			}
	}
	//+--------------------------------------------------------
	//| 方法：上移一位
	//+--------------------------------------------------------
	this.sortUp = function ()
	{
		try
		{
			var iIndex = this.sortPan.selectedIndex;	
			
			if(iIndex == 0)
			{
				return;
			}
			var curName = this.sortPan.item(iIndex).text;
			var ilength,iplace
			ilength=curName.length;
			iplace=curName.indexOf(".");
			var strNameState,strNameSpace
			strNameState=curName.substr(iplace+1,ilength)
			strNameSpace=curName.substr(0,iplace+1)
			var strNameStateMiddle
			strNameStateMiddle=strNameState
			
			var curName1 = this.sortPan.item(iIndex-1).text;
			var ilength1,iplace1
			ilength1=curName1.length;
			iplace1=curName1.indexOf(".");
			var strNameState1,strNameSpace1
			strNameState1=curName1.substr(iplace1+1,ilength1)
			strNameSpace1=curName1.substr(0,iplace1+1)
			strNameState=strNameState1
			strNameState1=strNameStateMiddle
			this.sortPan.item(iIndex).text =strNameSpace+strNameState
			 this.sortPan.item(parseInt(iIndex) - 1).text=strNameSpace1+strNameState1
	
			var curValue = this.sortPan.item(iIndex).value;
			this.sortPan.item(iIndex).value = this.sortPan.item(parseInt(iIndex) - 1).value;
			this.sortPan.item(parseInt(iIndex)-1).value = curValue;
		
			this.sortPan.item(parseInt(iIndex)-1).selected = true;
		}
		catch(e)
		{
			return;
		}
	}

	//+--------------------------------------------------------
	//| 方法：移动到第一位
	//+--------------------------------------------------------
	this.fnFirst = function ()
	{
		try
		{
			var iIndex = this.sortPan.selectedIndex;
			 
			if(iIndex == 0)
			{
				return;
			}
			
			while (iIndex>0)
			{	
				var curName = this.sortPan.item(iIndex).text;
				
				var ilength,iplace
			ilength=curName.length;
			iplace=curName.indexOf(".");
			var strNameState,strNameSpace
			strNameState=curName.substr(iplace+1,ilength)
			strNameSpace=curName.substr(0,iplace+1)
			var strNameStateMiddle
			strNameStateMiddle=strNameState
			
			var curName1 = this.sortPan.item(iIndex-1).text;
			var ilength1,iplace1
			ilength1=curName1.length;
			iplace1=curName1.indexOf(".");
			var strNameState1,strNameSpace1
			strNameState1=curName1.substr(iplace1+1,ilength1)
			strNameSpace1=curName1.substr(0,iplace1+1)
			strNameState=strNameState1
			strNameState1=strNameStateMiddle
			this.sortPan.item(iIndex).text =strNameSpace+strNameState
			 this.sortPan.item(parseInt(iIndex) - 1).text=strNameSpace1+strNameState1
				var curValue = this.sortPan.item(iIndex).value;
			
				this.sortPan.item(iIndex).value = this.sortPan.item(parseInt(iIndex) - 1).value;
				this.sortPan.item(parseInt(iIndex)-1).value = curValue;	
				this.sortPan.item(parseInt(iIndex)-1).selected = true;	
				iIndex=iIndex-1
			
			}
		}
		catch(e)
		{
			return;
		}
	}
	//+--------------------------------------------------------
	//| 方法：下移一位
	//+--------------------------------------------------------
	this.sortDown = function ()
	{
		try
		{
			var iIndex = this.sortPan.selectedIndex;	
			if(iIndex == this.sortPan.length - 1)
			{
				return;
			}
			var curName = this.sortPan.item(iIndex).text;
			var ilength,iplace
			ilength=curName.length;
			iplace=curName.indexOf(".");
			var strNameState,strNameSpace
			strNameState=curName.substr(iplace+1,ilength)
			strNameSpace=curName.substr(0,iplace+1)
			var strNameStateMiddle
			strNameStateMiddle=strNameState
			
			var curName1 = this.sortPan.item(iIndex+1).text;
			var ilength1,iplace1
			ilength1=curName1.length;
			iplace1=curName1.indexOf(".");
			var strNameState1,strNameSpace1
			strNameState1=curName1.substr(iplace1+1,ilength1)
			strNameSpace1=curName1.substr(0,iplace1+1)
			strNameState=strNameState1
			strNameState1=strNameStateMiddle
			this.sortPan.item(iIndex).text =strNameSpace+strNameState
			 this.sortPan.item(parseInt(iIndex) + 1).text=strNameSpace1+strNameState1

			var curValue = this.sortPan.item(iIndex).value;
			this.sortPan.item(iIndex).value = this.sortPan.item(parseInt(iIndex) + 1).value;
			this.sortPan.item(parseInt(iIndex)+1).value = curValue;
		
			this.sortPan.item(parseInt(iIndex) + 1).selected = true;
		}
		catch(e)	
		{
			return;
		}
		
	}
	//+--------------------------------------------------------
	//| 方法：移动到最后
	//+--------------------------------------------------------
	this.fnEnd = function ()
	{
		try
		{
			var iIndex = this.sortPan.selectedIndex;	
			if(iIndex == this.sortPan.length - 1)
			{
				return;
			}
			while (iIndex<(this.sortPan.length - 1))
			{
				var curName = this.sortPan.item(iIndex).text;
				var ilength,iplace
			ilength=curName.length;
			iplace=curName.indexOf(".");
			var strNameState,strNameSpace
			strNameState=curName.substr(iplace+1,ilength)
			strNameSpace=curName.substr(0,iplace+1)
			var strNameStateMiddle
			strNameStateMiddle=strNameState
			
			var curName1 = this.sortPan.item(iIndex+1).text;
			var ilength1,iplace1
			ilength1=curName1.length;
			iplace1=curName1.indexOf(".");
			var strNameState1,strNameSpace1
			strNameState1=curName1.substr(iplace1+1,ilength1)
			strNameSpace1=curName1.substr(0,iplace1+1)
			strNameState=strNameState1
			strNameState1=strNameStateMiddle
			this.sortPan.item(iIndex).text =strNameSpace+strNameState
			 this.sortPan.item(parseInt(iIndex) + 1).text=strNameSpace1+strNameState1
		
				var curValue = this.sortPan.item(iIndex).value;
				this.sortPan.item(iIndex).value = this.sortPan.item(parseInt(iIndex) + 1).value;
				this.sortPan.item(parseInt(iIndex)+1).value = curValue;
		
				this.sortPan.item(parseInt(iIndex) + 1).selected = true;
				iIndex=iIndex+1
			}
		}
		catch(e)	
		{
			return;
		}

	}
	//+--------------------------------------------------------
	//| 方法：排序保存
	//+--------------------------------------------------------
	this.ok = function ()
	{
		var str='';
		var iplace;
		for (i = 0; i <= this.sortPan.options.length-1; i++) { 
			iplace = this.sortPan.options[i].text.indexOf(".");
			str += this.sortPan.options[i].value + ":" + this.sortPan.options[i].text.substr(0,iplace) + ",";
		}
		this.FormObj.seqNoList.value = str.substr(0,str.length-1);
	}

	//+--------------------------------------------------------
	//| 结束
	//+--------------------------------------------------------

}