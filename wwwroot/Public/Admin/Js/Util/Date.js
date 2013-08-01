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
 * 日期扩展操作类
 +------------------------------------------------------------------------------
 * @package    Util
 * @link       http://www.fcs.org.cn
 * @copyright  Copyright (c) 2005-2006 liu21st.com.  All rights reserved. 
 * @author     liu21st <liu21st@gmail.com>
 * @version    $Id$
 +------------------------------------------------------------------------------
 */
	
//---------------------------------------------------
//	判断闰年
//---------------------------------------------------
Date.prototype.isLeapYear = function() 
{ 
return (0==this.getYear()%4&&((this.getYear()%100!=0)||(this.getYear()%400==0))); 
} 
function Date.isLeapYear(date)	{	return Date.ToDate(date).isLeapYear();	}

//---------------------------------------------------
//	日期格式化
//	格式 YYYY/yyyy/YY/yy 表示年份
//	MM/M 月份
//	W/w 星期
//	dd/DD/d/D 日期
//	hh/HH/h/H 时间
//	mm/m	分钟
//	ss/SS/s/S 秒
//	gz/GZ	干支
//	XZ/xz	星座
//	sx/SX	生肖
//---------------------------------------------------
Date.prototype.Format = function(formatStr) 
{ 
var str = formatStr; 
var Week = ["日","一","二","三","四","五","六"];
//干支 生肖 星座
str=str.replace(/gz|GZ/,this.MagicInfo('GZ')); 
str=str.replace(/XZ|xz/,this.MagicInfo('XZ'));
str=str.replace(/sx|SX/,this.MagicInfo('SX')); 
//所在月的最大天数
str=str.replace(/md|MD/,this.MaxDayOfDate()); 
//所在年的最几周
str=str.replace(/ww|WW/,this.WeekNumOfYear()); 

//年份
str=str.replace(/yyyy|YYYY/,this.getFullYear()); 
str=str.replace(/yy|YY/,(this.getYear() % 100)>9?(this.getYear() % 100).toString():"0" + (this.getYear() % 100)); 
//月
str=str.replace(/MM/,this.getMonth()>9?this.getMonth().toString():"0" + this.getMonth()); 
str=str.replace(/M/g,this.getMonth()); 
//星期
str=str.replace(/w|W/g,Week[this.getDay()]); 
//日
str=str.replace(/dd|DD/,this.getDate()>9?this.getDate().toString():"0" + this.getDate()); 
str=str.replace(/d|D/g,this.getDate()); 
//时
str=str.replace(/hh|HH/,this.getHours()>9?this.getHours().toString():"0" + this.getHours()); 
str=str.replace(/h|H/g,this.getHours()); 
//分
str=str.replace(/mm/,this.getMinutes()>9?this.getMinutes().toString():"0" + this.getMinutes()); 
str=str.replace(/m/g,this.getMinutes()); 
//秒
str=str.replace(/ss|SS/,this.getSeconds()>9?this.getSeconds().toString():"0" + this.getSeconds()); 
str=str.replace(/s|S/g,this.getSeconds()); 

return str; 
} 
function Date.Format(date,formatStr)	{ return Date.ToDate(date).Format(formatStr); }

