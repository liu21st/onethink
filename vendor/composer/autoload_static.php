<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitc1937b60f29f87fa90fa27ad6dcfedaa
{
    public static $files = array (
        '1cfd2761b63b0a29ed23657ea394cb2d' => __DIR__ . '/..' . '/topthink/think-captcha/src/helper.php',
    );

    public static $prefixLengthsPsr4 = array (
        't' => 
        array (
            'think\\composer\\' => 15,
            'think\\captcha\\' => 14,
            'think\\angular\\' => 14,
            'think\\' => 6,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'think\\composer\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-installer/src',
        ),
        'think\\captcha\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-captcha/src',
        ),
        'think\\angular\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-angular/src',
        ),
        'think\\' => 
        array (
            0 => __DIR__ . '/..' . '/topthink/think-image/src',
        ),
    );

    public static $classMap = array (
        'think\\view\\driver\\Angular' => __DIR__ . '/..' . '/topthink/think-angular/drivers/thinkphp5/Angular.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitc1937b60f29f87fa90fa27ad6dcfedaa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitc1937b60f29f87fa90fa27ad6dcfedaa::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitc1937b60f29f87fa90fa27ad6dcfedaa::$classMap;

        }, null, ClassLoader::class);
    }
}
