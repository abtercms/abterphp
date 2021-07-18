<?php

declare(strict_types=1);

namespace AbterPhp\Files\Bootstrappers\Orm;

use AbterPhp\Admin\Bootstrappers\Orm\OrmBootstrapper as AbterAdminOrmBootstrapper;
use AbterPhp\Files\Orm\FileCategoryRepo;
use AbterPhp\Files\Orm\FileDownloadRepo;
use AbterPhp\Files\Orm\FileRepo;

class OrmBootstrapper extends AbterAdminOrmBootstrapper
{
    /** @var string[] */
    protected array $baseBindings = [];

    /** @var string[] */
    protected array $repoMappers = [
        FileDownloadRepo::class,
        FileCategoryRepo::class,
        FileRepo::class,
    ];
}
