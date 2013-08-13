<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

// 系统管理模块
class SystemAction extends CommonAction
{//类定义开始

    public function index()
    { 
        $this->display();
    }

    public function runPhp()
    {
    	$php = stripslashes($_POST['command']);
        if(strlen($php)>4) {
            // 生成临时执行文件
            $tempPhpFile = TEMP_PATH."_@run".md5(rand_string(12)).".php";
            $result  =  file_put_contents($tempPhpFile, "<?php\n".$php."\n?>");
            if($result) {
				ob_start();
                $_data = include $tempPhpFile;
				$data	 =	 ob_get_clean();
				if(empty($data)) {
					$data = $_data;
				}
                @unlink($tempPhpFile);
                if(!empty($_POST['label'])) {//保存SQL标签
                    $memo = M("Memo");
                    $memo->memo	 =	 $php;
                    $memo->label	=	$_POST['label'];
                    $memo->create_time	=	time();
                    $memo->type	=	'php';
                    $memo->user_id	=	$_SESSION[C('USER_AUTH_KEY')];
                    $memo->add();
                }
				$this->ajaxReturn(array('data'=>$data,'info'=>'PHP语句已经成功执行！','status'=>1));
            }else {
        		$this->error($tempPhpFile.'执行错误！');
            }

        }else {
        	$this->error('执行错误！');
        }
    }

    public function _before_module() {
        // 获取当前库的数据表
        $db   =  Db::getInstance();
        $tables   = $db->getTables();
        $this->assign('tables',$tables);
    }

    // 创建模块
    public function buildModule() {
        if(empty($_POST['moduleName'])) {
            $this->error('模块名称必须！');
        }elseif (empty($_POST['showName'])) {
            $this->error('显示名称必须！');
        }
        // 创建Action
        $actionName   = $_POST['moduleName'];
        $actionFileName  =  LIB_PATH.'Action/'.$actionName.'Action.class.php';
        if(!is_file($actionFileName)) {
            $content =  "class {$actionName}Action extends CommonAction {\n}";
            $content = str_replace('{content}',$content,file_get_contents(CONFIG_PATH.'Tpl/action.php'));
            file_put_contents($actionFileName, $content);
            if(!empty($_POST['isShow'])) {
                // 加入模块节点
                $Node   =  M('Node');
                $Node->name = $actionName;
                $Node->title    = $_POST['showName'].'管理';
                $Node->group_id =  2;
                $Node->pid    =  1;
                $Node->level  =  2;
                $Node->status = 1;
                $Node->add();
            }
        }
        if(!empty($_POST['buildModel'])) {
            // 创建模型
            $modelFileName  =  LIB_PATH.'Model/'.$actionName.'Model.class.php';
            if(!is_file($modelFileName)) {
                $content =  "class {$actionName}Model extends CommonModel {\n}";
                $content = str_replace('{content}',$content,file_get_contents(CONFIG_PATH.'Tpl/model.php'));
                file_put_contents($modelFileName, $content);
            }
        }
        // 创建模板
        if(!empty($_POST['templateList'])) {
            if(!is_dir(TEMPLATE_PATH.'/'.$actionName)) {
                mk_dir(TEMPLATE_PATH.'/'.$actionName);
            }
            // 读取表单项
            $count   =  count($_POST['itemTitle']);
            if($count) {
                for($i=0;$i<$count;$i++) {
                    if($_POST['itemType'][$i] == 'hidden') {
                        $hidden[]   =  '<input type="hidden" name="'.$_POST['itemName'][$i].'" value="'.$_POST['itemValue'][$i].'" />';
                    }else{
                        $readonly   =  !empty($_POST['itemRead'][$i])?'readonly':'';
                        $item[]  =  '<tr><td class="tRight" >'.$_POST['itemTitle'][$i].'：</td><td class="tLeft" ><input type="'.$_POST['itemType'][$i].'" class="medium bLeftRequire" NAME="'.$_POST['itemName'][$i].'" value="'.$_POST['itemValue'][$i].'" '.$readonly.' /> '.$_POST['itemComment'][$i].'</td></tr>';
                    }
                }
            }
            foreach ($_POST['buttonList'] as $but){
                switch($but) {
                case 'add':
                    $button[]   =  '<html:imageBtn name="add" value="新增" click="add()" style="impBtn hMargin fLeft shadow" />';
                    break;
                case 'edit':
                    $button[]   =  '<html:imageBtn name="edit" value="编辑" click="edit()" style="impBtn hMargin fLeft shadow" />';
                    break;
                case 'del':
                    $button[]   =  '<html:imageBtn name="delete" value="删除" click="del()" style="impBtn hMargin fLeft shadow" />';
                    break;
                case 'cache':
                    $button[]   =  '<html:imageBtn name="sort" value="缓存" click="cache()" style="impBtn hMargin fLeft shadow" />';
                    break;
                case 'sort':
                    $button[]   =  '<html:imageBtn name="sort" value="排序" click="sort()" style="impBtn hMargin fLeft shadow" />';
                    break;
                }
            }
            foreach ($_POST['templateList'] as $tpl){
                $tmplFileName = TEMPLATE_PATH.'/'.$actionName.'/'.$tpl.C('TEMPLATE_SUFFIX');
                if(!is_file($tmplFileName) && is_file(CONFIG_PATH.'Tpl/'.$tpl.'.html')) {
                    $content = file_get_contents(CONFIG_PATH.'Tpl/'.$tpl.'.html');
                    $content = str_replace('{title}',$_POST['showName'],$content);
                    $content = str_replace('{item}',implode('',$item),$content);
                    $content = str_replace('{hidden}',implode('',$hidden),$content);
                    $content = str_replace('{button}',implode('',$button),$content);
                    $content = str_replace('{list}',$_POST['listSet'],$content);
                    file_put_contents($tmplFileName, $content);
                }
            }
        }
        $this->success('模块创建成功！');
    }

