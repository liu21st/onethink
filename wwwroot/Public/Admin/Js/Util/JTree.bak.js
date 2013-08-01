/*=============================================================================
程序名：JTree。
完成时间：2005/12/5
作者：xlinFairy
E-mail：1fairy1@163.com
	更新日期：2005/12/9
	更新功能：使之在opera下也能正常使用。
	特别感谢：
	Sheneyan，LeXRus (排名不分先后,^-^)
	
	更新日期：2006/01/06
	更新功能：加入treeNodes(数组形式)，通过treeNodes可以对指定的子树进行收缩和展开功能，查找指定节点的功能。
	加入这个功能之后，个人感觉有些混乱。不过，现在没有时间整理。待下一版吧
请保留作者信息
=============================================================================*/
function treeNode(){
	var self		=this;
	this.obj		=null;	//指caption所在的标签：<span>。。</span>
	this.caption	=null;	//显示的文字
	this.level		=null;	//节点的层次
	this.value		=null;	//这个值暂时没有用到。预感到会有用，因为做Delphi的树时，就因为缺少相关的东东，不得不用其它的办法来取代
	//----------------------------------
	this.treeNodes		=new Array();	//子树集合
	
	this.parentTreeNode	=null;			//当前“树枝”父树枝，就像树叶和树枝的关系一样。
	this.expand=function(pFlag){		//如果是树枝，就收缩或展开。要重定位一下要展开或收缩的对象。
		try{
			self.obj.parentNode.expand(pFlag);//pFlag只能为false或true
		}catch(e){};
	}
	this.click=function(){
		self.obj.onclick();
	}
}
function JTree(pParent,xmlFile,pExpand){
	this.PICPATH	=	"JTree/"	//图片文件所在的文件夹，可见public，可改变。
	
	var self		=this;	//相当于一个引用，指向自己。JTree.
	//-----------------------------------------------------------------------------
	//不可见private。
	//常量
	var JOIN		=	this.PICPATH +	"join.gif";
	var JOINBOTTOM	=	this.PICPATH +	"joinBottom.gif";
	var MINUS		=	this.PICPATH +	"minus.gif";
	var MINUSBOTTOM	=	this.PICPATH +	"minusBottom.gif";
	var PLUS		=	this.PICPATH +	"plus.gif";
	var PLUSBOTTOM	=	this.PICPATH +	"plusBottom.gif";
	var EMPTY		=	this.PICPATH +	"empty.gif";
	var LINE		=	this.PICPATH +	"line.gif";
	var ROOTICON	=	this.PICPATH +	"cd.gif";
	var LEAFICON	=	this.PICPATH +	"page.gif";
	var NODEICON	=	this.PICPATH + 	"folder.gif";
	
	var OPEN		=new Array();
		OPEN[true]	=MINUS;
		OPEN[false]	=PLUS;
	
	var OPENBOTTOM			=new Array();
		OPENBOTTOM[true]	=MINUSBOTTOM;
		OPENBOTTOM[false]	=PLUSBOTTOM;
	
	this.setPicPath=function(pPath){
		self.PICPATH=pPath;
		
		JOIN		=	self.PICPATH +	"join.gif";

		JOINBOTTOM	=	self.PICPATH +	"joinBottom.gif";
		MINUS		=	self.PICPATH +	"minus.gif";
		MINUSBOTTOM	=	self.PICPATH +	"minusBottom.gif";
		PLUS		=	self.PICPATH +	"plus.gif";
		PLUSBOTTOM	=	self.PICPATH +	"plusBottom.gif";
		EMPTY		=	self.PICPATH +	"empty.gif";
		LINE		=	self.PICPATH +	"line.gif";
			
		OPEN[true]	=MINUS;
		OPEN[false]	=PLUS;
		
		OPENBOTTOM[true]	=MINUSBOTTOM;
		OPENBOTTOM[false]	=PLUSBOTTOM;
		ROOTICON	=	self.PICPATH +	"base.gif";
		LEAFICON	=	self.PICPATH +	"page.gif";
		NODEICON	=	self.PICPATH + 	"folder.gif";
	}
	
	this.CAPTIONATT	=	"caption";//标题属性是哪一个属性
	this.ICONATT	=	"icon";//图标属性
	this.EXPANDALL	=	pExpand;//是否全部扩展。
	
	//this.clickItem=new treeNode;
	this.clickItem	=new treeNode;//用于点击时，返回值。
	this.selectNode	=null;//同上
	//----------------------------------------------------
	this.treeNodes	=new Array();//树节点的集合。
	this.treeNodes.push(null);
	this.root=this.treeNodes[0]=new treeNode;//树的根
	
	
	this.onclick=null;
	this.onmouseover=null;
	this.onmouseout=null;
	this.ondblclick=null;
	//-----------------------------------------------------------------------------
	//跟据ID得到OBJ
	var $=function(pObjID){
		return document.getElementById(pObjID)
	}			
	//-----------------------------------------------------------------------------
	this.body=$(pParent) || document.body;
	//-----------------------------------------------------------------------------
	//生XML对象。
	var createXMLDom=function(){
		if (window.ActiveXObject) 
			var xmldoc=new ActiveXObject("Microsoft.XMLDOM");
		else 
			if (document.implementation&&document.implementation.createDocument)
				var xmldoc=document.implementation.createDocument("","doc",null);
		xmldoc.async = false;
		//为了和FireFox一至，这里不能改为False;
		xmldoc.preserveWhiteSpace=true;
		return xmldoc;
	}
	
	var createXMLHttp=function(){
		var xmlHttp;
		if (window.ActiveXObject){
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}else{
			xmlHttp=new XMLHttpRequest();
		}
		return xmlHttp;
	}
	
	
	//-----------------------------------------------------------------------------
	//加载XML文件。
	var xmlDom=createXMLDom();
	try{
		xmlDom.load(xmlFile);
	}catch(e){
		var xmlHttp=createXMLHttp();
			xmlHttp.onreadystatechange = function(){
				if(xmlHttp.readyState == 4){
					xmlDom=xmlHttp.responseXML;
				}else{
					//window.state="XML文件加载中...";
				}
			}		
			xmlHttp.open("GET",xmlFile,false);
			xmlHttp.send(null);
	}
	//注：FF不支持xml
	//alert(xmlDom.documentElement.childNodes[1].xml);
	var DOMRoot=xmlDom.documentElement;
	//for(o in DOMRoot){//只能用在FireFox和opera下，不有用在IE下。
	//	document.write(o+" = " + DOMRoot.eval(o)+"<br>");
	//}
	//FF和IE都支持下面这个方法取属性的内容。
	//alert(root.attributes.getNamedItem("caption").nodeValue);
	//-----------------------------------------------------------------------------
	//取出指定节点的属性。
	var getDOMAtt=function(pNode,pAttribute){
		try{
			return pNode.attributes.getNamedItem(pAttribute).nodeValue;
		}catch(e){
			//alert("指定节点不存在，或指定属性："+pAttribute+" 不存在!")
			return false;
		}
		
	}
	//alert(getAttribute(root,"caption"));
	//-----------------------------------------------------------------------------
	//新建HTML标签。
	var createTag=function(pTagName){
		return document.createElement(pTagName)
	}
	var createImg=function(pSrc){
		var tmp=createTag("IMG");
		tmp.align	="absmiddle";
		tmp.src		=pSrc;
		tmp.onerror=function(){
			try{this.parentNode.removeChild(this);}catch(e){}
		}
		return tmp;
	}


	var createCaption=function(pNode,pLevel){
		var tmp=createTag("SPAN");
		tmp.innerHTML=getDOMAtt(pNode,self.CAPTIONATT);
		tmp.className="caption";
		tmp.onmouseover=function(){
			if(this.className!="captionHighLight")
				this.className="captionActive";
			try{self.onmouseover()}catch(e){}//必须加上
		}
		tmp.onmouseout=function(){
			if(this.className!="captionHighLight")
				this.className="caption";
			try{self.onmouseout()}catch(e){}//必须加上
		}
		tmp.ondblclick=function(){
			try{self.ondblclick()}catch(e){}//必须加上
		}
		tmp.onclick=function(){
			try{
				self.clickItem.obj.className="caption";
			}catch(e){
				//
			}
			this.className="captionHighLight";
			
			//alert(self);
			var clickItem=new treeNode;
			
			clickItem.obj		=tmp;
			clickItem.caption	=getDOMAtt(pNode,self.CAPTIONATT);
			clickItem.level		=pLevel
			
			try{
				clickItem.treeNodes		=this.parentNode.tree.treeNodes;
				clickItem.parentTreeNode=this.parentNode.tree.parentTreeNode;
			}catch(e){}
			
			self.clickItem=clickItem;
			self.selectNode=pNode;
			try{self.onclick();}catch(e){}//必须加上，如果self没有对onclick赋值的话，会引发错误。
		}
		return tmp;
	}

	var createTreeLine=function(pNode,pParentArea){
		var hasChildren=pNode.hasChildNodes();//是否有孩子。		
		for(var i=0;i<pParentArea.level;i++){
			var tmpArea=pParentArea;		
			for(var j=pParentArea.level;j>i;j--){
				//tmpArea=tmpArea.parentNode;
				tmpArea=tmpArea.parentNode.parentNode;
			}
			
			if(tmpArea.isLastChild)
				appendTo(createImg(EMPTY),pParentArea);
			else
				appendTo(createImg(LINE),pParentArea);
		}
				
		if(hasChildren){//有孩子
			var childShowBtn;
			if(!pParentArea.isLastChild){	
				childShowBtn=createImg(OPEN[true]);
				appendTo(childShowBtn,pParentArea);
			}else{
				childShowBtn=createImg(OPENBOTTOM[true]);
				appendTo(childShowBtn,pParentArea);
			}
			childShowBtn.onclick=function(){
				var isExpand=this.parentNode.expand();
								
				if(!pParentArea.isLastChild){
					this.src=OPEN[isExpand];
				}else{
					this.src=OPENBOTTOM[isExpand];
				}
				
			}
			pParentArea.expandBtn=childShowBtn;//新增的
		}else{//无孩子。
			if(!pParentArea.isLastChild)	
				appendTo(createImg(JOIN),pParentArea);
			else
				appendTo(createImg(JOINBOTTOM),pParentArea);			
		}
	}
	
	var createIcon=function(pNode,pParentArea){
		var hasChildren=pNode.hasChildNodes();//是否有孩子
		var tmpIcon=getDOMAtt(pNode,self.ICONATT);
		if(tmpIcon==false){
			if(hasChildren)
				appendTo(createImg(NODEICON),pParentArea);
			else
				appendTo(createImg(LEAFICON),pParentArea);
		}else{
			appendTo(createImg(ROOTICON),pParentArea);
		}		
	}
	//-----------------------------------------------------------------------------
	//将指定OBJ追加到某个OBJ的最后面。
	var appendTo=function(pObj,pTargetObj){
		try{
			pTargetObj.appendChild(pObj);
		}catch(e){
			alert(e.message);
		}
	}
	//-----------------------------------------------------------------------------
	var isFirstChild=function(pNode){
		//除了空白节点之外，是否是第一个节点
		var tmpNode=pNode.previousSibling;
		try{
			while(tmpNode.previousSibling!=null && tmpNode.nodeType!=1)
				tmpNode=tmpNode.previousSibling;
			if(tmpNode.nodeType==3)//是空节点
				return true;
			else
				return false;
		}catch(e){
			return true;
		}
	}
	var isLastChild=function(pNode){
		var tmpNode=pNode.nextSibling;
		try{
			while(tmpNode.nextSibling!=null && tmpNode.nodeType!=1)
				tmpNode=tmpNode.nextSibling;
			if(tmpNode.nodeType==3)//是空节点
				return true;
			else
				return false;
		}catch(e){
			return true;	
		}
	}
	//-----------------------------------------------------------------------------
	//循环绘制各节点。从下面这些起，这些节点具有收缩功能，所以，下面的这些不应该被oRoot所包含，而应该是oOutLine的孩子。
	var createSubTree=function(pNode,pLevel,pNodeArea,pTreeNode){
		var subNode;
		for(var i=0;subNode=pNode.childNodes[i];i++){			
			if(subNode.nodeType!=1) continue;//由于默认了把空白也当着一个节点来处理，所以，这里要判断一下。
			
			var subNodeItem		=createTag("DIV")
			
			if(subNode.hasChildNodes())
				var subNodeSubArea	=createTag("DIV");
				
				subNodeItem.level			=pLevel+1;
				subNodeItem.isFirstChild	=isFirstChild(subNode);
				subNodeItem.isLastChild		=isLastChild(subNode);
				//subNodeItem.parentTreeNode	=pTreeNode;//新增属性
				
			//下面的这个位置不能变动，因为createTreeLine里用到了它的parentNode
			appendTo(subNodeItem,pNodeArea);
			
			createTreeLine(subNode,subNodeItem);
			createIcon(subNode,subNodeItem);
			var subNodeCaption		=createCaption(subNode,pLevel+1);
				subNodeItem.caption	=subNodeCaption.innerHTML;
				
				subNodeItem.tree				=new treeNode();
				subNodeItem.tree.obj			=subNodeCaption;
				subNodeItem.tree.caption		=subNodeItem.caption;
				subNodeItem.tree.level			=subNodeItem.level;
				subNodeItem.tree.parentTreeNode	=pTreeNode;

			pTreeNode.treeNodes.push(subNodeItem.tree);
			
			appendTo(subNodeCaption,subNodeItem);
			
			if(subNode.hasChildNodes()){
				//createSubTree(subNode,pLevel+1,subNodeItem);
				appendTo(subNodeSubArea,subNodeItem);
				createSubTree(subNode,pLevel+1,subNodeSubArea,pTreeNode.treeNodes[pTreeNode.treeNodes.length-1]);
				subNodeItem.subNodeSubArea=subNodeSubArea;
				
				subNodeItem.expand=function(pFlag){
					//如果状态是展开，返回真，否则返回假。
					//this.subNodeSubArea.style.display=="" ? this.subNodeSubArea.style.display="none" : this.subNodeSubArea.style.display="";
					
					if(pFlag==null){
						if(this.subNodeSubArea.style.display==""){
							this.subNodeSubArea.style.display="none";
							return false;
						}else{
							this.subNodeSubArea.style.display="";
							return true;	
						}
					}else{
						//alert(this.expandBtn.tagName);
						if(pFlag)
							this.subNodeSubArea.style.display="";
						else this.subNodeSubArea.style.display="none";
												
						if(!this.isLastChild)
							this.expandBtn.src=OPEN[pFlag];
						else
							this.expandBtn.src=OPENBOTTOM[pFlag];					
						
					}
					
				};
			}
		}
	}
	
	
	this.expandByLevel=function(pLevel){
			
	}
	
	this.create=function(){
		//-----------------------------------------------------------------------------
		//绘制轮廓
		var oOutLine=createTag("DIV");
		oOutLine.className="outLine";
		appendTo(oOutLine,this.body);
		//oOutLine.onclick=this.onclick;
		//-----------------------------------------------------------------------------
		//绘制根。这个根不具备收缩的功能。
		var oRoot		=createTag("DIV");
			
			oRoot.level	=-1;//级别。根的级别为-1;
	
		var oRootIcon	=createImg(ROOTICON);	
		//var oRootCaption=createCaption(getDOMAtt(DOMRoot,self.CAPTIONATT),-1);
		var oRootCaption	=createCaption(DOMRoot,-1);
			oRoot.caption	=oRootCaption.innerHTML;	
		
		//================================================
		//子树
		//================================================
			oRoot.tree					=new treeNode();
			oRoot.tree.obj				=oRootCaption;
			oRoot.tree.caption			=oRoot.caption;
			oRoot.tree.level			=oRoot.level;
			oRoot.tree.parentTreeNode	=self.treeNodes[0];
		
		self.root=self.treeNodes[0]		=oRoot.tree;
		
		appendTo(oRootIcon,oRoot);
		appendTo(oRootCaption,oRoot);
		appendTo(oRoot,oOutLine);
		//------------------------------------------------------------------------------		
		createSubTree(DOMRoot,-1,oOutLine,self.treeNodes[0]);
		self.expand(this.EXPANDALL);
	}

	this.expand=function(pFlag){
		var obj	=	this.treeNodes[0].treeNodes;
		var length = obj.length;
		for (i=0;i<length ;i++ )
		{
			obj[i].expand(pFlag);
			this.EXPANDALL = pFlag;
		}
	}
}