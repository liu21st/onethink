<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: yangweijie <yangweijiester@gmail.com> <code-tech.diandian.com>
// +----------------------------------------------------------------------

namespace Addons\AdaptiveImages\Controller;
use Home\Controller\AddonsController;

class AdaptiveImagesController extends AddonsController{

	public function view(){
		error_reporting(0);
		$config = get_addon_config('AdaptiveImages');
		$document_root  = __ROOT__;
		$resolutions = $this->resolutions = explode(',', trim($config['resolutions']));
		$this->cache_path = $config['cache_path'];
		$this->jpg_quality = $config['jpg_quality'];
		$this->sharpen = $config['sharpen'];
		$this->watch_cache = $config['watch_cache'];
		$this->browser_cache = $config['browser_cache'];

		$requested_uri  = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH);
		$requested_uri = str_replace($document_root, '', $requested_uri);
		$requested_file = basename($requested_uri);
		$this->source_file = $source_file = '.'.$requested_uri;
		$resolution = FALSE;

		// 检测源文件是否存在
		if (!file_exists($source_file)) {
			header("Status: 404 Not Found");
			exit();
		}else{
			if($config['status'] == 0)
				$this->sendImage($this->source_file);
		}

		/* 检测环境里是否有GD库 */
		if (!extension_loaded('gd')) {
			if (!function_exists('dl') || !dl('gd.so')) {
				trigger_error('你必须启用 GD 扩展来使用Adaptive Images', E_USER_WARNING);
				$this->sendImage($source_file, $this->browser_cache);
			}
		}

		//$cache_path是否已经存在?
		if (!is_dir($this->cache_path)) { // no
			if (!@mkdir($this->cache_path, 0755, true)) {
				if (!is_dir($this->cache_path)) {
					$this->sendErrorImage("Failed to create cache directory at: $this->cache_path");
				}
			}
		}

		/* 检查是否合法的cookie */
		if (isset($_COOKIE['resolution'])) {
		  $cookie_value = $_COOKIE['resolution'];

		  // 格式是否正确 [whole number, comma, potential floating number]
		  if (! preg_match("/^[0-9]+[,]*[0-9\.]+$/", "$cookie_value")) { // no it doesn't look valid
		    setcookie("resolution", "$cookie_value", time()-100); // delete the mangled cookie
		  } else { // the cookie is valid, do stuff with it
		    $cookie_data   = explode(",", $_COOKIE['resolution']);
		    $client_width  = (int) $cookie_data[0]; // the base resolution (CSS pixels)
		    $total_width   = $client_width;
		    $pixel_density = 1; // set a default, used for non-retina style JS snippet
		    if (@$cookie_data[1]) { // the device's pixel density factor (physical pixels per CSS pixel)
		      $pixel_density = $cookie_data[1];
		    }

		    rsort($resolutions); // make sure the supplied break-points are in reverse size order
		    $resolution = $resolutions[0]; // by default use the largest supported break-point

		    // if pixel density is not 1, then we need to be smart about adapting and fitting into the defined breakpoints
		    if($pixel_density != 1) {
		      $total_width = $client_width * $pixel_density; // required physical pixel width of the image

		      // the required image width is bigger than any existing value in $resolutions
		      if($total_width > $resolutions[0]){
		        // firstly, fit the CSS size into a break point ignoring the multiplier
		        foreach ($resolutions as $break_point) { // filter down
		          if ($total_width <= $break_point) {
		            $resolution = $break_point;
		          }
		        }
		        // now apply the multiplier
		        $resolution = $resolution * $pixel_density;
		      }
		      // the required image fits into the existing breakpoints in $resolutions
		      else {
		        foreach ($resolutions as $break_point) { // filter down
		          if ($total_width <= $break_point) {
		            $resolution = $break_point;
		          }
		        }
		      }
		    }
		    else { // pixel density is 1, just fit it into one of the breakpoints
		      foreach ($resolutions as $break_point) { // filter down
		        if ($total_width <= $break_point) {
		          $resolution = $break_point;
		        }
		      }
		    }
		  }
		}

