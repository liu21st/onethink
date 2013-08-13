<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// TOPThink 平台自动加载机制
function topthink_autoload($name){
    // 自动加载系统API和服务接口
    $_autoPath =  array('Api','Behavior','Service');
    foreach ($_autoPath as $path){
        // 自动加载系统接口类
        if(require_cache(MODULE_PATH.$path.'/'.$name.'.class.php')) return ;
    }
    return ;
}
// 注册自动加载
spl_autoload_register('topthink_autoload');

// 实例化模型 并支持表前缀和数据库连接自动检测
function model($name='',$simple=false) {
    if(empty($name) || $simple) {
        return M($name);
    }
    // 获取模型表前缀
    static $_model  =  array();
    if(!isset($_model[$name])) {
        $model  =  M('Model')->where(array('name'=>strtolower($name)))->field('db_name,model_type,connection,table_name,table_prefix')->find();
        // 实例化模型
        $dbName =   $model['db_name']?$model['db_name'].'.':'';
        if(!empty($model['table_name'])) {// 定义了实际表名
            $_model[$name]  = M($dbName.$model['table_name'],null,$model['connection']);
        }else{
            $_model[$name]  =  M($dbName.$name,$model['table_prefix'],$model['connection']);
        }
    }
    return $_model[$name];
}

// 密码哈希规则
function pwdHash($password,$type='md5') {
    return hash($type,$password);
}

function ubbfilter($str) {
    $str = str_replace(array('&amp;gt;','&amp;lt;','<','>'),array('&gt;','&lt;','&lt;','&gt;'),$str);
    return $str;
}
// 文档状态过滤 自动判断当前是否预览模式
function filterStatus(){
    return C('PREVIEW_MODE')?array(1,2,'or'):1;
}

function array_to_string($str,$join=',') {
    if(empty($str)) {
        return '';
    }
    return implode($join,$str);
}

function string_to_array($str,$spr=',') {
    return explode($spr,$str);
}

function readrecord($recordId,$action) {
    $url    =   '/Think/edit/_module/'.$action['model'].'/id/'.$recordId;
    $data   =   model($action['model'])->find($recordId);
    array_shift($data);
    return '<a href="'.$url.'" target="_blank">'.array_shift($data).'</a>';
}

// 1:选项1,2:选项2 ... 转成数组
function express_to_array($str) {
    $options    =   explode(',',$str);
    $result =   array();
    foreach ($options as $option){
        $array  =   explode(':',$option);
        $result[$array[0]]   =   isset($array[1])?$array[1]:$array[0];
    }
    return $result;
}

function parseParams($rule) {
    preg_match_all('/{(.+?)}/is',$rule,$array);
    return $array[1];
}

// 获取映射文档
function getMapArticle($article) {
    if(!empty($article['map_id'])) {
        list($mapModule,$mapId)  =  explode('_',$article['map_id']);
        $data = M($mapModule)->find($mapId);
        $mapList = string_to_array(M('Model')->where('name="'.$module.'"')->getField('map_list'));
        foreach ($mapList as $field){
            if(isset($article[$field])) {
                $data[$field]  =  $article[$field];
            }
        }
        $data['id']   = $article['id'];
        $data['map_id']   =  $article['map_id'];
        $data['record_id'] =  $article['record_id'];
        $data['cate_id']   =  $article['cate_id'];
        $data['status']   =  $article['status'];
        $data['create_time'] = $article['create_time'];
        $data['update_time']   =  $article['update_time'];
        $article   = $data;
    }
    return $article;
}

// 执行某个用户行为
function actionLog($actionName,$memberId,$recordId='',$vars=array()){
    // 查询行为
    $action  =  M('Action')->getByName($actionName);
    // 记录行为
    $log['action_id']    = $action['id'];
    $log['record_id']   =  $recordId;
    $log['action_ip']    = get_client_ip();
    $log['member_id'] = $memberId;
    $log['create_time']   =  NOW_TIME;
    M('ActionLog')->add($log);
    // 分析行为
    parse_express($action['express'],$vars);
}

function isRunAction($memberId,$actionName) {
    $action  =  M('Action')->getByName($actionName);
    $Log =  M("ActionLog");
    $map['member_id']  =  $memberId;
    $map['action_id']  = $action['id'];
    if($Log->where($map)->field('id')->find()){
        return true;
    }else{
        return false;
    }
}

//--------------------------判断用户的行为次数----------------------------------------------
function memberActionCount($memberId,$actionName,$type='d',$intval=1){
    $action  =  M('Action')->getByName($actionName);
    switch(strtolower($type)) {// 行为检测间隔
        case 'h':  // 每小时
            $time = strtotime(date('Y-m-d H'));
            break;
        case 'd':  // 每天
            $time = strtotime(date('Y-m-d'));
            break;
        case 'm': // 每月
            $time = strtotime(date('Y-m-01'));
            break;
        case 'w':  // 每周
            $time = strtotime(date('Y-m-d',strtotime('-'.date('w').' days')));
            break;
        case 'y': // 每年
            $time = strtotime(date('Y-01-01'));
            break;
    }
    // 查看时间间隔内该用户的行为执行次数
    $Log =  M("ActionLog");
    $map['member_id']  =  $memberId;
    $map['action_id']  = $action['id'];
    $map['create_time'] =  array('gt',$intval*$time);
    $count   =  $Log->where($map)->count();
    return $count;
}

