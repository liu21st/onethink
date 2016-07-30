<?php
class plus_dir_tree extends iswaf {
    public static $fileinfo = array(),$list_rulers = array(), $tree=array(), $rootdir='', $dircount=0, $filecount=0, $read=false;
    private static $dircount_now=0, $filecount_now=0;
    function dir_tree($dir,$listfile = 0, $limit=100,$list_rulers=array()) {     
        $dir = $dir.'/';
        $dir = str_replace('//','/',$dir);
        self::$rootdir=$dir;
        self::filecount($dir);
        self::$list_rulers = $list_rulers;
        self::limittree($dir,$listfile,$limit,$rulers);
    }
    
    function limittree($dir, $listfile=1, $limit=100){
        $dir = $dir.'/';
        $dir = str_replace('//','/',$dir);
        foreach(glob($dir.'*') as $file){
            if(self::markedfile()=='' || self::markedfile()==$file) self::$read=true;
            if(is_dir($file)){
                self::$dircount_now++;
                if($listfile==0){
                    if(!in_array($file,self::$tree)){
                        self::$tree[] = $file;
                    }
                    if(self::$dircount_now==self::$dircount){
                        self::threadend('',true);
                    }
                }
                self::limittree($file,$listfile,$limit);
            }else{
                if(self::filext($file)=='php'){
                    self::$filecount_now++;
                    if(self::$read && $file!=self::markedfile() && !in_array($file,self::$tree)){
                        self::$tree[]=$file;
                        self::$fileinfo[] = array('m'=>substr(md5_file($file),8,16),'n'=>substr($file,strlen(self::$rootdir),strlen($file)-strlen(self::$rootdir)));
                    }
                    if(self::$filecount_now==self::$filecount) self::threadend('',true);
                    if(count(self::$tree)>=$limit){
                        self::threadend($file);
                    }
                }
            }
        }
    }
    
    
    function markfile($file){
        self::create_file(iswaf_database.'markfile.txt',$file);
    }
    function markedfile(){
        if(!file_exists(iswaf_database.'markfile.txt')) return '';
        $content = self::readfile(iswaf_database.'markfile.txt');
        return $content;
    }
    
    function filecount($dir){
        $dir = $dir.'/';
        $dir = str_replace('//','/',$dir);
        foreach(glob($dir.'*') as $file){
            if(is_dir($file)) {
                self::$dircount++;
                self::filecount($file,$listfile);
            }else{
                if(self::filext($file)=='php') self::$filecount++;
            }
        }
    }
    
    function list_functions($file) {
        $functions = $rulers = array();
        $tmp = self::readfile($file);
        if(empty(self::$list_rulers)) {
        
            $rulers[] = '/\b([\[\]\'"\w\_\.]+)\s*\(/is';
            $rulers[] = '/\b([\w\_]+)\s*[\'"].*[\'|"].*\;/is';
            $rulers[] = '/(\$[\[\]\'"\w\_\.]+)\(/is';
        } else {
            $rulers = self::$list_rulers;
        }
        foreach($rulers as $ruler) {
            preg_match_all($ruler,$tmp,$a);
            if(is_array($a[1])) {
                $functions = array_unique(array_merge_recursive($functions,$a[1]));
            }
        }
        foreach($functions as $id=>$function) {
            if(substr($function,-1) == '.') unset($functions[$id]);
        }
        return $functions;
    }
    function threadend($file,$allend=false){
        $file=trim($file);
        if($allend){
            self::$fileinfo['end']=1;
            self::markfile('');
        }else{
            self::markfile($file);
        }
        if($_GET['debug']==1){
            print_r(self::$fileinfo);
        }else{
            echo self::authcode(serialize(self::$fileinfo),'ENCODE');
        }
        exit;
    }
}
?>