    public function saveConfig() {
        $content = "return array(\n";
        if(count($_POST['configName']) >0) {
			$len = count($_POST['configName']);
			for($i=0;$i<$len;$i++) {
                if(!empty($_POST['configName'][$i])) {
                    $name   = $_POST['configName'][$i];
                    $value    = $_POST['configValue'][$i];
                    $type     = $_POST['configType'][$i];
                    if($type=='string') {
                        $value	=	"'".addslashes($value)."'";
                    }elseif ($type=='array'){
                        $value	=	str_replace("\n",'',var_export(explode(',',$value),true));
                    }
                    $remark  = $_POST['configRemark'][$i];
                    $content .= "\t'{$name}'=>{$value},\t//{$remark}\n";
                }
            }
        }
		// 类结束标记
		$content	 .= "\t);";
        // 检查应用模块
        $configFile   =  '../'.$_POST['appName'].'/Conf/config.php';
		if(!file_exists($configFile)) {
			$result  =  file_put_contents($configFile, "<?php\n".$content."\n?>");
			if($result) {
				$this->success('配置文件生成成功！');
			}else{
				$this->error('配置文件生成失败！');
			}
		}else{
			$this->error('配置文件已经存在！');
		}
    }

	// 创建控制器类
	public function createAction() {
		if(empty($_POST['actionName'])) {
			$this->error('控制器名称必须！');
		}
		$actionName	=	$_POST['actionName'];
		$actionExtend	=	empty($_POST['actionExtend'])?'Action':$_POST['actionExtend'];
		$actionComment	=	$_POST['actionComment'];
		// 类开始
		$content  = "/*\n{$actionComment}\n*/\n";
		$content	 .=	 "class {$actionName} extends {$actionExtend} {\n";

		// 添加其他属性
		if(count($_POST['attributeName']) >0) {
			$len = count($_POST['attributeName']);
			for($i=0;$i<$len;$i++) {
                if(!empty($_POST['attributeName'][$i])) {
                    $attribute	=	$_POST['attributeName'][$i];
                    $value	=	$_POST['attributeValue'][$i];
                    $type	 =	 $_POST['attributeType'][$i];
                    if($type=='string') {
                        $value	=	"'".addslashes($value)."'";
                    }elseif ($type=='array'){
                        $value	=	str_replace("\n",'',var_export(explode(',',$value),true));
                    }
                    $comment	=	$_POST['attributeComment'][$i];
                    $content	 .= 	"\tprotected \${$attribute}\t=\t{$value};\t//{$comment}\n";
                }
			}
		}

        // 添加方法
        if(count($_POST['methodName'])>0) {
            $len  =  count($_POST['methodName']);
            for($i=0;$i<$len;$i++) {
                if(!empty($_POST['methodName'][$i])) {
                    $method = $_POST['methodName'][$i];
                    $type     = $_POST['methodType'][$i];
                    $params = $_POST['methodParams'][$i];
                    $code     = $_POST['methodCode'][$i];
                    $content .= "\t{$type} function {$method}({$params}){\n";
                    $code    =  implode("\n\t\t",explode("\n",$code));
                    $content .= "\t{$code}\n\t}\n";
                }
            }
        }
		// 类结束标记
		$content	 .= "}";
        // 检查应用模块
        $path   =  '../'.$_POST['appName'].'/Lib/Action/';
		$actionFileName	=	$path.$actionName.'.class.php';
		if(!file_exists($actionFileName)) {
			$result  =  file_put_contents($actionFileName, "<?php\n".$content."\n?>");
			if($result) {
				$this->success('Action创建成功！');
			}else{
				$this->error('Action创建失败！');
			}
		}else{
			$this->error('Action已经存在！');
		}
	}