// 分析行为
function parse_express($express,$vars=array()) {
    $express = explode(';',$express);
    foreach ($express as $exp){
        // 表达式 model|id=1&name=%name|a=a+1,b=b-1;...
        $array   =  explode('|',$exp);
        $name   = $array[0];
        parse_str($array[1],$where);
        foreach ($where as $key=>$val){
            if(0===strpos($val,'%')) {
                $where[$key]  = $vars[substr($val,1)];
            }else{
                $where[$key]  = $val;
            }
        }
        $data = array();
        $temp   =  explode(',',$array[2]);
        foreach ($temp as $t){
            list($key,$value)  =  explode('=',$t);
            $data[$key]    = array('exp',$value);
        }
        if(!empty($data)) {
            // 更新模型
            model($name)->where($where)->save($data);
        }
    }
}

// 模型事件监听
function event($module,$name,$vars=array()){
    $model  =   M('Model')->getByName($module);
    // 系统默认监控日志
    if('log'!= $module) {
        $log    =   array();
        $log['type']    =   $name;
        $log['user_id']     =   $_SESSION[C('USER_AUTH_KEY')];
        $log['model']   =   $module;
        $log['record_id']   =   $vars['id'];
        $log['ip']  =   get_client_ip();
        $log['create_time'] =   NOW_TIME;
        M('Log')->add($log);
    }
    // 传人模型名称
    $vars['module']   =  $module;
    // 获取模型的事件定义
    $map['model_id'] =  $model['id'];
    $map['name']   =  $name;
    $map['status']  =   1;
    $events =  M('Event')->where($map)->order('sort')->getField('type,extra');
    if($events) {
        // 响应事件
        foreach ($events as $type=>$event){
            switch($type) {
                case 'class': // 调用事件类
                    $Event  =   A(parse_name($module,1),'Event');
                    $Event->$event($vars);
                    break;
                case 'fun': // 调用函数
                    $event($vars);
                    break;
                case 'express': // 调用行为表达式
                    parse_express($event,$vars);
                    break;
                case 'behavior': // 调用行为
                    B($event,$vars);
                    break;
                case 'task': // 调用任务
                    $Task  =   A(parse_name($module,1),'Task');
                    $Task->$event($vars);
                    break;
            }
        }
        return true;
    }else{
        return false;
    }
}

