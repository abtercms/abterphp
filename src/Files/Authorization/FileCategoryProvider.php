<?php

declare(strict_types=1);

namespace AbterPhp\Files\Authorization;

use AbterPhp\Admin\Authorization\PolicyProviderTrait;
use AbterPhp\Admin\Databases\Queries\IAuthLoader;
use Casbin\Model\Model;
use Casbin\Persist\Adapter as CasbinAdapter;
use AbterPhp\Files\Databases\Queries\FileCategoryAuthLoader as AuthLoader;

class FileCategoryProvider implements CasbinAdapter
{
    use PolicyProviderTrait;

    public const PREFIX = 'file_category';

    /** @var IAuthLoader */
    protected IAuthLoader $authLoader;

    /**
     * FileCategory constructor.
     *
     * @param AuthLoader $authLoader
     */
    public function __construct(AuthLoader $authLoader)
    {
        $this->authLoader = $authLoader;
        $this->prefix     = static::PREFIX;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param Model $model
     *
     * @return void
     */
    public function savePolicy(Model $model): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     *
     * @return void
     */
    public function addPolicy(string $sec, string $ptype, array $rule): void
    {
        return;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param array  $rule
     *
     * @return void
     */
    public function removePolicy(string $sec, string $ptype, array $rule): void
    {
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $sec
     * @param string $ptype
     * @param int $fieldIndex
     * @param string ...$fieldValues
     *
     * @return void
     */
    public function removeFilteredPolicy(string $sec, string $ptype, int $fieldIndex, string ...$fieldValues): void
    {
    }
}
