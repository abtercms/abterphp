<?php

declare(strict_types=1);

namespace AbterPhp\Files\Form\Factory;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\Form\Form;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Option;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Files\Domain\Entities\FileCategory as Entity;
use AbterPhp\Admin\Domain\Entities\UserGroup;
use AbterPhp\Admin\Orm\UserGroupRepo;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FileCategory extends Base
{
    /** @var UserGroupRepo */
    protected $userGroupRepo;

    /**
     * FileCategory constructor.
     *
     * @param ISession      $session
     * @param ITranslator   $translator
     * @param UserGroupRepo $userGroupRepo
     */
    public function __construct(ISession $session, ITranslator $translator, UserGroupRepo $userGroupRepo)
    {
        parent::__construct($session, $translator);

        $this->userGroupRepo = $userGroupRepo;
    }

    /**
     * @param string       $action
     * @param string       $method
     * @param string       $showUrl
     * @param IEntity|null $entity
     *
     * @return $this
     */
    public function create(string $action, string $method, string $showUrl, ?IEntity $entity = null): IForm
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(IFormFactory::ERR_MSG_ENTITY_MISSING);
        }

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addIdentifier($entity)
            ->addName($entity)
            ->addUserGroups($entity)
            ->addDefaultButtons($showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addIdentifier(Entity $entity): FileCategory
    {
        $this->form[] = new Input(
            'identifier',
            'identifier',
            $entity->getIdentifier(),
            null,
            [Input::ATTRIBUTE_TYPE => Input::TYPE_HIDDEN]
        );

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addName(Entity $entity): FileCategory
    {
        $input = new Input(
            'name',
            'name',
            $entity->getName()
        );
        $label = new Label('name', 'files:fileCategoryName', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addUserGroups(Entity $entity): FileCategory
    {
        $allUserGroups = $this->getAllUserGroups();
        $userGroupIds  = $this->getUserGroupIds($entity);

        $options = $this->createUserGroupOptions($allUserGroups, $userGroupIds);

        $this->form[] = new FormGroup(
            $this->createUserGroupSelect($entity, $options),
            $this->createUserGroupLabel()
        );

        return $this;
    }

    /**
     * @return UserGroup[]
     */
    protected function getAllUserGroups(): array
    {
        return $this->userGroupRepo->getAll();
    }

    /**
     * @param Entity $entity
     *
     * @return int[]
     */
    protected function getUserGroupIds(Entity $entity): array
    {
        $userGroupIds = [];
        foreach ($entity->getUserGroups() as $userGroup) {
            $userGroupIds[] = $userGroup->getId();
        }

        return $userGroupIds;
    }

    /**
     * @param UserGroup[] $allUserGroups
     * @param int[]       $userGroupIds
     *
     * @return array
     */
    protected function createUserGroupOptions(array $allUserGroups, array $userGroupIds): array
    {
        $options = [];
        foreach ($allUserGroups as $userGroup) {
            $attributes = [Option::ATTRIBUTE_VALUE => (string)$userGroup->getId()];
            if (in_array($userGroup->getId(), $userGroupIds, true)) {
                $attributes[Option::ATTRIBUTE_SELECTED] = null;
            }
            $options[] = new Option($userGroup->getName(), null, $attributes);
        }

        return $options;
    }

    /**
     * @param Entity   $entity
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createUserGroupSelect(Entity $entity, array $options): Select
    {
        $attributes = [
            Select::ATTRIBUTE_SIZE => $this->getMultiSelectSize(
                count($options),
                static::MULTISELECT_MIN_SIZE,
                static::MULTISELECT_MAX_SIZE
            ),
        ];

        $select = new Select('user_group_ids', 'user_group_ids[]', $entity->getName(), true, null, $attributes);

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createUserGroupLabel(): Label
    {
        return new Label('user_group_ids', 'files:fileCategoryUserGroups', null, [], $this->translator);
    }

    /**
     * @param int $optionCount
     * @param int $minSize
     * @param int $maxSize
     *
     * @return int
     */
    protected function getMultiSelectSize(int $optionCount, int $minSize, int $maxSize): int
    {
        return (int)max(min($optionCount, $maxSize), $minSize);
    }
}