/**
 +----------------------------------------------------------
 * 字节格式化 把字节数格式为 B K M G T 描述的大小
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function byte_format($size, $dec=2){
	$a = array("B", "KB", "MB", "GB", "TB", "PB");
	$pos = 0;
	while ($size >= 1024) {
		 $size /= 1024;
		   $pos++;
	}
	return round($size,$dec)." ".$a[$pos];
}

// 时间戳日期格式化
function toDate($time,$format='Y-m-d H:i:s'){
	if( empty($time)) {
		return '';
	}
    $format = str_replace('#',':',$format);
	return date(($format),$time);
}

// 中文字符串截取
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix='...')
{
    if(strlen($str)<=3*$length) {
        return $str;
    }
    if(function_exists("mb_substr"))
        return mb_substr($str, $start, $length, $charset).$suffix;
    elseif(function_exists('iconv_substr')) {
        return iconv_substr($str,$start,$length,$charset).$suffix;
    }
    $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    return $slice.$suffix;
}

//+---------------------------------------
//|  输出函数
//+---------------------------------------

// 获取某个文档的URL地址
function getArticleLink($module,$id) {
}

// 获取静态URL地址 $type 0 首页 1 频道页 2 栏目列表页 3 内容页 4 栏目首页
function url($id,$type,$page=1,$data='') {
    if(empty($id)) return '';
    switch($type) {
        case 0:// 首页
            return Html::getIndexUrl($id,$data);
        case 1:// 频道页
            return Html::getChannelUrl($id,$data);
        case 2:// 栏目列表页
            return Html::getCateListUrl($id,$page,$data);
        case 4:// 栏目首页 暂时不用
            return Html::getCateIndexUrl($id,$data);
        case 6:// 图库页面
            return Html::getPicUrl($id,$data,5279);
        case 8:// 频道列表页
            return Html::getChannelListUrl($id,$page,$data);
        case 3:// 内容页
        default:
            return Html::getArticleUrl($id,$page,$data);
    }
}


//纯文本输出
function _t($text){
	return hsc(trim($text));
}

//改进的htmlspecialchars()
function hsc($text)
{
	return preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
		   str_replace(array('&', '"', '<', '>',' '), array('&amp;', '&quot;', '&lt;', '&gt;', '&nbsp;'), $text));
}

function h($string) {
	$pattern = array('/(javascript|prop|jscript|js|vbscript|vbs|about):/i','/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i','/<script([^>]*)>/i','/<iframe([^>]*)>/i','/<frame([^>]*)>/i','/<link([^>]*)>/i','/@import/i','/(expression)/i');
	$replace = array('','','&lt;script${1}&gt;','&lt;iframe${1}&gt;','&lt;frame${1}&gt;','&lt;link${1}&gt;','','');
	$string = preg_replace($pattern, $replace, $string);
	$string = str_replace(array('</script>', '</iframe>', '&#'), array('&lt;/script&gt;', '&lt;/iframe&gt;', '&amp;#'), $string);
	return stripslashes($string);
}

// 获取项目自己的配置文件
function getSiteConfig() {
    return M('Config')->where('site=0 OR site=1')->getField('name,value');
}

// 获取后台用户的ID
function getUserId() {
    return $_SESSION[C('USER_AUTH_KEY')];
}
// 获取用户的角色列表
function getUserRoles($userId='') {
    $userId =   $userId?$userId:getUserId();
    // 获取当前用户的角色
    $map['user_id'] =   $userId;
    $roles  =   M('RoleUser')->where($map)->getField('role_id',true);
    return $roles;
}

// 检查用户是否具有当前流程的权限
function checkUserFlow($flowId) {
    $flow =  M('Flow')->find($flowId);
    if(empty($flow['user_id'])) { // 指定用户ID
        $userId  = getUserId();
        if(in_array($userId,explode(',',$flow['user_id']))) {
            return true;
        }
    }else{ // 检查角色组
        $map['role_id'] = array('IN',$flow['role_list']);
        $map['user_id']   =  getUserId();
        $find = M('RoleUser')->where($map)->find();
        if($find) {
            return true;
        }
    }
    return false;
}
function getFieldMust($isMust) {
    return $isMust?'bLeftRequire':'bLeft';
}
// 获取属性列表
function getAttrList($module,$site='') {
    $map['status'] = 1;
    $map['site'] = $site;
    $map['module']   =  $module;
    return M('Attribute')->where($map)->getField('id,title');
}

function getModelType($type) {
    static $_type = array(0=>'系统',2=>'文档',1=>'视图');
    return $_type[$type];
}

function parseTpl($content) {
    // 并对内容进行模板再次解析
    $compiler = new ThinkTemplate();
    $content = $compiler->parse($content);
    $guid =  md5($content);
    file_put_contents(TEMP_PATH.$guid.'.php',$content);
    ob_start();
    include TEMP_PATH.$guid.'.php';
    $content =  ob_get_clean();
    unlink(TEMP_PATH.$guid.'.php');
    return $content;
}

function getModels($appId='') {
    $map['status'] = 1;
    if(!empty($appId)) {
        $map['app_id']  =   intval($appId);
    }else{
        $map['app_id']  =   0;
    }
    $map['allow_as_sub']    =   1;
    return M('Model')->where($map)->getField('name,title');
}

function tagFilter($tag) {
    return $tag?trim($tag).' ':'';
}

function setStatus() {
    if(!empty($_POST['cate_id'])) {
        $requireAudit   =  M('Cate')->where('id='.$_POST['cate_id'])->getField('require_audit');
        return $requireAudit?2:1;
    }else{
        return 1;
    }
}

function setPwd($pwd) {
    if(empty($pwd)) {
        return false;
    }else{
        return md5($pwd);
    }
}
function setMapId($module,$id) {

}
function is_serialized( $data ) {
     $data = trim( $data );
     if ( 'N;' == $data )
         return true;
     if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
         return false;
     switch ( $badions[1] ) {
         case 'a' :
         case 'O' :
         case 's' :
             if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
                 return true;
             break;
         case 'b' :
         case 'i' :
         case 'd' :
             if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
                 return true;
             break;
     }
     return false;
 }

 function is_serialized_string( $data ) {
     $data = trim( $data );
     if ( preg_match( '/^s:[0-9]+:.*;$/s', $data ) )
         return true;
     return false;
 }

 function is_serialized_array( $data ) {
     $data = trim( $data );
     if ( preg_match( '/^a:[0-9]+:.*;$/s', $data ) )
         return true;
     return false;
 }

 // 分析配置值 格式 a:名称1,b:名称2
function parseAttr($string) {
    preg_replace('@(\w+)\:([^,\/]+)@e', '$var[\'\\1\']="\\2";', $string);
    return $var;
}

function getApps() {
    return M('App')->where('status=1')->getField('id,title');
}

function getAppNames() {
    return M('App')->where('status=1')->field('name,title')->select();
}

function getAppName($app_id) {
    static $name    =   array();
    if($app_id==0) {
        return '全局';
    }
    if(isset($name[$app_id])) {
        return $name[$app_id];
    }
    $name[$app_id]   =   M('App')->where('id='.(int)$app_id)->getField('title');
    return $name[$app_id];
}
function getDefaultStyle($style) {
    if(empty($style)) {
        return 'blue';
    }else{
        return $style;
    }
}

// 获取栏目英文名称
function getCateName($id) {
    if(empty($id)) {
        return '';
    }
    static $_name =  array();
    if(isset($_name[$id])) {
        return $_name[$id];
    }
    $_name[$id]   =  M('Cate')->where('id='.$id)->getField('name');
    return $_name[$id];
}

// 获取所在频道英文名称
function getChannelName($id) {
    if(empty($id)) {
        return '';
    }
    static $_name =  array();
    if(isset($_name[$id])) {
        return $_name[$id];
    }
    $pids =  D('Cate')->getParentCateId($id,true);
    if(count($pids)>1) {
        $_name[$id]   =  M('Cate')->where('id='.$pids[1])->getField('name');
    }else{
        $_name[$id]   =  '';
    }
    return $_name[$id];
}

function getRecordCount($id,$module='') {
    $map['record_id'] =  $id;
    $map['status'] = 1;
    $map['cate_id']   =  0;
    if(!empty($module)) {
        $map['module']   =  $module;
    }
    return M('Article')->where($map)->count('id');
}
function IP($ip='',$charset='gbk',$file='QQWry.dat') {
	static $_ip	=	array();
	if(isset($_ip[$ip])) {
		return $_ip[$ip];
	}else{
		import("ORG.Net.IpLocation");
		$iplocation = new IpLocation($file);
		$location = $iplocation->getlocation($ip);
		$_ip[$ip]	 =	 $location['country'].$location['area'];
	}
    if('utf-8' != $charset) {
        $_ip[$ip] = auto_charset($_ip[$ip],$charset,'utf-8');
    }
	return $_ip[$ip];
}

function showPic($pic) {
    return $pic?'<img src="'.$pic.'" />':'';
}

function getNodeName($id) {
	if(Session::is_set('nodeNameList')) {
		$name	=	Session::get('nodeNameList');
		return $name[$id];
	}
	$Group	=	D("Node");
	$list	=	$Group->getField('id,name');
	$name	=	$list[$id];
	Session::set('nodeNameList',$list);
    return $name;
}

function showTitle($title) {
    return msubstr($title,0,24);
}
function getArticleTitle($id,$module='') {
    if(empty($module)) {
        list($module,$id)  =  explode('_',$id);
    }
    return model($module)->where('id='.$id)->getField('title');
}
function parseRecordId($id,$module='') {
    if(empty($module)) {
        list($module,$id)  =  explode('_',$id);
    }
    return '_module/'.$module.'/id/'.$id;
}

function getLevelTitle($title,$level) {
    return str_repeat('-',$level).$title;
}
function addColumn($id,$level) {
    $level = $level?$level:0;
    return '<a href="javascript:add('.$id.','.($level+1).')">新增</a>';
}
function getShowTitle($title,$isHigh,$highColor='Red') {
	if($isHigh) {
		$title	=	"<span style='font-weight:bold;color:".$highColor."'>".$title."</span>";
	}
	return $title;
}

function getAttrType($type) {
    static $_type = array('string'=>'字符型','num'=>'数字型','bool'=>'布尔型','zone'=>'地区联动','html'=>'HTML型','verify'=>'验证码','editor'=>'编辑器','cate'=>'分类联动','textarea'=>'文本域','text'=>'文本型','select'=>'枚举型','hidden'=>'固定值','file'=>'附件型','files'=>'多附件型','images'=>'多图型','dynamic'=>'动态型',9=>'验证码','radio'=>'单选型','checkbox'=>'多选型','date'=>'日期型','image'=>'图片型','complex'=>'组合型','serialize'=>'序列化','link'=>'连接型');
    return $_type[$type];
}
function getEventTime($time) {
    static $_type   =  array('insert'=>'新增','update'=>'更新','del'=>'删除','get'=>'获取');
    return $_type[$time];
}
function getEventType($type) {
    static $_type   =  array('fun'=>'函数','class'=>'行为','task'=>'任务','express'=>'表达式');
    return $_type[$type];
}
function getAttrModule($module) {
    static $_module   =  array(0=>'系统',1=>'用户');
    return $_module[$module];
}
function getUserName($id)
{
    static $_user   =  array();
    if(isset($_user[$id])) {
        return $_user[$id];
    }
	$User	=	M("Auth");
	$user	=	$User->where('id='.(int)$id)->field('id,nickname')->find();
	$name	=	$user['nickname'];
    $_user[$id] =  $name;
    return $name;
}

// 获取站点前缀
function getModelPrefixBySite($siteId) {
    static $_array  =  array();
    if(!isset($_array[$siteId])) {
        // 站点前缀设置
        $_array[$siteId]  =  M('Cate')->where('id='.(int)$siteId)->getField('table_prefix');
    }
    return $_array[$siteId];
}

// 获取当前分类的所属站点ID
function getSiteIdByCate($cateId) {
    $rootId  =  M('Cate')->where('id='.$cateId)->getField('root_id');
    return  $rootId==0?$cateId:$rootId;
}

// 获取分类所属模型名称
function getModuleByCate($cateId) {
    static $_array  =  array();
    // 查询分类和模型的对应关系
    if(!isset($_array[$cateId])) {
        $moduleId  =  M('Cate')->where('id='.(int)$cateId)->getField('model');
        if(!$moduleId) {
            $pid    =   M('Cate')->where('id='.(int)$cateId)->getField('pid');
            $moduleId = M('Cate')->where('id='.$pid)->getField('model');
        }
        $_array[$cateId] =  M('Model')->where('id='.(int)$moduleId)->getField('name');
    }
    return $_array[$cateId];
}

function getModelById($id) {
    return M('Model')->where('id='.(int)$id)->getField('title');
}
function getModelIdByName($name) {
    $map['name']    =   $name;
    return M('Model')->where($map)->getField('id');
}
function getModelNameById($id) {
    return M('Model')->where('id='.(int)$id)->getField('name');
}

function getModelName($name) {
    static $_model   =  array();
    if(isset($_model[$name])) {
        return $_model[$name];
    }
	$Model	=	M("Model");
	$model	=	$Model->where('name="'.$name.'"')->field('name,title')->find();
	$title	=	$model['title'];
    $_model[$name] =  $title;
    return $title;
}
function getMemberName($id)
{
    static $_user   =  array();
    if(isset($_user[$id])) {
        return $_user[$id];
    }
	$Member	=	M("Member");
	$member	=	$Member->where('id='.$id)->field('id,nickname')->find();
	$name	=	$member['nickname'];
    $_user[$id] =  $name;
    return $name;
}

function getRoles() {
    return M('Role')->field('id,name')->select();
}

function getNodeGroupName($id)
{
    if(empty($id)) {
        return '未分组';
    }
    if(isset($_SESSION['nodeGroupList'])) {
        return $_SESSION['nodeGroupList'][$id];
    }
	$Group	=	M("Group");
	$list	=	$Group->getField('id,title');
    $_SESSION['nodeGroupList']   = $list;
	$name	=	$list[$id];
    return $name;
}

function build_count_rand ($number,$length=4,$mode=1) {
    if($mode==1 && $length<strlen($number) ) {
        //不足以生成一定数量的不重复数字
        return false;
    }
    $rand   =  array();
    for($i=0; $i<$number; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $unqiue = array_unique($rand);
    if(count($unqiue)==count($rand)) {
        return $rand;
    }
    $count   = count($rand)-count($unqiue);
    for($i=0; $i<$count*3; $i++) {
        $rand[] =   rand_string($length,$mode);
    }
    $rand = array_slice(array_unique ($rand),0,$number);
    return $rand;
}

function getArticleMap($mapId) {
    return !empty($mapId)?'<IMG SRC="'.APP_TMPL_PATH.'/Public/images/addMsg.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="映射">':'';
}

// 获取文章的推荐位置
function getArticlePos($pos='',$imageShow=false) {
    $list   =  parseAttr(C('ARTICLE_RECOMMEND_POS'));
    return $pos==''?$list:$list[$pos];
}

function getStatus($status,$imageShow=true)
{
    switch($status) {
    	case 0:
            $showText   = '禁用';
            $showImg    = '<IMG SRC="__PUBLIC__/images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
            break;
    	case 2:
            $showText   = '待审';
            $showImg    = '<IMG SRC="__PUBLIC__/images/checkin.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
            break;
    	case 3:
            $showText   = '发布';
            $showImg    = '<IMG SRC="__PUBLIC__/images/record.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="发布">';
            break;
    	case -1:
            $showText   = '删除';
            $showImg    = '<IMG SRC="__PUBLIC__/images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
            break;
        case 1:
        default:
            $showText   =   '正常';
            $showImg    =   '<IMG SRC="__PUBLIC__/images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

    }
    return ($imageShow===true)? ($showImg) : $showText;
}

function showStatus($status,$id) {
	switch($status) {
	case 0:$info	=	'<a href="javascript:resume('.$id.')">恢复</a>';break;
	case 2:$info	=	'<a href="javascript:pass('.$id.')">批准</a>';break;
	case 1:$info	=	'<a href="javascript:forbid('.$id.')">禁用</a>';break;
	case -1:$info	=	'<a href="javascript:recycle('.$id.')">还原</a>';break;
	}
	return $info;
}

function getArticleStatus($status,$imageShow=true)
{
    switch($status) {
    	case 0:
            $showText   = '禁用';
            $showImg    = '<IMG SRC="__PUBLIC__/images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
            break;
    	case 2:
            $showText   = '待审';
            $showImg    = '<IMG SRC="__PUBLIC__/images/checkin.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
            break;
    	case 3:
            $showText   = '发布';
            $showImg    = '<IMG SRC="__PUBLIC__/images/record.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="发布">';
            break;
    	case -1:
            $showText   = '删除';
            $showImg    = '<IMG SRC="__PUBLIC__/images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
            break;
        case 1:
        default:
            $showText   =   '正常';
            $showImg    =   '<IMG SRC="__PUBLIC__/images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';

    }
    return ($imageShow===true)? ($showImg) : $showText;
}

function showPublish($status) {
    if($status) {
       return '<IMG SRC="__PUBLIC__/images/allow.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="已经发布">';
    }else{
        return '';
    }
}
function showArticleStatus($status,$id) {
	switch($status) {
	case 0:$info	=	'<a href="javascript:resume('.$id.')">恢复</a>';break;
	case 1:$info	=	'<a href="javascript:forbid('.$id.')">发布</a>';break;
	case 2:$info	=	'<a href="javascript:pass('.$id.')">审核</a>';break;
	case -1:$info	=	'<a href="javascript:recycle('.$id.')">还原</a>';break;
	}
	return $info;
}

/**
 +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
 +----------------------------------------------------------
 * @param string $fmode 文件名
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function build_verify ($length=4,$mode=1) {
    return rand_string($length,$mode);
}

function getCateTitle($id)
{
    static $_column = array();
    if(isset($_column[$id])) {
        return $_column[$id];
    }
	$dao	=	M("Cate");
	$name	=	$dao->where('id='.$id)->getField('title');
    $_column[$id] =  $name;
    return $name;
}

function getClassName($name)
{
    static $_model = array();
	$Model	=	M("Model");
    if(empty($_model)) {
        $_model   =  $Model->getField('name,title');
    }
    return $_model[$name];
}

function getGroupName($id)
{
    if($id==0) {
    	return '无上级组';
    }
	$list	=	M("Role")->field('id,name')->select();
	foreach ($list as $vo){
		$nameList[$vo['id']] = $vo['name'];
	}
	$name	=	$nameList[$id];
    return $name;
}

function getTabeSize($a,$b) {
    return byte_format($a+$b);
}
function readFileList($filename)
{
    $file = urlsafe_b64encode($filename);
	// xp系统下面的文件名采用gbk编码 需要转换成uft8编码格式
    $name  =  preg_replace('/^.+[\\\\\\/]/', '', auto_charset($filename,'gbk','utf-8'));
	$pathinfo	=	pathinfo($filename);
	if(is_dir($filename)) {
		$pathinfo['extension']	=	'dir';
	}
	return showExt($pathinfo['extension'])." <a href='".__URL__.'/index/f/'.$file."'>".$name."</a>";
}

function readRecycleFile($filename) {
    $file = urlsafe_b64encode($filename);
    return " <a href='javascript:readRecycleFile(\"".$file."\")'>".basename($filename)."</a>";
}
// 显示文件所在路径 用于搜索
function showFileDir($filename) {
    $dirname  =  dirname($filename);
    $filename = urlsafe_b64encode($dirname);
    $dirname  =  auto_charset($dirname,'gbk','utf-8');
	return " <a href='".__URL__.'/index/f/'.$filename."'>".$dirname."</a>";
}
function urlsafe_b64encode($string) {
   $data = base64_encode($string);
   $data = str_replace(array('+','/','='),array('-','_',''),$data);
   return $data;
}

function urlsafe_b64decode($string) {
   $data = str_replace(array('-','_'),array('+','/'),$string);
   $mod4 = strlen($data) % 4;
   if ($mod4) {
       $data .= substr('====', $mod4);
   }
   return base64_decode($data);
}

function showDirName($filename,$file,$showIco=true) {
	if($showIco) {
		if(empty($file['ext'])) {
			$file['ext']	=	'dir';
		}
		$str	=	showExt($file['ext']);
	}
	if($file['isDir']) {
		// 目录
		$str .= " <a  href='javascript:read(\"".$filename."\")'>".$filename."</a>";
	}else{
		$str .= " <a  href='javascript:download(\"".$filename."\")'>".$filename."</a>";
	}
	return $str;
}
function showFileName($name,$id,$showIco=true) {
	$attach = D("Document");
	$attach->getById($id);
	if($showIco) {
		$str	=	showExt($attach->extension);
	}
	switch($attach->extension) {
	case 'dir':
		$str .= " <a  href='javascript:read(".$id.")'>".$name."</a>";
		break;
	case 'txt':
	case 'css':
	case 'js':
	case 'php':
	case 'html':
		$str .= " <a  href='javascript:look(".$id.")'>".$name."</a>";
		break;
    case 'gif':
    case 'png':
    case 'jpg':
        $str .= " <a href='".__ROOT__.'/Admin/'.$attach->savepath.$attach->savename."' rel='lightbox'>".$name."</a>";
        break;
	default:
		$str .= " <a  href='javascript:download(".$id.")'>".$name."</a>";
	}
	return $str;
}

function color_txt($str)
{
    if(function_exists('iconv_strlen')) {
    	$len  = iconv_strlen($str);
    }else if(function_exists('mb_strlen')) {
    	$len = mb_strlen($str);
    }
    $colorTxt = '';
    for($i=0; $i<$len; $i++) {
               $colorTxt .=  '<span style="color:'.rand_color().'">'.msubstr($str,$i,1,'utf-8','').'</span>';
     }

    return $colorTxt;
}

function extension($filename) {
	$pathinfo = pathinfo($filename);
	return $pathinfo['extension'];
}
function showAttrModule($id) {
    static $_id = array('1'=>'会员','0'=>'文档');
    return $_id[$id];
}
function showExt($ext,$pic=true) {
	static $_extPic = array(
		'dir'=>"folder.gif",
		'doc'=>'msoffice.gif',
		'docx'=>'msoffice.gif',
		'xls'=>'msoffice.gif',
		'xlsx'=>'msoffice.gif',
		'ppt'=>'msoffice.gif',
		'pptx'=>'msoffice.gif',
		'rar'=>'rar.gif',
		'zip'=>'zip.gif',
		'txt'=>'text.gif',
		'pdf'=>'pdf.gif',
		'html'=>'html.gif',
		'png'=>'image.gif',
		'gif'=>'image.gif',
		'jpg'=>'image.gif',
		'php'=>'text.gif',
		'swf'=>'swf.gif',
	);
	static $_extTxt = array(
		'dir'=>'文件夹',
		'jpg'=>'JPEG图象',
		);
	if($pic) {
		if(array_key_exists(strtolower($ext),$_extPic)) {
			$show = "<IMG SRC='__PUBLIC__/Images/extension/".$_extPic[strtolower($ext)]."' BORDER='0' alt='' align='absmiddle'>";
		}else{
			$show = "<IMG SRC='__PUBLIC__/Images/extension/common.gif' WIDTH='16' HEIGHT='16' BORDER='0' alt='文件' align='absmiddle'>";
		}
	}else{
		if(array_key_exists(strtolower($ext),$_extTxt)) {
			$show = $_extTxt[strtolower($ext)];
		}else{
			$show = $ext?$ext:'文件夹';
		}
	}

	return $show;
}

function showThumb($id) {
	$dao = D("Attach");
	$attach = $dao->getById($id);
	if(in_array(strtolower($attach->extension),array('gif','png','jpg'))) {
		$str =  "<img src='/Thinkvms/admin/".$attach->savepath.$attach->savename."' width='75px' alt='$attach->name $attach->remark' height='75px' />";
	}else{
		$str =  "<img src='__PUBLIC__/Images/ext/".strtolower($attach->extension).".png' width='75px' alt='$attach->name $attach->remark' height='75px' />";
	}
	return showFileName($str,$id,false);
}

function getRecommend($type)
{
	switch($type) {
		case 1: $icon = '<IMG SRC="__PUBLIC__/images/brand.gif" BORDER="0" align="absmiddle" ALT="">';break;
	default:
		$icon = '';
	}
	return $icon;
}

function getTop($type)
{
	switch($type) {
		case 1: $icon = '<IMG SRC="__PUBLIC__/images/top.gif" BORDER="0" align="absmiddle" ALT="">';break;
	default:
		$icon = '';
	}
	return $icon;
}

function seems_utf8($Str) { # by bmorel at ssi dot fr
	for ($i=0; $i<strlen($Str); $i++) {
		if (ord($Str[$i]) < 0x80) continue; # 0bbbbbbb
		elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
		elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
		elseif ((ord($Str[$i]) & 0xF8) == 0xF0) $n=3; # 11110bbb
		elseif ((ord($Str[$i]) & 0xFC) == 0xF8) $n=4; # 111110bb
		elseif ((ord($Str[$i]) & 0xFE) == 0xFC) $n=5; # 1111110b
		else return false; # Does not match any model
		for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
			if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80))
			return false;
		}
	}
	return true;
}

/**
 +----------------------------------------------------------
 * 检查字符串是否是UTF8编码
 +----------------------------------------------------------
 * @param string $string 字符串
 +----------------------------------------------------------
 * @return Boolean
 +----------------------------------------------------------
 */
