<?php

$appRootPath = __DIR__ . '/../../vendor';

if (getenv('APP_ENV') !== 'dev')
{
    $autoloadFiles = [];

    /** @noinspection PhpIncludeInspection */
    $pathAutoloadFiles = $appRootPath . '/composer/autoload_files.php';

    if (file_exists($pathAutoloadFiles))
    {
        /** @noinspection PhpIncludeInspection */
        $autoloadFiles = require $pathAutoloadFiles;

        if (empty($autoloadFiles) === false)
        {
            foreach ($autoloadFiles as $file)
            {
                /** @noinspection PhpIncludeInspection */
                require $file;
            }
        }
    }

    /** @noinspection PhpIncludeInspection */
    $classmap = require $appRootPath . '/composer/autoload_classmap.php';

    spl_autoload_register(
        function ($class) use ($classmap)
        {
            if(isset($classmap[$class]))
            {
                /** @noinspection PhpIncludeInspection */
                require $classmap[$class];
            }
        }
    );
}
else
{
    /** @noinspection PhpIncludeInspection */
    require $appRootPath . '/autoload.php';
}
