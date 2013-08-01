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
//+-------------------------------------------------------
//|	指定页面区域内容导入Excel，包含页面样式
//+-------------------------------------------------------
 function CopyToExcel(tableId) 
 {
  var oXL = new ActiveXObject("Excel.Application"); 
  var oWB = oXL.Workbooks.Add(); 
  var oSheet = oWB.ActiveSheet;  
  var sel=document.body.createTextRange();
  sel.moveToElementText($(tableId));
  sel.select();
  sel.execCommand("Copy");
  oSheet.Paste();
  oXL.Visible = true;
 }

 function WriteToWord(tableId) 
 {
 	try {
       var oWD = new ActiveXObject("Word.Application"); 
     }
    catch(e) {
         alert("要使用该功能，必须安装Word，\n\r并且浏览器须允许执行ActiveX 控件。\n\r或者设置当前站点到信任站点！");
         return "";
     }
  oWD.DisplayAlerts = false;
  var oDC = oWD.Documents.Add("",0,1);
 var oRange =oDC.Range(0,1);
  var sel=document.body.createTextRange();
  sel.moveToElementText($(tableId));
  sel.select();
  sel.execCommand("Copy");
  oRange.Paste();
  oWD.Visible = true;
 }
//+-------------------------------------------------------
//|	指定页面区域“单元格”内容导入Excel,不包含页面样式
//+-------------------------------------------------------
 function WriteToExcel(tableId) 
 {
 	try {
       var oXL = new ActiveXObject("Excel.Application"); 
     }
    catch(e) {
         alert("要使用导出功能，必须安装Excel，\n\r并且浏览器须允许执行ActiveX 控件。\n\r或者设置当前站点到信任站点！");
         return "";
     }
  var oWB = oXL.Workbooks.Add(); 
  var oSheet = oWB.ActiveSheet; 
  oXL.DisplayAlerts = false;
  var obj = $(tableId);
  var Lenr = obj.rows.length;
  for (i=1;i<Lenr;i++) 
  { 
	   var Lenc = obj.rows(i).cells.length; 
	   for (j=0;j<Lenc;j++) 
	   { 
		oSheet.Cells(i+1,j+1).value = obj.rows(i).cells(j).innerText; 
	   } 
  } 
  oXL.Visible = true; 
 }


function getTableData(tableId,format)
 { 
 var a = $(tableId).getElementsByTagName('tr');
 var tdData = Array(a.length);
 for (i=0;i<a.length;i++){ 
   var b = a[i].getElementsByTagName('td');
   tdData[i] = Array(b.length);
  for (j=0;j<b.length;j++){ 
	  if (format == true)
	  {
		  tdData[i][j] = b[j].innerHTML;
	  }else {
		tdData[i][j] = b[j].innerText;
	  }
    
   }
 }
return tdData;
 } 