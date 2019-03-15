<?php

namespace AbterPhp\Framework\Filesystem\Bootstrappers;

use AbterPhp\Framework\Constant\Env;
use AbterPhp\Framework\Filesystem\Uploader\Uploader;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

class FilesystemBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @return array
     */
    public function getBindings(): array
    {
        return [
            Filesystem::class,
            Uploader::class,
        ];
    }

    /**
     * @param IContainer $container
     */
    public function registerBindings(IContainer $container)
    {
        $dirPrivate        = getenv(Env::DIR_PRIVATE);
        $adapter           = new Local($dirPrivate);
        $storedFileManager = new Filesystem($adapter);
        $container->bindInstance(Filesystem::class, $storedFileManager);

        $uploader = new Uploader($storedFileManager, $dirPrivate);
        $container->bindInstance(Uploader::class, $uploader);
    }
}
