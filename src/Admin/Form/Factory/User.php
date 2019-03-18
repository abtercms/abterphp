<?php

declare(strict_types=1);

namespace AbterPhp\Admin\Form\Factory;

use AbterPhp\Admin\Domain\Entities\User as Entity;
use AbterPhp\Admin\Domain\Entities\UserLanguage;
use AbterPhp\Admin\Orm\UserGroupRepo;
use AbterPhp\Admin\Orm\UserLanguageRepo;
use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Container\Toggle;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Form\Label\ToggleLabel;
use AbterPhp\Framework\Html\Component\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class User extends Base
{
    /** @var UserGroupRepo */
    protected $userGroupRepo;

    /** @var UserLanguageRepo */
    protected $userLanguageRepo;

    /**
     * User constructor.
     *
     * @param ISession         $session
     * @param ITranslator      $translator
     * @param UserGroupRepo    $userGroupRepo
     * @param UserLanguageRepo $userLanguageRepo
     */
    public function __construct(
        ISession $session,
        ITranslator $translator,
        UserGroupRepo $userGroupRepo,
        UserLanguageRepo $userLanguageRepo
    ) {
        parent::__construct($session, $translator);

        $this->userGroupRepo    = $userGroupRepo;
        $this->userLanguageRepo = $userLanguageRepo;
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
            ->addUsername($entity)
            ->addEmail($entity)
            ->addPassword()
            ->addPasswordConfirmed()
            ->addRawPassword()
            ->addRawPasswordConfirmed()
            ->addCanLogin($entity)
            ->addIsGravatarAllowed($entity)
            ->addUserGroups($entity)
            ->addUserLanguages($entity)
            ->addDefaultButtons($showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @return $this
     */
    protected function addJsOnly(): User
    {
        $content    = sprintf(
            '<i class="material-icons">warning</i>&nbsp;%s',
            $this->translator->translate('admin:jsOnly')
        );
        $attributes = [Tag::ATTRIBUTE_CLASS => 'only-js-form-warning'];

        $this->form[] = new Tag($content, $attributes, null, Tag::TAG_P);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addUsername(Entity $entity): User
    {
        $input = new Input(
            'username',
            'username',
            $entity->getUsername(),
            [Input::ATTRIBUTE_NAME => Input::AUTOCOMPLETE_OFF]
        );
        $label = new Label('body', 'admin:userUsername', [], $this->translator);

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addEmail(Entity $entity): User
    {
        $input = new Input(
            'email',
            'email',
            $entity->getEmail(),
            [Input::ATTRIBUTE_NAME => Input::AUTOCOMPLETE_OFF]
        );
        $label = new Label('email', 'admin:userEmail', [], $this->translator);

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addPassword(): User
    {
        $this->form[] = new Input(
            'password',
            'password',
            '',
            [Input::ATTRIBUTE_TYPE => Input::TYPE_HIDDEN]
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function addPasswordConfirmed(): User
    {
        $this->form[] = new Input(
            'password_confirmed',
            'password_confirmed',
            '',
            [Input::ATTRIBUTE_TYPE => Input::TYPE_HIDDEN]
        );

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRawPassword(): User
    {
        $input = new Input(
            'raw_password',
            'raw_password',
            '',
            [Input::ATTRIBUTE_NAME => Input::AUTOCOMPLETE_OFF]
        );
        $label = new Label('raw_password', 'admin:userPassword', [], $this->translator);

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRawPasswordConfirmed(): User
    {
        $input = new Input(
            'raw_password_confirmed',
            'raw_password_confirmed',
            '',
            [Input::ATTRIBUTE_NAME => Input::AUTOCOMPLETE_OFF]
        );
        $label = new Label('raw_password_confirmed', 'admin:userConfirmPassword', [], $this->translator);

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addCanLogin(Entity $entity): User
    {
        $attributes = [Input::ATTRIBUTE_TYPE => Input::TYPE_CHECKBOX];
        if ($entity->canLogin()) {
            $attributes[Input::ATTRIBUTE_CHECKED] = null;
        }
        $input = new Input(
            'can_login',
            'can_login',
            '1',
            $attributes
        );
        $label = new ToggleLabel('can_login', 'admin:userCanLogin', [], $this->translator);

        $this->form[] = new Toggle($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addIsGravatarAllowed(Entity $entity): User
    {
        $attributes = [Input::ATTRIBUTE_TYPE => Input::TYPE_CHECKBOX];
        if ($entity->isGravatarAllowed()) {
            $attributes[Input::ATTRIBUTE_CHECKED] = null;
        }
        $input = new Input(
            'is_gravatar_allowed',
            'is_gravatar_allowed',
            '1',
            $attributes
        );
        $label = new ToggleLabel(
            'is_gravatar_allowed',
            'admin:userIsGravatarAllowed',
            [],
            $this->translator
        );

        $this->form[] = new Toggle($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addUserGroups(Entity $entity): User
    {
        $allUserGroups = $this->getAllUserGroups();

        $userGroupIds = [];
        foreach ($entity->getUserGroups() as $userGroup) {
            $userGroupIds[] = (int)$userGroup->getId();
        }

        $options = $this->createUserGroupOptions($allUserGroups, $userGroupIds);

        $this->form[] = new FormGroup(
            $this->createUserGroupSelect($options),
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
     * @param UserGroup[] $allUserGroups
     * @param UserGroup[] $userGroupIds
     *
     * @return array
     */
    protected function createUserGroupOptions(array $allUserGroups, array $userGroupIds): array
    {
        $options = [];
        foreach ($allUserGroups as $userGroup) {
            $isSelected = in_array((int)$userGroup->getId(), $userGroupIds, true);
            $options[]  = new Option((string)$userGroup->getId(), $userGroup->getName(), $isSelected);
        }

        return $options;
    }

    /**
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createUserGroupSelect(array $options): Select
    {
        $attributes = [
            Select::ATTRIBUTE_SIZE => $this->getMultiSelectSize(
                count($options),
                static::MULTISELECT_MIN_SIZE,
                static::MULTISELECT_MAX_SIZE
            ),
        ];

        $select = new Select('user_group_ids', 'user_group_ids[]', true, $attributes);

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
        return new Label('user_group_ids', 'admin:userGroups', [], $this->translator);
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addUserLanguages(Entity $entity): User
    {
        $allUserGroups = $this->getAllUserLanguages();
        $userGroupId   = (int)$entity->getUserLanguage()->getId();

        $options = $this->createUserLanguageOptions($allUserGroups, $userGroupId);

        $this->form[] = new FormGroup(
            $this->createUserLanguageSelect($options),
            $this->createUserLanguageLabel()
        );

        return $this;
    }

    /**
     * @return UserLanguage[]
     */
    protected function getAllUserLanguages(): array
    {
        return $this->userLanguageRepo->getAll();
    }

    /**
     * @param UserLanguage[] $allUserLanguages
     * @param int            $userLanguageId
     *
     * @return array
     */
    protected function createUserLanguageOptions(array $allUserLanguages, int $userLanguageId): array
    {
        $options = [];
        foreach ($allUserLanguages as $userLanguage) {
            $isSelected = $userLanguageId === (int)$userLanguage->getId();
            $options[]  = new Option((string)$userLanguage->getId(), $userLanguage->getName(), $isSelected);
        }

        return $options;
    }

    /**
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createUserLanguageSelect(array $options): Select
    {
        $attributes = [
            Select::ATTRIBUTE_SIZE => $this->getMultiSelectSize(
                count($options),
                static::MULTISELECT_MIN_SIZE,
                static::MULTISELECT_MAX_SIZE
            ),
        ];

        $select = new Select('user_language_id', 'user_language_id', true, $attributes);

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createUserLanguageLabel(): Label
    {
        return new Label('user_language_id', 'admin:userLanguages', [], $this->translator);
    }
}