function is_utf8($string)
{
	return preg_match('%^(?:
		 [\x09\x0A\x0D\x20-\x7E]            # ASCII
	   | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
	   |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
	   | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
	   |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
	   |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
	   | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
	   |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
   )*$%xs', $string);
}
/**
 +----------------------------------------------------------
 * 代码加亮
 +----------------------------------------------------------
 * @param String  $str 要高亮显示的字符串 或者 文件名
 * @param Boolean $show 是否输出
 +----------------------------------------------------------
 * @return String
 +----------------------------------------------------------
 */
function highlight_code($str,$number=true,$show=false)
{
    if(file_exists($str)) {
        $str    =   file_get_contents($str);
    }
    $str  =  stripslashes(trim($str));
    // The highlight string function encodes and highlights
    // brackets so we need them to start raw
    $str = str_replace(array('&lt;', '&gt;'), array('<', '>'), $str);

    // Replace any existing PHP tags to temporary markers so they don't accidentally
    // break the string out of PHP, and thus, thwart the highlighting.

    $str = str_replace(array('&lt;?php', '?&gt;',  '\\'), array('phptagopen', 'phptagclose', 'backslashtmp'), $str);

    // The highlight_string function requires that the text be surrounded
    // by PHP tags.  Since we don't know if A) the submitted text has PHP tags,
    // or B) whether the PHP tags enclose the entire string, we will add our
    // own PHP tags around the string along with some markers to make replacement easier later

    $str = '<?php //tempstart'."\n".$str.'//tempend ?>'; // <?

    // All the magic happens here, baby!
    $str = highlight_string($str, TRUE);

    // Prior to PHP 5, the highlight function used icky font tags
    // so we'll replace them with span tags.
    if (abs(phpversion()) < 5)
    {
        $str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
        $str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
    }

    // Remove our artificially added PHP
    $str = preg_replace("#\<code\>.+?//tempstart\<br />\</span\>#is", "<code>\n", $str);
    $str = preg_replace("#\<code\>.+?//tempstart\<br />#is", "<code>\n", $str);
    $str = preg_replace("#//tempend.+#is", "</span>\n</code>", $str);

    // Replace our markers back to PHP tags.
    $str = str_replace(array('phptagopen', 'phptagclose', 'backslashtmp'), array('&lt;?php', '?&gt;', '\\'), $str); //<?
    $line   =   explode("<br />", rtrim(ltrim($str,'<code>'),'</code>'));
    $result =   '<div class="code"><ol>';
    foreach($line as $key=>$val) {
        $result .=  '<li>'.$val.'</li>';
    }
    $result .=  '</ol></div>';
    $result = str_replace("\n", "", $result);
    if( $show!== false) {
        echo($result);
    }else {
        return $result;
    }
}

