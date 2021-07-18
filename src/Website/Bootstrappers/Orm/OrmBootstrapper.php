<?php

declare(strict_types=1);

namespace AbterPhp\Website\Bootstrappers\Orm;

use AbterPhp\Admin\Bootstrappers\Orm\OrmBootstrapper as AbterAdminOrmBootstrapper;
use AbterPhp\Website\Orm\BlockLayoutRepo;
use AbterPhp\Website\Orm\BlockRepo;
use AbterPhp\Website\Orm\ContentListItemRepo;
use AbterPhp\Website\Orm\ContentListRepo;
use AbterPhp\Website\Orm\PageCategoryRepo;
use AbterPhp\Website\Orm\PageLayoutRepo;
use AbterPhp\Website\Orm\PageRepo;

class OrmBootstrapper extends AbterAdminOrmBootstrapper
{
    /** @var string[] */
    protected array $baseBindings = [];

    /** @var string[] */
    protected array $repoMappers = [
        BlockLayoutRepo::class,
        BlockRepo::class,
        ContentListRepo::class,
        ContentListItemRepo::class,
        PageLayoutRepo::class,
        PageCategoryRepo::class,
        PageRepo::class,
    ];
}