//+---------------------------------------------------
//|	日期计算
//+---------------------------------------------------
Date.prototype.DateAdd = function(strInterval, Number) {
    var dtTmp = this;
    switch (strInterval) {
        case "ms":return new Date(Date.parse(dtTmp) + Number);
        case "s":return new Date(Date.parse(dtTmp) + (1000 * Number));
        case "n":return new Date(Date.parse(dtTmp) + (60000 * Number));
        case "h":return new Date(Date.parse(dtTmp) + (3600000 * Number));
        case "d":return new Date(Date.parse(dtTmp) + (86400000 * Number));
        case "w":return new Date(Date.parse(dtTmp) + ((86400000 * 7) * Number));
        case "q":return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number*3, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
        case "m":return new Date(dtTmp.getFullYear(), (dtTmp.getMonth()) + Number, dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
        case "y":return new Date((dtTmp.getFullYear() + Number), dtTmp.getMonth(), dtTmp.getDate(), dtTmp.getHours(), dtTmp.getMinutes(), dtTmp.getSeconds());
    }
}
function Date.DateAdd(date,strInterval, Number){ return Date.ToDate(date).DateAdd(strInterval,Number);}

//+---------------------------------------------------
//|	比较日期差 dtEnd 格式为日期型或者 有效日期格式字符串
//+---------------------------------------------------
Date.prototype.DateDiff = function(strInterval, dtEnd) {
    var dtStart = this;
    if (typeof dtEnd == 'string')//如果是字符串转换为日期型
    {
        dtEnd = StringToDate(dtEnd);
    }
    switch (strInterval) {
        case "ms":return parseInt((dtEnd - dtStart));
        case "s":return parseInt((dtEnd - dtStart) / 1000);
        case "n":return parseInt((dtEnd - dtStart) / 60000);
        case "h":return parseInt((dtEnd - dtStart) / 3600000);
        case "d":return parseInt((dtEnd - dtStart) / 86400000);
        case "w":return parseInt((dtEnd - dtStart) / (86400000 * 7));
        case "m":return (dtEnd.getMonth()+1)+((dtEnd.getFullYear()-dtStart.getFullYear())*12) - (dtStart.getMonth()+1);
        case "y":return dtEnd.getFullYear() - dtStart.getFullYear();
    }
}
function Date.DateDiff(date1,date2,strInterval){return Date.ToDate(date1).DateDiff(strInterval,date2);}

//+---------------------------------------------------
//|	日期数字转中文
//+---------------------------------------------------
function  numberToCh(numberStr){  
var  numberstring="一二三四五六七八九十";  
var str = '';
   if(numberStr  ==0)  {str += "十"}  
   if(numberStr  <  10){  
       str += numberstring.substring(0+(numberStr-1),numberStr) 
    }  
   else  if(numberStr  <  20  ){  
       str += "十"+numberstring.substring(0+(numberStr-11),(numberStr-10))
    }  
   else  if(numberStr  <  30  ){  
       str += "二十"+numberstring.substring(0+(numberStr-21),(numberStr-20))
    }  
   else{  
       str += "三十"+numberstring.substring(0+(numberStr-31),(numberStr-30))
    }  
return str;
} 


//+---------------------------------------------------
//|	年份转中文
//+---------------------------------------------------	   
function  yearToCh( yearStr ,flag ){  
var numberstring="零一二三四五六七八九";  
var str = flag?'公元':'';
var index = new  String(yearStr);  
for(var  i=0;i<4;i++){  
    str +=(numberstring.substr(index.substr(i,1),1)) ;
}  
return str;
}

//+---------------------------------------------------
//|	日期输出字符串，重载了系统的toString方法
//|	可以显示中文日期和星期
//+---------------------------------------------------
Date.prototype.toString = function( showWeek , chinese )
{
    var myDate= this;
    var str = '';
    if (chinese)
    {
        str += yearToCh(myDate.getFullYear())+'年';
        str += numberToCh(myDate.getMonth()+1)+'月';
        str += numberToCh(myDate.getDate())+'日';
    }else {
        str = myDate.toLocaleDateString();
        str += myDate.toLocaleTimeString();
    }

    if (showWeek)
    {
        var Week = ["日","一","二","三","四","五","六"];
        str += " 星期"	+	Week[myDate.getDay()];
    }
    return str;
}
function Date.toString(date,showWeek,chinese){return Date.ToDate(date).toString(showWeek,chinese);}

//+---------------------------------------------------
//|	判断日期 所属 干支 生肖 星座
//|	type 参数：XZ 星座 GZ 干支 SX 生肖
//+---------------------------------------------------
Date.prototype.MagicInfo = function( type )
{
    var myDate= this;
    var y = myDate.getFullYear();
    var m = myDate.getMonth()+1;
    var d = myDate.getDate();
    var result = '';
    switch (type)
    {
    case 'XZ'://星座
        var XZDict = '摩羯宝瓶双鱼白羊金牛双子巨蟹狮子处女天秤天蝎射手';
        var Zone = new Array(1222,122,222,321,421,522,622,722,822,922,1022,1122,1222);
        if((100*m+d)>=Zone[0]||(100*m+d)<Zone[1])
            var i=0;
        else
            for(var i=1;i<12;i++){
            if((100*m+d)>=Zone[i]&&(100*m+d)<Zone[i+1])
              break;
            }
        result = XZDict.substring(2*i,2*i+2)+'座';
        break;
    case 'GZ'://干支
        var GZDict = ['甲乙丙丁戊己庚辛壬癸','子丑寅卯辰巳午未申酉戌亥'];
        var i= y -1900+36 ;
        result = GZDict[0].charAt(i%10)+GZDict[1].charAt(i%12);
        break;
    case 'SX'://生肖
        var SXDict = '鼠牛虎兔龙蛇马羊猴鸡狗猪';
        result = SXDict.charAt((y-4)%12);
        break;
    
    }
    return result;
}
function Date.MagicInfo(date,type){ return Date.ToDate(date).MagicInfo(type);}

//+---------------------------------------------------
//|	获取日期时区位置 timezoneoffset
//+---------------------------------------------------
function Date.prototype.GetTZO()
{
var myDate = this;
return myDate.getTimezoneOffset();
}function Date.GetTZO(date){return Date.ToDate(date).GetTZO();}

//+---------------------------------------------------
//|	获取某个时区差的时间 
//+---------------------------------------------------
function Date.prototype.GetTZD(tzo)
{
if(typeof(tzo)=="undefined")tzo=this.GetTZO();
tzo = Number(tzo);
var myDate = this ;
myDate.setMinutes(myDate.getMinutes()+myDate.GetTZO()-tzo);
return myDate;
}function Date.GetTZD(date,tzo){return Date.ToDate(date).GetTZD(tzo);}

//+---------------------------------------------------
//|	日期合法性验证
//|	格式为：YYYY-MM-DD或YYYY/MM/DD
//+---------------------------------------------------
function  IsValidDate(DateStr)  
{                                          
   var  sDate=DateStr.replace(/(^\s+|\s+$)/g,"");  //去两边空格;  
   if(sDate=="")  return  true;  
   //如果格式满足YYYY-(/)MM-(/)DD或YYYY-(/)M-(/)DD或YYYY-(/)M-(/)D或YYYY-(/)MM-(/)D就替换为""  
　　　//数据库中，合法日期可以是:YYYY-MM/DD(2003-3/21),数据库会自动转换为YYYY-MM-DD格式  
   var  s  =  sDate.replace(/[\d]{4,4}[\-/]{1}[\d]{1,2}[\-/]{1}[\d]{1,2}/g,"");  
   if  (s=="")  //说明格式满足YYYY-MM-DD或YYYY-M-DD或YYYY-M-D或YYYY-MM-D  
   {  
       var  t=new  Date(sDate.replace(/\-/g,"/"));  
       var  ar  =  sDate.split(/[-/:]/);  
       if(ar[0] !=  t.getYear() || ar[1] != t.getMonth()+1  || ar[2] != t.getDate())  
           return  false;  
   }  
   else  
   {  
        return  false;  
   }  
   return  true;  
} 

//+---------------------------------------------------
//|	日期时间检查
//|	格式为：YYYY-MM-DD HH:MM:SS
//+---------------------------------------------------
function  CheckDateTime(str){                            
       var  reg  =  /^(\d+)-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{1,2}):(\d{1,2})$/;    
       var  r  =  str.match(reg);    
       if(r==null)return  false;    
       r[2]=r[2]-1;    
       var  d=  new  Date(r[1],r[2],r[3],r[4],r[5],r[6]);    
       if(d.getFullYear()!=r[1])return  false;    
       if(d.getMonth()!=r[2])return  false;    
       if(d.getDate()!=r[3])return  false;    
       if(d.getHours()!=r[4])return  false;    
       if(d.getMinutes()!=r[5])return  false;    
       if(d.getSeconds()!=r[6])return  false;    
       return  true;  
} 