function showFileThumb($filename) {
	$pathinfo	=	pathinfo($filename);
	$basename	=	$pathinfo['basename'];
	if(empty($pathinfo['extension'])) {
		$pathinfo['extension']	=	'dir';
	}
	if(in_array(strtolower($pathinfo['extension']),array('gif','png','jpg'))) {
		$str =  "<img src='/Thinkvms/admin/".$filename."' width='75px' alt='$basename' height='75px' />";
	}else{
		$str =  "<img src='".WEB_PUBLIC_PATH."/Images/ext/".strtolower($pathinfo['extension']).".png' width='75px' alt='$basename' height='75px' />";
	}
	$return	= "<a href='".__URL__.'/index/f/'.base64_encode($filename)."'>".$str."</a>";
	return $return;
}
	/**
	 +----------------------------------------------------------
	 * 产生随机字串，可用来自动生成密码
	 * 默认长度6位 字母和数字混合 支持中文
	 +----------------------------------------------------------
	 * @param string $len 长度
	 * @param string $type 字串类型
	 * 0 字母 1 数字 其它 混合
	 * @param string $addChars 额外字符
	 +----------------------------------------------------------
	 * @return string
	 +----------------------------------------------------------
	 */
	function rand_string($len=6,$type='',$addChars='') {
		$str ='';
		switch($type) {
			case 0:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
				break;
			case 1:
				$chars= str_repeat('0123456789',3);
				break;
			case 2:
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
				break;
			case 3:
				$chars='abcdefghijklmnopqrstuvwxyz'.$addChars;
				break;
			default :
				// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
				$chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
				break;
		}
		if($len>10 ) {//位数过长重复字符串一定次数
			$chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
		}
		if($type!=4) {
			$chars   =   str_shuffle($chars);
			$str     =   substr($chars,0,$len);
		}else{
			// 中文随机字
			for($i=0;$i<$len;$i++){
			  $str.= msubstr($chars, floor(mt_rand(0,mb_strlen($chars,'utf-8')-1)),1);
			}
		}
		return $str;
	}

