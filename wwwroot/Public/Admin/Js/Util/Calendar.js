/*=======Calendar.js=======By Jiang Hongbin=======*/

var months = new Array("1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"); 
var daysInMonth = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
var days = new Array("日","一", "二", "三", "四", "五", "六"); 
var today; 

document.writeln("<div id='Calendar' onselectstart='return false;' style='position:absolute; z-index:1; visibility: hidden; 	border: 2px groove #d4d4d4;'></div>");

function getDays(month, year)
{ 
	//下面的这段代码是判断当前是否是闰年的 
	if (1 == month) 
		return ((0 == year % 4) && (0 != (year % 100))) || (0 == year % 400) ? 29 : 28; 
	else 
		return daysInMonth[month]; 
} 

function getToday() 
{ 
	//得到今天的年,月,日 
	this.now = new Date(); 
	this.year = this.now.getFullYear(); 
	this.month = this.now.getMonth(); 
	this.day = this.now.getDate(); 
}

function getStringDay(str) 
{ 
	//得到输入框的年,月,日
	var str=str.split("-")
	
	this.now = new Date(parseFloat(str[0]),parseFloat(str[1])-1,parseFloat(str[2])); 
	this.year = this.now.getFullYear(); 
	this.month = this.now.getMonth(); 
	this.day = this.now.getDate(); 
}

function newCalendar() { 
	var parseYear = parseInt(document.getElementById('Year').options[document.getElementById('Year').selectedIndex].value); 
	var newCal = new Date(parseYear, document.getElementById('Month').selectedIndex, 1); 
	var day = -1; 
	var startDay = newCal.getDay(); 
	var daily = 0; 
	
	if ((today.year == newCal.getFullYear()) &&(today.month == newCal.getMonth())) 
		day = today.day; 
		
	var tableCal = document.getElementById('Calendar'); 
	var intDaysInMonth =getDays(newCal.getMonth(), newCal.getFullYear());
	 
	for (var intWeek = 1; intWeek < tableCal.rows.length;intWeek++) 
		for (var intDay = 0;intDay < tableCal.rows[intWeek].cells.length;intDay++) 
		{ 
			var cell = tableCal.rows[intWeek].cells[intDay]; 
			if ((intDay == startDay) && (0 == daily)) 
				daily = 1; 
				
			if(day==daily) //今天，调用今天的Class 
			{
				cell.style.background='#ffffff';
				cell.style.color='#000000';
				//cell.style.fontWeight='bold';
			}
			else if(intDay==6) //周六 
				cell.style.color='red'; 
			else if (intDay==0) //周日 
				cell.style.color='red';
			
			if ((daily > 0) && (daily <= intDaysInMonth)) 
			{ 
				cell.innerText = daily; 
				daily++; 
			} 
			else 
				cell.innerText = ""; 
		} 
} 

function GetDate(InputBox)
{ 
	var sDate; 
	//这段代码处理鼠标点击的情况 
	if (event.srcElement.tagName == "TD") 
		if (event.srcElement.innerText != "") 
		{ 
			sDate = document.getElementById('Year').value + "-" + document.getElementById('Month').value + "-" + event.srcElement.innerText;
			eval("document.getElementById('Calendar')."+InputBox).value=sDate;
			HiddenCalendar();
		} 
} 

function dsMove(obj)  //实现层的拖移
{
  if(event.button==1)
  {
    var X=obj.clientLeft;
    var Y=obj.clientTop;
    obj.style.pixelLeft=X+(event.x-DS_x);
    obj.style.pixelTop=Y+(event.y-DS_y);
  }
}
function HiddenCalendar()
{
	//关闭选择窗口
	document.getElementById('Calendar').style.visibility='hidden';
}