//+---------------------------------------------------
//|	把日期分割成数组
//+---------------------------------------------------
Date.prototype.toArray = function()
{
    var myDate = this;
    var myArray = Array();
    myArray['y'] = myDate.getFullYear();
    myArray['m'] = myDate.getMonth();
    myArray['d'] = myDate.getDate();
    myArray['h'] = myDate.getHours();
    myArray['n'] = myDate.getMinutes();
    myArray['s'] = myDate.getSeconds();
    return myArray;
}
function Date.toArray(date){return Date.ToDate(date).toArray();}

//+---------------------------------------------------
//|	取得日期数据信息
//| 参数 interval 表示数据类型
//|	y 年 m月 d日 w星期 ww周 h时 n分 s秒
//|	GZ 干支 SX 生肖  XZ 星座 
//|	MD 月最大天数 
//+---------------------------------------------------
Date.prototype.DatePart = function(interval)
{
    var myDate = this;
    var partStr='';
    var Week = ["日","一","二","三","四","五","六"];
    switch (interval)
    {
        case 'y':partStr = myDate.getFullYear();break;
        case 'm':partStr = myDate.getMonth()+1;break;
        case 'd':partStr = myDate.getDate();break;
        case 'w':partStr = Week[myDate.getDay()];break;
        case 'ww':partStr = myDate.WeekNumOfYear();break;
        case 'h':partStr = myDate.getHours();break;
        case 'n':partStr = myDate.getMinutes();break;
        case 's':partStr = myDate.getSeconds();break;
        case 'GZ':partStr = myDate.MagicInfo('GZ');break;
        case 'SX':partStr = myDate.MagicInfo('SX');break;
        case 'XZ':partStr = myDate.MagicInfo('XZ');break;
        case 'MD':partStr = myDate.MaxDayOfDate();break;
    }
    return partStr;
}
function Date.DatePart(date,interval){return Date.ToDate(date).DatePart(interval);}