function showTags($tags)
{
	$tags = explode(' ',$tags);
    $str = '';
    foreach($tags as $key=>$val) {
    	$tag =  trim($val);
        $str  .= ' <a href="'.__URL__.'/tag/name/'.urlencode($tag).'">'.$tag.'</a>  ';
    }
    return $str;
}

/**
 +----------------------------------------------------------
 * 把返回的数据集转换成Tree
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function toTree($list=null, $pk='id',$pid = 'pid',$child = '_child',$root=0)
{
    // 创建Tree
    $tree = array();
    if(is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] =& $list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] =& $list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

function parseT($string,$return=false) {
    static $_result =   array();
    if(isset($_result[$string])) {
        return $_result[$string];
    }
    if(strpos($string,'.')) {
        list($name,$field,$map) = explode('.',$string);
    }else{
        $name   =   $string;
        $field  =   'title';
        $map =  'status=1';
    }
    if($return) {
        $result =   model($name)->where($map)->getField('id,'.$field);
    }else{
        $result   =  model($name)->where($map)->getField('id,id,'.$field,':');
    }
    $_result[$string]   =   $result;
    return $result;
}

/**
 +----------------------------------------------------------
 * 压缩PHP文件内容和简单加密
 +----------------------------------------------------------
 * @param string $filename 文件名
 * @param boolean $strip 是否去除代码空白和注释
 +----------------------------------------------------------
 * @return false|integer 返回加密文件的字节大小
 +----------------------------------------------------------
 */
