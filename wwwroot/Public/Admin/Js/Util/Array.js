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
 * 数组扩展类
 +------------------------------------------------------------------------------
 * @package    Util
 * @link       http://www.fcs.org.cn
 * @copyright  Copyright (c) 2005-2006 liu21st.com.  All rights reserved. 
 * @author     liu21st <liu21st@gmail.com>
 * @version    $Id$
 +------------------------------------------------------------------------------
 */
	
function Array.prototype.Left(length)
{
	return this.slice(0,length);
}

function Array.prototype.Mid(start,length)
{
	return this.slice(start,start+length);
}

function Array.prototype.Right(length)
{
	if(length>=this.length)return this.concat();
	return this.slice(this.length-length,this.length);
}

function Array.prototype.IndexOf(obj,start)
{
	start=Number.Convert(start);
	var l=this.length;
	for(var i=start;i<l;i++)
	{
		if(this[i]===obj)return i
	}
	return -1;
}

function Array.prototype.LastIndexOf(obj)
{
	var l=this.length;
	for(var i=l-1;i>=0;i--)
	{
		if(this[i]===obj)return i
	}
	return -1;
}

function Array.prototype.Item(index)
{
	return this[index];
}

//JScript5.5
function Array.prototype.RemoveItem(index)
{
	this.splice(index,1);
}
//5.5
function Array.prototype.RemoveLeft(count)
{
	this.splice(0,count);
}
//5.5
function Array.prototype.RemoveRight(count)
{
	var start=this.length-count;
	var length=count;
	if(start<0)
	{
		start=0;
		length=this.length;
	}
	this.splice(start,length)
}

//一维数组的排序
function Array.prototype.SortBy(type,str)
{
	//type 0 字母顺序（默认） 1 大小 2 拼音 3 乱序 4 带搜索
	switch (type)
	{
	case 0:this.sort();break;
	case 1:this.sort(function(a,b){return a-b;});break;
	case 2:this.sort(function(a,b){return a.localeCompare(b)});break;
	case 3:this.sort(function(){return Math.random()>0.5?-1:1;});break;
	case 4:this.sort(function(a,b){return a.indexOf(str)==-1?1:-1;});break;
	default:
		this.sort();
	}
}
 
function in_array(stringToSearch, arrayToSearch) {
        for (s = 0; s < arrayToSearch.length; s++) {
                thisEntry = arrayToSearch[s].toString();
                if (thisEntry == stringToSearch) {
                        return true;
                }
        }
        return false;
}
Array.prototype.indexOf=function(substr,start){
	var ta,rt,d='\0';
	if(start!=null){ta=this.slice(start);rt=start;}else{ta=this;rt=0;}
	var str=d+ta.join(d)+d,t=str.indexOf(d+substr+d);
	if(t==-1)return -1;rt+=str.slice(0,t).replace(/[^\0]/g,'').length;
	return rt;
}

Array.prototype.lastIndexOf=function(substr,start){
	var ta,rt,d='\0';
	if(start!=null){ta=this.slice(start);rt=start;}else{ta=this;rt=0;}
	ta=ta.reverse();var str=d+ta.join(d)+d,t=str.indexOf(d+substr+d);
	if(t==-1)return -1;rt+=str.slice(t).replace(/[^\0]/g,'').length-2;
	return rt;
}

Array.prototype.replace=function(reg,rpby){
	var ta=this.slice(0),d='\0';
	var str=ta.join(d);str=str.replace(reg,rpby);
	return str.split(d);
}

Array.prototype.search=function(reg){
	var ta=this.slice(0),d='\0',str=d+ta.join(d)+d,regstr=reg.toString();
	reg=new RegExp(regstr.replace(/\/((.|\n)+)\/.*/g,'\\0$1\\0'),regstr.slice(regstr.lastIndexOf('/')+1));
	t=str.search(reg);if(t==-1)return -1;return str.slice(0,t).replace(/[^\0]/g,'').length;
}