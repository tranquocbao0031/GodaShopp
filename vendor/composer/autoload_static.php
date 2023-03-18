<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2dfece8111af6303cad7f8dc72ff6473
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2dfece8111af6303cad7f8dc72ff6473::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2dfece8111af6303cad7f8dc72ff6473::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2dfece8111af6303cad7f8dc72ff6473::$classMap;

        }, null, ClassLoader::class);
    }
}