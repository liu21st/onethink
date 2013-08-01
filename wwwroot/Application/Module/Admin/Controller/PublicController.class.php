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

class PublicController extends CommonController {

    // 检查用户是否登录
    protected function checkUser() {
        if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->error('没有登录',C('USER_AUTH_GATEWAY'));
        }
    }

    // 顶部页面
    public function top() {
        $this->checkUser();
        C('SHOW_RUN_TIME',false);			// 运行时间显示
        C('SHOW_PAGE_TRACE',false);
        if(!isset($_SERVER['_GROUP_ACCESS_LIST_'])) {
            if($_SESSION['administrator']) {
                $_SERVER['_GROUP_ACCESS_LIST_']   =  M('Menu')->where('level=1 and status=1')->order('sort')->field('id,name,title,url')->select();
            }else{
                $map['level']   = 1;
                $map['status'] = 1;
                $_SERVER['_GROUP_ACCESS_LIST_']   =   ThinkAcl::getRoleAccessList(4,'','id,name,title,url',$map);
            }
        }
		$this->assign('nodeGroupList',$_SERVER['_GROUP_ACCESS_LIST_']);
		$this->display();
	}

    public function drag(){
        C('SHOW_PAGE_TRACE',false);
		C('SHOW_RUN_TIME',false);			// 运行时间显示
        $this->display();
    }
	// 尾部页面
	public function footer() {
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
		$this->display();
	}

	// 菜单页面
	public function menu() {
        $this->checkUser();
		C('SHOW_RUN_TIME',false);			// 运行时间显示
		C('SHOW_PAGE_TRACE',false);
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
            //显示菜单项
            if(!isset($_SERVER['_MENU_ACCESS_LIST_'])) {
                if($_SESSION['administrator']) { // 超级管理员
                    $_SERVER['_MENU_ACCESS_LIST_']  =   M('Menu')->field('id,name,title,pid,is_show,url')->where('level=2 AND status=1')->order('sort')->select();
                }else{
                    $map['level']   =   2;
                    $map['status']  =   1;
                    $_SERVER['_MENU_ACCESS_LIST_']   =   ThinkAcl::getRoleAccessList(4,'','id,name,title,pid,is_show,url',$map);
                }
            }
            $menu   =   $_SERVER['_MENU_ACCESS_LIST_'];
            $group   = !empty($_GET['tag'])?$_GET['tag']:0;
            $this->assign('menuTag',$group);
            $this->assign('menu',$menu);
		}
		$this->display();
	}

    // 后台首页 查看系统信息
    public function main() {
        $this->checkUser();
        $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
            'ThinkPHP版本'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->info  =  $info;
        $this->display();
    }

	// 用户登录页面
	public function login() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display();
		}else{
			redirect(__MODULE__.'/');
		}
	}

	public function index()	{
		//如果通过认证跳转到首页
		redirect(__MODULE__.'/');
	}

	// 用户登出
    public function logout() {
        if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			$loginId	=	$_SESSION['loginId'];
			unset($_SESSION);
			session_destroy();
			//保存登出记录
			$login   =   M('Login');
            $login->out_time	=	time();
			$login->id	=		$loginId;
            $login->save();
            $this->success('登出成功！',__URL__.'/login/');
        }else {
            $this->error('已经登出！',C('USER_AUTH_GATEWAY'));
        }
    }

	// 登录检测
	public function checkLogin() {
		if(empty($_POST['account'])) {
			$this->error('帐号不能为空！');
		}elseif (empty($_POST['password'])){
			$this->error('密码必须！');
		}elseif (''===trim($_POST['verify'])){
			$this->error('验证码必须！');
		}
        //生成认证条件
		$map['account']	= $_POST['account'];
        $map["status"]	=	array('gt',0);
        if(C('ADV_LOGIN_VERIFY')) {
            // 登录验证码获取
            $verifyCodeStr   = $_POST['verify'];
            $verifyCodeNum   = array_flip($_SESSION['verifyCode']);
            $len	=	strlen(trim($_POST['verify']));
            for($i=0; $i<$len; $i++) {
                $verify .=  $verifyCodeNum[$verifyCodeStr[$i]];
            }
        }else{
            if(session('verify') != md5($_POST['verify'])) {
                $this->error('验证码错误！');
            }
        }
        $authInfo   =   M('Auth')->where($map)->find();
        //使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }else {
            if($authInfo['password'] != md5($_POST['password'])) {
            	$this->error('密码错误！');
            }
            if( C('ADV_LOGIN_VERIFY') && $authInfo['verify'] != $verify) {
            	$this->error('验证码错误！');
            }
            if(!empty($authInfo['max_login']) && $authInfo['login_count'] > $authInfo['max_login']) {
                $this->error('超过限制的登录次数！');
            }
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
            $_SESSION['loginUserName']		=	$authInfo['nickname'];
            $_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
			$_SESSION['login_count']	=	$authInfo['login_count'];
            if($authInfo['account']=='admin') {// 超级管理员
                $_SESSION['administrator']		=	true;
            }
            // 保存登录信息
			$User	=	M('Auth');
            $data = array();
			$data['id']	=	$authInfo['id'];
			$data['last_login_time']	=	NOW_TIME;
			$data['login_count']	=	array('exp','(login_count+1)');
			$data['last_login_ip']	=	get_client_ip();
			$User->save($data);
			// 保存登录日志
			$login   =   M("Login");
            $login->user_id	=	$authInfo['id'];
            $login->in_time	=	NOW_TIME;
            $login->login_ip	=	get_client_ip();
            $loginId    =   $login->add();
            $_SESSION['loginId']		=	$loginId;
			$this->success('登录成功！',__APP__.'/');
		}
	}

	public function profile() {
		$this->checkUser();
		$User	 =	 M("Auth");
		$vo	=	$User->getById($_SESSION[C('USER_AUTH_KEY')]);
		$this->assign('vo',$vo);
		$this->display();
	}

	// 修改资料
	public function change() {
		$this->checkUser();
		$User	 =	 M("Auth");
		if(!$User->create()) {
			$this->error($User->getError());
		}
		$result	=	$User->save();
		if(false !== $result) {
			$this->success('资料修改成功！');
		}else{
			$this->error('资料修改失败!');
		}
	}

    // 更换密码
    public function changePwd() {
		$this->checkUser();
        //对表单提交处理进行处理或者增加非表单数据
		if(md5($_POST['verify'])	!= $_SESSION['verify']) {
			$this->error('验证码错误！');
		}
		$map	=	array();
        $map['password']= md5($_POST['oldpassword']);
        if(isset($_POST['account'])) {
            $map['account']	 =	 $_POST['account'];
        }elseif(isset($_SESSION[C('USER_AUTH_KEY')])) {
            $map['id']		=	$_SESSION[C('USER_AUTH_KEY')];
        }
        //检查用户
        $User    =   M("Auth");
        if(!$User->where($map)->field('id')->find()) {
            $this->error('旧密码不符或者用户名错误！');
        }else {
			$User->password	=	pwdHash($_POST['password']);
			$User->save();
			$this->success('密码修改成功！',__APP__.'/Public/main');
         }
    }

	// 验证码显示
    public function verify() {
        import("ORG.Util.Image");
        if(isset($_REQUEST['adv'])) {
        	Image::showAdvVerify();
        }else {
            $length  =  C('VERIFY_CODE_LENGTH');
            if(strpos($length,',')) {
                $rand = explode(',',$length);
                $length  =  floor(mt_rand((int)$rand[0],(int)$rand[1]));
            }
            Image::buildImageVerify($length?$length:4);
        }
    }
}