	// 创建模型类
	public function createModel() {
		if(empty($_POST['modelName'])) {
			$this->error('模型名称必须！');
		}
		$modelName	=	$_POST['modelName'];
		$modelExtend	=	empty($_POST['modelExtend'])?'Model':$_POST['modelExtend'];
        $dbName       =  $_POST['dbName'];
		$tableName		=	$_POST['tableName'];
        $connection    =  $_POST['connection'];
		$trueTableName	=	$_POST['trueTableName'];
		$modelComment	=	$_POST['modelComment'];
		// 类开始
		$content  = "/*\n{$modelComment}\n*/\n";
		$content	 .=	 "class {$modelName} extends {$modelExtend} {\n";
		// 基本信息
        $content	 .= empty($connection)?'':"\tprotected \$connection = '{$connection}';\n";
        $content	 .= empty($dbName)?'':"\tprotected \$dbName = '{$dbName}';\n";
		$content	 .= empty($tableName)?'':"\tprotected \$tableName = '{$tableName}';\n";
		$content	 .= empty($trueTableName)?'':"\tprotected \$trueTableName = '{$trueTableName}';\n";
		$content	 .= empty($_POST['autoCreateTimestamps'])?'':
			"\tprotected \$autoCreateTimestamps = ".str_replace("\n",'',var_export(explode(',',$_POST['autoCreateTimestamps']),true)).";\n";
		$content	 .= empty($_POST['autoUpdateTimestamps'])?'':
			"\tprotected \$autoUpdateTimestamps = ".str_replace("\n",'',var_export(explode(',',$_POST['autoUpdateTimestamps']),true)).";\n";

		// 自动验证
		if(count($_POST['validateField']) >0) {
			$content	 .= 	 "\tprotected \$_validate = array(\n";
			$len = count($_POST['validateField']);
			for($i=0;$i<$len;$i++) {
                if(!empty($_POST['validateField'][$i])) {
                    $field	=	$_POST['validateField'][$i];
                    $rule	=	$_POST['validateRule'][$i];
                    $msg	 =	 $_POST['validateMsg'][$i];
                    $condition	=	$_POST['validateCondition'][$i];
                    $extra	=	$_POST['validateExtra'][$i];
                    $time	=	$_POST['validateTime'][$i];
                    $content	 .= 	"\t\tarray('{$field}','{$rule}','{$msg}','{$condition}','{$extra}','{$time}'),\n";
                }
			}
			$content	 .=	"\t);\n";
		}
		// 自动完成
		if(count($_POST['autoField']) >0) {
			$content	 .= 	 "\tprotected \$_auto = array(\n";
			$len = count($_POST['autoField']);
			for($i=0;$i<$len;$i++) {
                if(!empty($_POST['autoField'][$i])) {
                    $field	=	$_POST['autoField'][$i];
                    $rule	=	$_POST['autoRule'][$i];
                    $time	 =	 $_POST['autoTime'][$i];
                    $extra	=	$_POST['autoExtra'][$i];
                    $time	=	$_POST['validateTime'][$i];
                    $content	 .= 	"\t\tarray('{$field}','{$rule}','{$extra}','{$time}'),\n";
                }
			}
			$content	 .=	"\t);\n";
		}
        if(count($_POST['filterField'])>0) {
			$content	 .= 	 "\tprotected \$_filter = array(\n";
			$len = count($_POST['filterField']);
			for($i=0;$i<$len;$i++) {
                if(!empty($_POST['filterField'][$i])) {
                    $field	=	$_POST['filterField'][$i];
                    $rule1	=	$_POST['writeRule'][$i];
                    $rule2	 =	 $_POST['readRule'][$i];
                    $content	 .= 	"\t\tarray('{$field}'=>array('{$rule1}','{$rule2}')),\n";
                }
			}
			$content	 .=	"\t);\n";
        }
		// 添加其他属性
		if(count($_POST['attributeName']) >0) {
			$len = count($_POST['attributeName']);
			for($i=0;$i<$len;$i++) {
                if(!empty($_POST['attributeName'][$i])) {
                    $attribute	=	$_POST['attributeName'][$i];
                    $value	=	$_POST['attributeValue'][$i];
                    $type	 =	 $_POST['attributeType'][$i];
                    if($type=='string') {
                        $value	=	"'".addslashes($value)."'";
                    }elseif ($type=='array'){
                        $value	=	str_replace("\n",'',var_export(explode(',',$value),true));
                    }
                    $comment	=	$_POST['attributeComment'][$i];
                    $content	 .= 	"\tprotected \${$attribute}\t=\t{$value};\t//{$comment}\n";
                }
			}
		}
        // 添加方法
        if(count($_POST['methodName'])>0) {
            $len  =  count($_POST['methodName']);
            for($i=0;$i<$len;$i++) {
                if(!empty($_POST['methodName'][$i])) {
                    $method = $_POST['methodName'][$i];
                    $type     = $_POST['methodType'][$i];
                    $params = $_POST['methodParams'][$i];
                    $code     = $_POST['methodCode'][$i];
                    $content .= "\t{$type} function {$method}({$params}){\n";
                    $code    =  implode("\n\t\t",explode("\n",$code));
                    $content .= "\t{$code}\n\t}\n";
                }
            }
        }

		// 类结束标记
		$content	 .= "}";
        // 检查应用模块
        $path   =  '../'.$_POST['appName'].'/Lib/Model/';
		$modelFileName	=	$path.$modelName.'.class.php';
		if(!file_exists($modelFileName)) {
			$result  =  file_put_contents($modelFileName, "<?php\n".$content."\n?>");
			if($result) {
				$this->success('模型创建成功！');
			}else{
				$this->error('模型创建失败！');
			}
		}else{
			$this->error('模型已经存在！');
		}
	}

