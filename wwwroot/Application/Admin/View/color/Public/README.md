<!DOCTYPE html>
<html>
<head>
  <include file="Public/head"/>
</head>

<body>
  <div id="wrapper">
	<include file="Public/header" /> 
	<include file="Public/menu" />
	<include file="Public/content"/>	
  </div>  
  
  <include file="Public/footer"/>  
  <include file="Public/script"/>
</body>

</html

文件包含关系
base						定义了html结构
	head					包含了html节点中的head部分，包含CSS
	
	header					顶部导航条，用户栏等固定部分	
	menu					左边导航栏(sidemenu)
	content					右边主体内容
	footer					下半版权信息等
	
	script
			ver				think用的公共JS，定义路径，URL
			navjs			导航条控制(不用了)