function encode_file_contents($filename,$strip=true)
{
    $type = strtolower(substr(strrchr($filename, '.'),1));
    if('php'==$type && is_file($filename) && is_writeable($filename)) {
        // 如果是PHP文件 并且可写 则进行压缩
    	$contents  =  file_get_contents($filename);
        // 判断文件是否已经被加密
        $pos = strpos($contents,'/*Protected by TOPThink Cryptation*/');
        if( false === $pos  || $pos>100 ) {
            if($strip) {
                // 去除PHP文件注释和空白，减少文件大小
                $contents  =  php_strip_whitespace($filename);
            }
            // 变量混淆
            $contents   =  var_mix($contents);
            // 去除PHP头部和尾部标识
            $contents = substr($contents, 5);
            if ('?>' == substr($contents, -2))
                $contents = substr($contents, 0, -2);
            // 对文件内容进行加密存储
            $encode   =  base64_encode(gzdeflate($contents));
            $encode   = '<?php'." /*Protected by TOPThink Cryptation*/\n\$cryptCode='".$encode."';eval(gzinflate(base64_decode(\$cryptCode)));\n /*Reverse engineering is illegal and strictly prohibited - (C) TOPThink Cryptation 2012*/\n?>";
            // 重新写入加密内容
            return file_put_contents($filename,$encode);
        }
    }
    return false;
}

