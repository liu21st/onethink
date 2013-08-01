function CheckAll(strSection)
	{
		var i;
		var	colInputs = document.getElementById(strSection).getElementsByTagName("input");
		for	(i=1; i < colInputs.length; i++)
		{
			colInputs[i].checked=colInputs[0].checked;
		}
	}
function add(){
 location.href  = URL+"/add/";
}
function showHideSearch(){
	if (document.getElementById('searchM').style.display=='inline')
	{
		document.getElementById('searchM').style.display='none';
		document.getElementById('showText').value ='高级';
		document.getElementById('key').style.display='inline';
	}else {
		document.getElementById('searchM').style.display='inline';
		document.getElementById('showText').value ='隐藏';
		document.getElementById('key').style.display='none';

	}
}

function recommend(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert('请选择推荐项！');
		return false;
	}

	location.href = URL+"/recommend/id/"+keyValue;

}

function sortBy (field,sort){
	location.href = URL+"/index/order/"+field+"/sort/"+sort;
}

function forbid(id){
	location.href = URL+"/forbid/id/"+id;
}

function resume(id){
	location.href = URL+"/resume/id/"+id;
}
function output(){
	location.href = URL+"/output/";
}
function member(id){
	location.href = URL+"/../member/edit/id/"+id;
}
function chat(id){
	location.href = URL+"/../chat/index/girlId/"+id;
}
function login(id){
	location.href = URL+"/../login/index/id/"+id;
}
function child(id){
	location.href = URL+"/index/parentId/"+id;
}
function action(id){
	location.href = URL+"/action/id/"+id;
}

function module(id){
	location.href = URL+"/module/id/"+id;
}

function user(id){
	location.href = URL+"/user/id/"+id;
}

	//+---------------------------------------------------
	//|	打开模式窗口，返回新窗口的操作值
	//+---------------------------------------------------
	function PopModalWindow(url,width,height)
	{
		var result=window.showModalDialog(url,"win","dialogWidth:"+width+"px;dialogHeight:"+height+"px;center:yes;status:no;scroll:no;dialogHide:no;resizable:no;help:no;edge:sunken;");
		return result;
	}

function read(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert('请选择编辑项！');
		return false;
	}
	location.href =  URL+"/read/id/"+keyValue;
}

function edit(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValue();
	}
	if (!keyValue)
	{
		alert('请选择编辑项！');
		return false;
	}
	location.href =  URL+"/edit/id/"+keyValue;
}

function del(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert('请选择删除项！');
		return false;
	}

	if (window.confirm('确实要删除选择项吗？'))
	{
	location.href = URL+"/delete/id/"+keyValue;
	}
}

function takeback(id){
	var keyValue;
	if (id)
	{
		keyValue = id;
	}else {
		keyValue = getSelectCheckboxValues();
	}
	if (!keyValue)
	{
		alert('请选择回收项！');
		return false;
	}

	if (window.confirm('确实要回收选择项吗？'))
	{
	location.href = URL+"/takeback/id/"+keyValue;
	}
}


function getSelectCheckboxValue(){
	var obj = document.getElementsByName('key');
	var result ='';
	for (var i=0;i<obj.length;i++)
	{
		if (obj[i].checked==true)
				return obj[i].value;

	}
	return false;
}


function getSelectCheckboxValues(){
	var obj = document.getElementsByName('key');
	var result ='';
	for (var i=0;i<obj.length;i++)
	{
		if (obj[i].checked==true)
				result += obj[i].value+",";

	}
	return result.substring(0, result.length-1);
}


 function   change()   
  {   
	  var   oObj   =   event.srcElement;   
	  if(oObj.tagName.toLowerCase()   ==   "td")   
	  {   
	  var   oTable   =   oObj.parentNode.parentNode;   
	  for(var   i=1;   i<oTable.rows.length;   i++)   
	  {   
	  oTable.rows[i].className   =   "out";   
	  oTable.rows[i].tag   =   false;   
	  }   

	  var   oTr   =   oObj.parentNode; 
	  oTr.className   =   'down';   
	  oTr.tag   =   true;   

	  }   
  }   
    
  function   out()   
  {   
  var   oObj   =   event.srcElement;   
  if(oObj.tagName.toLowerCase()   ==   "td")   
  {   
  var   oTr   =   oObj.parentNode;   
  if(!oTr.tag)   
  oTr.className   =   "out";   
  }   
  }   
    
  function   over()   
  {   
  var   oObj   =   event.srcElement;   
  if(oObj.tagName.toLowerCase()   ==   "td")   
  {   
  var   oTr   =   oObj.parentNode;   
  if(!oTr.tag)   
  oTr.className   =   "over";   
  }   
  }   


//---------------------------------------------------------------------
// 多选改进方法 by Liu21st at 2005-11-29
// 
//
//-------------------------begin---------------------------------------

function searchItem(item){
	for(i=0;i<selectSource.length;i++)
		if (selectSource[i].text.indexOf(item)!=-1)
		{selectSource[i].selected = true;break;}
}

function addItem(){
	for(i=0;i<selectSource.length;i++)
		if(selectSource[i].selected){
			selectTarget.add( new Option(selectSource[i].text,selectSource[i].value));
			}
		for(i=0;i<selectTarget.length;i++)
			for(j=0;j<selectSource.length;j++)
				if(selectSource[j].text==selectTarget[i].text)
					selectSource[j]=null;
}

function delItem(){
	for(i=0;i<selectTarget.length;i++)
		if(selectTarget[i].selected){
		selectSource.add(new Option(selectTarget[i].text,selectTarget[i].value));
		
		}
		for(i=0;i<selectSource.length;i++)
			for(j=0;j<selectTarget.length;j++)
			if(selectTarget[j].text==selectSource[i].text) selectTarget[j]=null;
}

function delAllItem(){
	for(i=0;i<selectTarget.length;i++){
		selectSource.add(new Option(selectTarget[i].text,selectTarget[i].value));
		
	}
	selectTarget.length=0;
}
function addAllItem(){
	for(i=0;i<selectSource.length;i++){
		selectTarget.add(new Option(selectSource[i].text,selectSource[i].value));
		
	}
	selectSource.length=0;
}

function getReturnValue(){
	for(i=0;i<selectTarget.length;i++){
		selectTarget[i].selected = true;
	}
}

