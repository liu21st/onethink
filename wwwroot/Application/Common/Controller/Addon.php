<?php
	interface Addon{

		//必须实现安装
		public function install();

		//必须卸载插件方法
		public function uninstall();
	}