		//是否是手机
		if(!$this->is_mobile()){
			$is_mobile = FALSE;
		} else {
			$is_mobile = TRUE;
		}

		/* 没有响应式断点发现 (没有合法的cookie) */
		if (!$resolution) {
		  	// 我们发送给手机端最小的宽度，非手机端最大的
			$resolution = $is_mobile ? min($resolutions) : max($resolutions);
		}

		if(substr($requested_uri, 0,1) == "/") {
		  $requested_uri = substr($requested_uri, 1);
		}

		$cache_file = "$this->cache_path/$resolution/".$requested_uri;

		/* 使用响应值作为路径变量，并且检测同名图片是否存在其中 */
		if (file_exists($cache_file)) {
			if ($this->watch_cache) { //监视原图改变的话
				$cache_file = $this->refreshCache($source_file, $cache_file, $resolution);
			}

			$this->sendImage($cache_file, $this->browser_cache);
		}

		/* 原图存在无缓存时创建缓存: */
		$file = $this->generateImage($source_file, $cache_file, $resolution);
		$this->sendImage($file, $this->browser_cache);

	}

	//是否是手机端
	private function is_mobile(){
		// return true;
		$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
		return strpos($userAgent, 'mobile');
	}

	/* helper function: Send headers and returns an image. */
	private function sendImage($filename, $browser_cache = '604800') {
		$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		if (in_array($extension, array('png', 'gif', 'jpeg'))) {
			header("Content-Type: image/".$extension);
		} else {
			header("Content-Type: image/jpeg");
		}
		header("Cache-Control: private, max-age=".$browser_cache);
		header('Expires: '.gmdate('D, d M Y H:i:s', time()+$browser_cache).' GMT');
		header('Content-Length: '.filesize($filename));
		$length = filesize($filename);
		$result = readfile($filename);
		readfile($filename);
		exit();
	}

	/* helper function: Create and send an image with an error message. */
	private function sendErrorImage($message) {
		/* get all of the required data from the HTTP request */
		$document_root  = $_SERVER['DOCUMENT_ROOT'];
		$requested_uri  = parse_url(urldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH);
		$requested_file = basename($requested_uri);
		$source_file    = $this->source_file;

		if(!$this->is_mobile()){
			$is_mobile = "FALSE";
		} else {
			$$this->is_mobile = "TRUE";
		}

		$im            = ImageCreateTrueColor(800, 300);
		$text_color    = ImageColorAllocate($im, 233, 14, 91);
		$message_color = ImageColorAllocate($im, 91, 112, 233);

		ImageString($im, 5, 5, 5, "Adaptive Images encountered a problem:", $text_color);
		ImageString($im, 3, 5, 25, $message, $message_color);

		ImageString($im, 5, 5, 85, "Potentially useful information:", $text_color);
		ImageString($im, 3, 5, 105, "DOCUMENT ROOT IS: $document_root", $text_color);
		ImageString($im, 3, 5, 125, "REQUESTED URI WAS: $requested_uri", $text_color);
		ImageString($im, 3, 5, 145, "REQUESTED FILE WAS: $requested_file", $text_color);
		ImageString($im, 3, 5, 165, "SOURCE FILE IS: $source_file", $text_color);
		ImageString($im, 3, 5, 185, "DEVICE IS MOBILE? $is_mobile", $text_color);

		header("Cache-Control: no-store");
		header('Expires: '.gmdate('D, d M Y H:i:s', time()-1000).' GMT');
		header('Content-Type: image/jpeg');
		ImageJpeg($im);
		ImageDestroy($im);
		exit();
	}

	/* 锐化图片函数 */
	private function findSharp($intOrig, $intFinal) {
		$intFinal = $intFinal * (750.0 / $intOrig);
		$intA     = 52;
		$intB     = -0.27810650887573124;
		$intC     = .00047337278106508946;
		$intRes   = $intA + $intB * $intFinal + $intC * $intFinal * $intFinal;
		return max(round($intRes), 0);
	}


	/* 刷新过期图片的缓存 */
	private function refreshCache($source_file, $cache_file, $resolution) {
		if (file_exists($cache_file)) {
    		// 未修改原图
			if (filemtime($cache_file) >= filemtime($source_file)) {
				return $cache_file;
			}

    		// 修改过原图，清除缓存
			unlink($cache_file);
		}
		return $this->generateImage($source_file, $cache_file, $resolution);
	}

	/* 生成所给文件和响应式尺寸的缓存文件 */
	private function generateImage($source_file, $cache_file, $resolution) {
		global $sharpen, $jpg_quality;
		$sharpen = $this->sharpen;
		$jpg_quality = $this->jpg_quality;
		$extension = strtolower(pathinfo($source_file, PATHINFO_EXTENSION));

	  	// Check the image dimensions
		$dimensions   = GetImageSize($source_file);
		$width        = $dimensions[0];
		$height       = $dimensions[1];

	  	// 是否需要缩小图像?
		if ($width <= $resolution) {
			return $source_file;
		}

	  	// We need to resize the source image to the width of the resolution breakpoint we're working with
		$ratio      = $height/$width;
		$new_width  = $resolution;
		$new_height = ceil($new_width * $ratio);
		$dst        = ImageCreateTrueColor($new_width, $new_height); // re-sized image

		switch ($extension) {
			case 'png':
			  $src = @ImageCreateFromPng($source_file); // original image
			  break;
			  case 'gif':
			  $src = @ImageCreateFromGif($source_file); // original image
			  break;
			  default:
			  $src = @ImageCreateFromJpeg($source_file); // original image
			  ImageInterlace($dst, true); // Enable interlancing (progressive JPG, smaller size file)
			  break;
			}

			if($extension=='png'){
				imagealphablending($dst, false);
				imagesavealpha($dst,true);
				$transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
				imagefilledrectangle($dst, 0, 0, $new_width, $new_height, $transparent);
			}

		ImageCopyResampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height); // do the resize in memory
		ImageDestroy($src);

		// 锐化图片?
		// NOTE: requires PHP compiled with the bundled version of GD (see http://php.net/manual/en/function.imageconvolution.php)
		if($sharpen == TRUE && function_exists('imageconvolution')) {
			$intSharpness = $this->findSharp($width, $new_width);
			$arrMatrix = array(
				array(-1, -2, -1),
				array(-2, $intSharpness + 12, -2),
				array(-1, -2, -1)
				);
			imageconvolution($dst, $arrMatrix, $intSharpness, 0);
		}

		$cache_dir = dirname($cache_file);

		// does the directory exist already?
		if (!is_dir($cache_dir)) {
			if (!mkdir($cache_dir, 0755, true)) {
		  		// check again if it really doesn't exist to protect against race conditions
				if (!is_dir($cache_dir)) {
		    		// uh-oh, failed to make that directory
					ImageDestroy($dst);
					$this->sendErrorImage("Failed to create cache directory: $cache_dir");
				}
			}
		}

		if (!is_writable($cache_dir)) {
			$this->sendErrorImage("The cache directory is not writable: $cache_dir");
		}

	  // save the new file in the appropriate path, and send a version to the browser
		switch ($extension) {
			case 'png':
				$gotSaved = ImagePng($dst, $cache_file);
				break;
			case 'gif':
				$gotSaved = ImageGif($dst, $cache_file);
				break;
			default:
				$gotSaved = ImageJpeg($dst, $cache_file, $jpg_quality);
				break;
		}
		ImageDestroy($dst);

		if (!$gotSaved && !file_exists($cache_file)) {
			$this->sendErrorImage("Failed to create image: $cache_file");
		}

		return $cache_file;
	}

}
