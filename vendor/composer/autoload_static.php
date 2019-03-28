<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb4813b44f64b04600de4240638075260
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb4813b44f64b04600de4240638075260::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb4813b44f64b04600de4240638075260::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
