<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite955ab85ae4548aa741b7a3e8945b9fa
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'MaxMind\\Db\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'MaxMind\\Db\\' => 
        array (
            0 => __DIR__ . '/..' . '/maxmind-db/reader/src/MaxMind/Db',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite955ab85ae4548aa741b7a3e8945b9fa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite955ab85ae4548aa741b7a3e8945b9fa::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite955ab85ae4548aa741b7a3e8945b9fa::$classMap;

        }, null, ClassLoader::class);
    }
}