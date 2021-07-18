<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Bootstrappers\Orm;

use AbterPhp\Admin\Bootstrappers\Orm\OrmBootstrapper as AbterAdminOrmBootstrapper;
use AbterPhp\Contact\Orm\FormRepo;

class OrmBootstrapper extends AbterAdminOrmBootstrapper
{
    /** @var string[] */
    protected array $baseBindings = [];

    /** @var string[] */
    protected array $repoMappers = [
        FormRepo::class,
    ];
}
