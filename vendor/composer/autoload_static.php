<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticIniteea0b448905b8c3a5a5369852769ea2b
{
    public static $prefixLengthsPsr4 = array (
        'N' => 
        array (
            'Nano\\' => 5,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Nano\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticIniteea0b448905b8c3a5a5369852769ea2b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticIniteea0b448905b8c3a5a5369852769ea2b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticIniteea0b448905b8c3a5a5369852769ea2b::$classMap;

        }, null, ClassLoader::class);
    }
}
