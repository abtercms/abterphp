<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Admin\Form\Factory\Base;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Form\Container\CheckboxGroup;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Extra\DefaultButtons;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Button;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Constant\Authorization;
use AbterPhp\Website\Domain\Entities\ContentList as Entity;
use AbterPhp\Website\Form\Factory\ContentList\Item as ItemFactory;
use AbterPhp\Website\Orm\ContentListItemRepo as ItemRepo;
use Casbin\Enforcer;
use Casbin\Exceptions\CasbinException;
use Opulence\Orm\IEntity;
use Opulence\Orm\OrmException;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class ContentList extends Base
{
    protected const NEW_ITEM_NAME = 'website:contentListItemNew';

    protected const ITEM_TEMPLATE_CLASS = 'item-template';

    protected const NEW_ITEMS_CONTAINER_ID      = 'new-items';
    protected const EXISTING_ITEMS_CONTAINER_ID = 'existing-items';
    protected const ADD_BTN_ID                  = 'add-item-container';
    protected const PROTECTED_FORM_GROUP_ID     = 'protected-container';
    protected const WITH_CLASSES_ID             = 'withClasses-container';
    protected const WITH_HTML_ID                = 'withHtml-container';
    protected const WITH_IMAGES_ID              = 'withImages-container';
    protected const WITH_LINKS_ID               = 'withLinks-container';
    protected const WITH_LABEL_LINKS_ID         = 'withLabelLinks-container';

    protected const ITEM_ATTRIBS                 = [Html5::ATTR_CLASS => self::ITEM_TEMPLATE_CLASS];
    protected const EXISTING_ITEMS_ATTRIBS       = [Html5::ATTR_ID => self::EXISTING_ITEMS_CONTAINER_ID];
    protected const NEW_ITEMS_ATTRIBS            = [Html5::ATTR_ID => self::NEW_ITEMS_CONTAINER_ID];
    protected const ADD_BTN_ATTRIBS              = [Html5::ATTR_ID => self::ADD_BTN_ID];
    protected const PROTECTED_FORM_GROUP_ATTRIBS = [Html5::ATTR_ID => self::PROTECTED_FORM_GROUP_ID];
    protected const WITH_CLASSES_ATTRIBS         = [Html5::ATTR_ID => self::WITH_CLASSES_ID];
    protected const WITH_HTML_ATTRIBS            = [Html5::ATTR_ID => self::WITH_HTML_ID];
    protected const WITH_IMAGES_ATTRIBS          = [Html5::ATTR_ID => self::WITH_IMAGES_ID];
    protected const WITH_LINKS_ATTRIBS           = [Html5::ATTR_ID => self::WITH_LINKS_ID];
    protected const WITH_LABEL_LINKS_ATTRIBS     = [Html5::ATTR_ID => self::WITH_LABEL_LINKS_ID];

    protected ItemRepo $itemRepo;

    protected ItemFactory $itemFactory;

    protected Enforcer $enforcer;

    protected ?bool $advancedAllowed = null;

    /**
     * ContentList constructor.
     *
     * @param ISession    $session
     * @param ITranslator $translator
     * @param ItemRepo    $itemRepo
     * @param ItemFactory $itemFactory
     * @param Enforcer    $enforcer
     */
    public function __construct(
        ISession $session,
        ITranslator $translator,
        ItemRepo $itemRepo,
        ItemFactory $itemFactory,
        Enforcer $enforcer
    ) {
        parent::__construct($session, $translator);

        $this->itemRepo    = $itemRepo;
        $this->itemFactory = $itemFactory;
        $this->enforcer    = $enforcer;
    }

    /**
     * @return bool
     * @throws \Casbin\Exceptions\CasbinException
     */
    protected function isUserAdvanced(): bool
    {
        if ($this->advancedAllowed !== null) {
            return $this->advancedAllowed;
        }

        $username              = $this->session->get(Session::USERNAME);
        $this->advancedAllowed = $this->enforcer->enforce(
            $username,
            Authorization::RESOURCE_LISTS,
            Authorization::ROLE_ADVANCED_WRITE
        );

        return $this->advancedAllowed;
    }

    /**
     * @param Entity|null $entity
     *
     * @return bool
     * @throws \Casbin\Exceptions\CasbinException
     */
    protected function showAdvancedFields(?Entity $entity): bool
    {
        if (null === $entity || !$entity->getId()) {
            return true;
        }

        return !$entity->isProtected() || $this->isUserAdvanced();
    }

    /**
     * @param string       $action
     * @param string       $method
     * @param string       $showUrl
     * @param IEntity|null $entity
     *
     * @return IForm
     * @throws CasbinException|OrmException
     */
    public function create(string $action, string $method, string $showUrl, ?IEntity $entity = null): IForm
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addName($entity)
            ->addIdentifier($entity)
            ->addClasses($entity)
            ->addProtected($entity)
            ->addWithLinks($entity)
            ->addWithLabelLinks($entity)
            ->addWithHtml($entity)
            ->addWithImages($entity)
            ->addWithClasses($entity)
            ->addExistingItems($entity)
            ->addNewItems($entity)
            ->addAddBtn($entity)
            ->addButtons($entity, $showUrl);

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addName(Entity $entity): self
    {
        // Identifier of protected lists can only be set by advanced users
        if ($entity->isProtected() && !$this->isUserAdvanced()) {
            return $this;
        }

        $input = new Input('name', 'name', $entity->getName());
        $label = new Label('name', 'website:contentListName');

        $this->form[] = new FormGroup($input, $label, null, [], [Html5::ATTR_CLASS => FormGroup::CLASS_REQUIRED]);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addIdentifier(Entity $entity): self
    {
        // Identifier of protected lists can only be set by advanced users
        if ($entity->isProtected() && !$this->isUserAdvanced()) {
            return $this;
        }

        $input = new Input('identifier', 'identifier', $entity->getIdentifier(), [], Input::IDENTIFIER_ATTRIBS);
        $label = new Label('identifier', 'website:contentListIdentifier');
        $help  = new Help('website:contentListIdentifierHelp');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addClasses(Entity $entity): self
    {
        if (!$this->showAdvancedFields($entity)) {
            return $this;
        }

        $input = new Input('classes', 'classes', $entity->getClasses());
        $label = new Label('classes', 'website:contentListClasses');
        $help  = new Help('website:contentListClassesHelp');

        $this->form[] = new FormGroup($input, $label, $help);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addProtected(Entity $entity): self
    {
        if (!$this->showAdvancedFields($entity)) {
            return $this;
        }

        $attributes = Input::getRawCheckboxAttribs($entity->isProtected());
        $input      = new Input('protected', 'protected', '1', [], $attributes);
        $label      = new Label('protected', 'website:contentListProtected');
        $help       = new Help('website:contentListProtectedHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], static::PROTECTED_FORM_GROUP_ATTRIBS);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addWithLinks(Entity $entity): self
    {
        if (!$this->showAdvancedFields($entity)) {
            return $this;
        }

        $attributes = Input::getRawCheckboxAttribs($entity->isWithLinks());
        $input      = new Input('with_links', 'with_links', '1', [], $attributes);
        $label      = new Label('with_links', 'website:contentListWithLinks');
        $help       = new Help('website:contentListWithLinksHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], static::WITH_LINKS_ATTRIBS);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addWithLabelLinks(Entity $entity): self
    {
        if (!$this->showAdvancedFields($entity)) {
            return $this;
        }

        $attributes = Input::getRawCheckboxAttribs($entity->isWithLabelLinks());
        $input      = new Input('with_label_links', 'with_label_links', '1', [], $attributes);
        $label      = new Label('with_label_links', 'website:contentListWithLabelLinks');
        $help       = new Help('website:contentListWithLabelLinksHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], static::WITH_LABEL_LINKS_ATTRIBS);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addWithHtml(Entity $entity): self
    {
        if (!$this->showAdvancedFields($entity)) {
            return $this;
        }

        $attributes = Input::getRawCheckboxAttribs($entity->isWithHtml());
        $input      = new Input('with_html', 'with_html', '1', [], $attributes);
        $label      = new Label('with_html', 'website:contentListWithHtml');
        $help       = new Help('website:contentListWithHtmlHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], static::WITH_HTML_ATTRIBS);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addWithImages(Entity $entity): self
    {
        if (!$this->showAdvancedFields($entity)) {
            return $this;
        }

        $attributes = Input::getRawCheckboxAttribs($entity->isWithImages());
        $input      = new Input('with_images', 'with_images', '1', [], $attributes);
        $label      = new Label('with_images', 'website:contentListWithImages');
        $help       = new Help('website:contentListWithImagesHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], static::WITH_IMAGES_ATTRIBS);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addWithClasses(Entity $entity): self
    {
        if (!$this->showAdvancedFields($entity)) {
            return $this;
        }

        $attributes = Input::getRawCheckboxAttribs($entity->isWithClasses());
        $input      = new Input('with_classes', 'with_classes', '1', [], $attributes);
        $label      = new Label('with_classes', 'website:contentListWithClasses');
        $help       = new Help('website:contentListWithClassesHelp');

        $this->form[] = new CheckboxGroup($input, $label, $help, [], static::WITH_CLASSES_ATTRIBS);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws OrmException
     */
    protected function addExistingItems(Entity $entity): self
    {
        // There's no reason to check existing items during creation
        if (!$entity->getId()) {
            return $this;
        }

        $links      = $entity->isWithLinks();
        $labelLinks = $entity->isWithLabelLinks();
        $html       = $entity->isWithHtml();
        $images     = $entity->isWithImages();
        $classes    = $entity->isWithClasses();

        $container = new Tag(null, [], static::EXISTING_ITEMS_ATTRIBS, Html5::TAG_SECTION);

        $items = $this->itemRepo->getByListId($entity->getId());
        foreach ($items as $item) {
            $fieldset   = new Tag(null, [], [], Html5::TAG_FIELDSET);
            $fieldset[] = new Tag($item->getLabel(), [], [], Html5::TAG_LEGEND);

            $components = $this->itemFactory->create($item, $links, $labelLinks, $html, $images, $classes);
            foreach ($components as $component) {
                $fieldset[] = $component;
            }
            $container[] = $fieldset;
        }

        $this->form[] = $container;

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addNewItems(Entity $entity): self
    {
        // New items can not be added during creation
        if (!$entity->getId()) {
            return $this;
        }
        // New items can only be added to protected lists by advanced users
        if ($entity->isProtected() && !$this->isUserAdvanced()) {
            return $this;
        }

        $links      = $entity->isWithLinks();
        $labelLinks = $entity->isWithLabelLinks();
        $html       = $entity->isWithHtml();
        $images     = $entity->isWithImages();
        $classes    = $entity->isWithClasses();

        $container = new Tag(null, [], static::NEW_ITEMS_ATTRIBS, Html5::TAG_SECTION);

        $item   = new Tag(null, [], static::ITEM_ATTRIBS, Html5::TAG_FIELDSET);
        $item[] = new Tag(static::NEW_ITEM_NAME, [], [], Html5::TAG_LEGEND);

        $components = $this->itemFactory->create(null, $links, $labelLinks, $html, $images, $classes);
        $item->add(...$components);

        $container[] = $item;

        $this->form[] = $container;

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     * @throws CasbinException
     */
    protected function addAddBtn(Entity $entity): self
    {
        // New items can not be added during creation
        if (!$entity->getId()) {
            return $this;
        }
        // New items can only be added to protected lists by advanced users
        if ($entity->isProtected() && !$this->isUserAdvanced()) {
            return $this;
        }

        $i   = new Tag('add', [Button::INTENT_SMALL, Button::INTENT_ICON], [], Html5::TAG_I);
        $btn = new Button($i, [Button::INTENT_FAB, Button::INTENT_PRIMARY], [Html5::ATTR_TYPE => Button::TYPE_BUTTON]);

        $this->form[] = new Tag($btn, [], static::ADD_BTN_ATTRIBS, Html5::TAG_DIV);

        return $this;
    }

    /**
     * @param Entity $entity
     * @param string $showUrl
     *
     * @return Base
     */
    protected function addButtons(Entity $entity, string $showUrl): Base
    {
        if ($entity->getName()) {
            return parent::addDefaultButtons($showUrl);
        }

        return $this->addNewButtons($showUrl);
    }

    /**
     * @param string $showUrl
     *
     * @return Base
     */
    protected function addNewButtons(string $showUrl): Base
    {
        $buttons = new DefaultButtons();

        $buttons
            ->addSaveAndEdit(Button::INTENT_PRIMARY, Button::INTENT_FORM)
            ->addBackToGrid($showUrl)
            ->addSaveAndBack(Button::INTENT_WARNING, Button::INTENT_FORM)
            ->addSaveAndCreate();

        $this->form[] = $buttons;

        return $this;
    }
}
