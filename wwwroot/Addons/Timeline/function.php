<?php

if(!function_exists('preview_pic')){

	function preview_pic($cover_id, $height=50){
	    $src = get_cover($cover_id,'path');
	    return <<<str
<div class="upload-img-box">
	<div class="upload-pre-item">
		<img src="{$src}"/>
	</div>
</div>
str;
	}

}

function format_date($time){
	return date('Y,m,d', $time);
}
