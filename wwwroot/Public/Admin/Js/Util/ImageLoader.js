//*+----------------------------------------------
//* ImageLoader.js 
//* To load outside images.
//* Copyright(C) By Michael Chen(www.jzchen.net)
//*-----------------------------------------------

/****************************************************************************
 * ImageLoader, v1.2
 * To load outside images.
 * I design this Class for preloading the images. Initially, I use 'this' but
 *   faild. So I rewrite it as a static class. any body can give some help in 
 *   setTimeout("this.somefunc()") please mail me at [email]jzchen@jzchen.net[/email]
 *
 * Usage:     ImageLoader.add("a.gif", "b.gif", "c.jpg", ...); // add others..
 *            ImageLoader.onProgressChange = function() {...}
 *            ImageLoader.onLoadFinish = function() {...}
 *            ImageLoader.onTimeOut = somefunction;
 *            ImageLoader.startLoad();
 ****************************************************************************/

function ImageLoader() {}

// Internal varibles definition
ImageLoader._preImages = new Array();
ImageLoader._imageUrlBuffer = new Array();
ImageLoader._currentID = 0;
ImageLoader._loaded = new Array();
ImageLoader._loadedNum = 0;
ImageLoader._currentLoading = "";
ImageLoader._timeOut = 30 * 1000;
ImageLoader._timeElapsed = 0;
ImageLoader._checkInterval = 50;

// Event simulation
ImageLoader.onProgressChange = new Function();
ImageLoader.onLoadFinish = new Function();
ImageLoader.onTimeOut = new Function();

/**
* Add images to the image array
* @param image1, image2, ...
* @return none
*/
ImageLoader.add = function() {
    for (var i = ImageLoader._currentID; i < arguments.length; ImageLoader._currentID++,i++) {
        if (arguments[i] != null || arguments[i] != "") {
            ImageLoader._imageUrlBuffer[i] = arguments[i];
        }
    }
}
    
/**
* Get the Resouces count to be loaded
* @param none
* @return the number of resources to load.
*/
ImageLoader.getResourceCount = function() {
    return ImageLoader._imageUrlBuffer.length;
}

/**
* Get the  count of current loaded.
* @param none
* @return long the number of images that current loaded
*/
ImageLoader.getLoadedCount = function() {
    return ImageLoader._loadedNum;
}

/**
* Get the current loading image url
* @param none
* @return string the url of the current loading image.
*/
ImageLoader.getCurrentLoading = function() {
    return ImageLoader._currentLoading;
}

/**
* Set the timeout value. initial is 30 seconds.
* @param long millisecond time
* @return none
*/
ImageLoader.setTimeOut = function(ts) {
    ImageLoader._timeOut = ts;
}

/**
* Get the timeout value
* @param none
* @return int the timeout value
*/
ImageLoader.getTimeOut = function() {
    return ImageLoader._timeOut;
}

/**
* Start to load the images.
* @param none
* @return none
*/
ImageLoader.startLoad = function() {
    for (var i = 0; i < ImageLoader._imageUrlBuffer.length; i++) {
        ImageLoader._preImages[i] = new Image();
        ImageLoader._preImages[i].src = ImageLoader._imageUrlBuffer[i];
        ImageLoader._loaded[i] = false;
    }
    ImageLoader.checkLoad();
}

/*-
* checkLoad
* Internal use only. 
* Do not use it directly. otherwise will encount an error.
*/
ImageLoader.checkLoad = function() {
    if (ImageLoader._loadedNum == ImageLoader._preImages.length) { 
        ImageLoader.onLoadFinish();
        return;
    }

    if (ImageLoader._timeElapsed >= ImageLoader._timeOut) {
        ImageLoader.onTimeOut();
        return;
    }

    for (i = 0; i < ImageLoader._preImages.length; i++) {
        if (ImageLoader._loaded[i] == false && ImageLoader._preImages[i].complete) {
            ImageLoader._loaded[i] = true;
            ImageLoader._currentLoading = ImageLoader._imageUrlBuffer[i];
            ImageLoader._loadedNum++;
            ImageLoader.onProgressChange();
        }
    }
    
    ImageLoader._timeElapsed += ImageLoader._checkInterval;

    setTimeout("ImageLoader.checkLoad()", ImageLoader._checkInterval);
}