//+---------------------------------------------------
//|	取得当前日期所在月的最大天数
//+---------------------------------------------------
Date.prototype.MaxDayOfDate = function()
{
    var myDate = this;
    var ary = myDate.toArray();
    var date1 = (new Date(ary[0],ary[1],1));
    var date2 = date1.DateAdd("m",1);
    var result = date1.DateDiff('d',date2);
    return result;
}
function Date.MaxDayOfDate(date){return Date.ToDate(date).MaxDayOfDate();}

//+---------------------------------------------------
//|	取得当前日期所在周是一年中的第几周
//+---------------------------------------------------
Date.prototype.WeekNumOfYear = function(){
    var myDate = this;
    var ary = myDate.toArray();
    var year = ary[0];
    var month = ary[1]+1;
    var day	= ary[2];
    document.write('<script language=VBScript\> \n');
    document.write('myDate = DateValue("'+month+'-'+day+'-'+year+'") \n');
    document.write('result = DatePart("ww", myDate) \n');
    document.write('</SCRIPT\> \n');
    return result;
}
function Date.WeekNumOfYear(date){return Date.ToDate(date).WeekNumOfYear();}

//+---------------------------------------------------
//|	字符串转成日期类型 
//|	格式 MM/dd/YYYY MM-dd-YYYY YYYY/MM/dd YYYY-MM-dd YYYYMMdd
//+---------------------------------------------------
function StringToDate(DateStr)
{
    //MM/dd/YYYY MM-dd-YYYY YYYY/MM/dd
    var converted = Date.parse(DateStr);
    var myDate = new Date(converted);
    if (isNaN(myDate))
    {
        //YYYY-MM-dd类型转换
        if (DateStr.indexOf('-')!=-1)
        {
            var arys= DateStr.split('-');
            myDate = new Date(arys[0],--arys[1],arys[2]);
        }else if (DateStr.length>=8)
        {
            //YYYYMMdd格式的字串转换
            var year = DateStr.substring(0,4);
            var month = DateStr.substring(4,6);
            var day = DateStr.substring(6,8);
            myDate = new Date(year,--month,day);
        }
    }
    return myDate;
}

//+---------------------------------------------------
//|	自动判断转换成日期型
//+---------------------------------------------------
function Date.ToDate(date)
{
    if (typeof(date)=='string') date = StringToDate(date);
    return date;
}