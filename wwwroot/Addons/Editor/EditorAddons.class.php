<?php
	class EditorAddons extends Addons{

		public function install(){
			return true;
		}

		public function uninstall(){
			return true;
		}

		static public function documentEditFormContent($data){
			$addons = addons('Editor');
			$addons->assign('data', $data);
			$addons->assign('config', $addons->getConfig());
			$addons->display('content');
		}
	}