function var_mix($content) {
    return $content;
}
/**
 +----------------------------------------------------------
 * 压缩文件夹下面的PHP文件
 +----------------------------------------------------------
 * @param string $path 路径
 +----------------------------------------------------------
 * @return void
 +----------------------------------------------------------
 */
function encode_dir($path) {
    if(substr($path, -1) != "/")    $path .= "/";
    $dir=glob($path."*");
    foreach($dir as $key=>$val) {
        if(is_dir($val)) {
            encode_dir($val);
        } else{
            encode_file_contents($val);
        }
    }
}

/**
 +----------------------------------------------------------
 * 对查询结果集进行排序
 +----------------------------------------------------------
 * @access public
 +----------------------------------------------------------
 * @param string $field 排序的字段名
 * @param array $sortby 排序类型 asc arsort natcaseror
 * @param array $list 查询结果
 +----------------------------------------------------------
 * @return array
 +----------------------------------------------------------
 */
function sort_by($list,$field, $sortby='asc',$limit=0 ) {
   if(is_array($list)){
       $refer = $resultSet = array();
       foreach ($list as $i => $data)
           $refer[$i] = &$data[$field];
       switch ($sortby) {
           case 'asc': // 正向排序
                asort($refer);
                break;
           case 'desc':// 逆向排序
                arsort($refer);
                break;
           case 'nat': // 自然排序
                natcasesort($refer);
                break;
       }
       $i   = 1;
       $count = $limit>0?true:false;
       if($limit>0) $count   =  true;
       foreach ( $refer as $key=> $val){
           $resultSet[] = &$list[$key];
           $i++;
           if($count && $i>$limit) {
               break;
           }
       }
       return $resultSet;
   }
   return false;
}

// 自动转换字符集 支持数组转换
function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        //如果编码相同或者非字符串标量则不转换
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}

// 保存标签
function saveTag($data){
    if(empty($data['tags'])) {
        return ;
    }
    $Tag = M("Tag");
    $Tagged   = M("Tagged");
    // 记录已经存在的标签
    $exists_tags  = $Tagged->where("module='{$data['module']}' and record_id={$data['id']}")->getField("id,tag_id");
    $Tagged->where("module='{$data['module']}' and record_id={$data['id']}")->delete();
    $tags = explode(' ',$data['tags']);
    foreach($tags as $key=>$val) {
        $val  = trim($val);
        if(!empty($val)) {
            $tag =  $Tag->where("module='{$data['module']}' and name='{$val}'")->find();
            if($tag) {
                // 标签已经存在
                //if(!in_array($tag['id'],$exists_tags)) {
                    $Tag->setInc('count','id='.$tag['id']);
                //}
            }else {
                // 不存在则添加
                $tag = array();
                $tag['name'] =  $val;
                $tag['count']  =  1;
                $tag['module']   =  $data['module'];
                $result  = $Tag->add($tag);
                $tag['id']   =  $result;
            }
            // 记录tag关联信息
            $t = array();
            $t['user_id'] = $_SESSION[C('USER_AUTH_KEY')];
            $t['module']   = $data['module'];
            $t['record_id'] =  $data['id'];
            $t['create_time']  = time();
            $t['tag_id']  = $tag['id'];
            $Tagged->add($t);
        }
    }
}

function getStaff($id) {
    return model('staff')->getFieldById($id,'name');
}

function getCate() {
    return M('Cate')->where('status=1 AND level=1')->order('sort')->getField('id,id,title',':');
}

// 导入别名定义
alias_import(array(
    'Page'                 =>   C('LIB_PATH').'ORG/Page.class.php',
    )
);