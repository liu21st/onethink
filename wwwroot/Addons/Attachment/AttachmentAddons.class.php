<?php
	class AttachmentAddons extends Addons{

		public function install(){
			return true;
		}

		public function uninstall(){
			return true;
		}

		/* 页面头部输出CSS文件 */
		static public function pageHeader(){
			// $addons = addons('Attachment');
			if(CONTROLLER_NAME == 'Article' && ACTION_NAME == 'edit' && I('get.model') != 2){
				$addons->display('uploadify_style');
				// $this->display('Uploadify/style');
			} elseif(CONTROLLER_NAME == 'Article' && ACTION_NAME == 'detail') {
				$addons->display('View/Article/style');
				// $this->display('Article/style');
			}
		}
	}