function ShowCalendar(InputBox)
{
	var x,y,intLoop,intWeeks,intDays;
	var DivContent;
	var year,month,day;
	//var o=document.getElementById(InputBox);
	var o=InputBox;
	var oid=o.id;
	var thisyear; //真正的今年年份
	
	if(!oid)oid=o.name;
	
	thisyear=new getToday();
	thisyear=thisyear.year;
	
	today = o.value;
	if(isDate(today))
		today = new getStringDay(today);
	else
		today = new getToday(); 
	
	//显示的位置
	x=o.offsetLeft;
	y=o.offsetTop;
	while(o=o.offsetParent)
	{
		x+=o.offsetLeft;
		y+=o.offsetTop;
	}
	document.getElementById('Calendar').style.left=x+2;
	document.getElementById('Calendar').style.top=y+20;
	document.getElementById('Calendar').style.visibility="visible";
	
	//下面开始输出日历表格(border-color:#9DBAF7)
	DivContent="<table border='0' cellspacing='1' cellpadding='0' class='Calendar' id='caltable' onmousedown='DS_x=event.x-parentNode.style.pixelLeft;DS_y=event.y-parentNode.style.pixelTop;setCapture();' onmouseup='releaseCapture();' onmousemove='dsMove(this.parentNode)' style='cursor:move;'>";
	DivContent+="<tr>";
	DivContent+="<td height='25'>";
	
	//年
	DivContent+="&nbsp;&nbsp;<select name='Year' id='Year' onChange='newCalendar()' style='font-family:Verdana; font-size:12px; width:60'>";
	for (intLoop = thisyear - 10; intLoop < (thisyear + 10); intLoop++) 
		DivContent+="<option value= " + intLoop + " " + (today.year == intLoop ? "Selected" : "") + ">" + intLoop + "</option>"; 
	DivContent+="</select>&nbsp;年&nbsp;";
	
	//月
	DivContent+="<select name='Month' id='Month' onChange='newCalendar()' style='font-family:Verdana; font-size:12px; width:40'>";
	for (intLoop = 0; intLoop < months.length; intLoop++) 
		DivContent+="<option value= " + (intLoop + 1) + " " + (today.month == intLoop ? "Selected" : "") + ">" + months[intLoop] + "</option>"; 
	DivContent+="</select>&nbsp;月";
	
	DivContent+="</td>";
	
	DivContent+="<td style='border-bottom:0px solid #0066FF; background-color:#C0D0E8; font-family:Wingdings 2,Wingdings,Webdings; font-size:16px; padding-top:2px; color:#FFFFFF; cursor:hand' align='center' title='关闭' onClick='javascript:HiddenCalendar()'>S</td>";
	DivContent+="</tr>";
	 
	DivContent+="<tr><td align='center' colspan='2'>";
	DivContent+="<table id='calendar' border='0' cellspacing='1' cellpadding='0' width='100%'>";
	
	//星期
DivContent += ' <tr align="center" valign="middle" > ';
DivContent += '<TD class=DaySunTitle id=diary >' + days[0] + '</TD>'; 
for (var intLoop = 1; intLoop < days.length-1;intLoop++) 
	DivContent += '<TD class=DayTitle id=diary>' + days[intLoop] + '</TD>'; 
DivContent += '<TD class=DaySatTitle id=diary>' + days[intLoop] + '</TD>'; 
DivContent += ' </TR>';

	
	//天
	for (intWeeks = 0; intWeeks < 6; intWeeks++)
	{ 
		DivContent+="<tr>"; 
		for (intDays = 0; intDays < days.length; intDays++) 
			DivContent+="<td onClick='GetDate(\"" + oid + "\")' class=Day onMouseover='buttonOver();' onMouseOut='buttonOut();' ></td>"; 
		DivContent+="</tr>"; 
	} 
	DivContent+="</table></td></tr></table>";
	document.getElementById('Calendar').innerHTML=DivContent;
	newCalendar();
}

function isDate(dateStr)
{ 
	var datePat = /^(\d{4})(\-)(\d{1,2})(\-)(\d{1,2})$/;
	var matchArray = dateStr.match(datePat);
	if (matchArray == null) return false; 
	var month = matchArray[3];
	var day = matchArray[5]; 
	var year = matchArray[1]; 
	if (month < 1 || month > 12) return false; 
	if (day < 1 || day > 31) return false; 
	if ((month==4 || month==6 || month==9 || month==11) && day==31) return false; 
	if (month == 2)
	{
		var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0)); 
		if (day > 29 || (day==29 && !isleap)) return false; 
	} 
	return true;
}

function buttonOver()
{
 var obj = window.event.srcElement;
 obj.runtimeStyle.cssText = "background-color:#FFFFa7";
// obj.className="Hover";
}

function buttonOut()
{
 var obj = window.event.srcElement;
 window.setTimeout(function(){obj.runtimeStyle.cssText = "";},300);
}

function wanwan(tableid){
var tableid=document.getElementById(tableid);
tableid.width=tableid.width=='80%'?'100%':'80%';
}