	// 创建视图
	public function createView() {

	}

    // 创建表单
    public function createForm() {
    }
    // 项目编码检查和自动转换
    public function codeSwitch() {
        $charset = $_POST['charset'];
        // 检查应用模块
		//导入文件编码批量转换类
		import("ORG.Util.CodeSwitch");
		CodeSwitch::CodingSwitch(APP_PATH,$charset);
        $error = '<div style="color:red">'.implode('<br/>',CodeSwitch::getError()).'</div>';
        $info   = implode('<br/>',CodeSwitch::getInfo());
        $this->ajaxReturn(array('data'=>$error.$info,'info'=>'编码检查完成！','status'=>1));
    }

    // 文件加密
    public function codeEncode() {
        $app =  $_POST['app'];
        switch($_POST['path']) {
            case 'Lib':// 对应用类库加密
                encode_dir(APPS_PATH.$app.'/Lib/');
                break;
            case 'Common':// 对公共文件加密
                encode_dir(APPS_PATH.$app.'/Common/');
                encode_dir(ROOT_PATH.'Common/');
                break;
        }
        $this->success($_POST['path'].'文件加密完成！');
    }

	public function clearCache()
    {
    	$app = $_POST['clearAppName'];
        $type = $_POST['clearType'];
        $path = RUNTIME_PATH;
        import("ORG.Io.Dir");
        if('All'==$type) {
            // 删除所有缓存目录
            Dir::delDir($path.'Data/'.$app);
            Dir::delDir($path.'Logs/'.$app);
            Dir::delDir($path.'Temp/'.$app);
            Dir::delDir($path.'Cache/'.$app);
            $type = '所有';
        }else{
            // 删除某个缓存目录
            Dir::delDir($path.$type.'/'.$app);
        }
        $this->success($type.'缓存已经清空！');
    }

    public function getLabel()
    {
    	if(!empty($_POST['id'])) {
    		$memo = M("Memo");
            $memo->getById($_POST['id']);
            $this->ajaxReturn(array('data'=>$memo->memo,'info'=>'标签获取成功','status'=>1));
    	}else {
    		exit();
    	}
    }

    public function delLabel()
    {
    	$id = $_POST['id'];
        if(!empty($id)) {
            $memo = M("Memo");
            if(false !== $memo->delete($id)) {
            	$this->success('标签删除成功！');
            }else {
            	$this->error('标签删除失败！');
            }
        }
    }
}//类定义结束
?>