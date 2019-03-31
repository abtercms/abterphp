<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Form\Component\Option;
use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Container\Hideable;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Countable;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Constant\Authorization;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\PageLayout;
use AbterPhp\Website\Form\Factory\Page\Assets as AssetsFactory;
use AbterPhp\Website\Form\Factory\Page\Meta as MetaFactory;
use AbterPhp\Website\Orm\PageLayoutRepo;
use Casbin\Enforcer;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Page extends Base
{
    /** @var PageLayoutRepo */
    protected $layoutRepo;

    /** @var MetaFactory */
    protected $metaFactory;

    /** @var AssetsFactory */
    protected $assetsFactory;

    /** @var Enforcer */
    protected $enforcer;

    /**
     * Page constructor.
     *
     * @param ISession       $session
     * @param ITranslator    $translator
     * @param PageLayoutRepo $layoutRepo
     * @param MetaFactory    $metaFactory
     * @param AssetsFactory  $assetsFactory
     * @param Enforcer       $enforcer
     */
    public function __construct(
        ISession $session,
        ITranslator $translator,
        PageLayoutRepo $layoutRepo,
        MetaFactory $metaFactory,
        AssetsFactory $assetsFactory,
        Enforcer $enforcer
    ) {
        parent::__construct($session, $translator);

        $this->layoutRepo    = $layoutRepo;
        $this->metaFactory   = $metaFactory;
        $this->assetsFactory = $assetsFactory;
        $this->enforcer      = $enforcer;
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

        $username        = $this->session->get(Session::USERNAME);
        $advancedAllowed = $this->enforcer->enforce(
            $username,
            Authorization::RESOURCE_PAGES,
            Authorization::ROLE_PAGES_ADVANCED_WRITE
        );

        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addIdentifier($entity)
            ->addTitle($entity)
            ->addDescription($entity)
            ->addMeta($entity)
            ->addBody($entity)
            ->addLayoutId($entity)
            ->addLayout($entity, $advancedAllowed)
            ->addAssets($entity, $advancedAllowed)
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
    protected function addIdentifier(Entity $entity): Page
    {
        $input = new Input('identifier', 'identifier', $entity->getIdentifier());
        $label = new Label('title', 'pages:pageIdentifier');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addTitle(Entity $entity): Page
    {
        $input = new Input('title', 'title', $entity->getTitle());
        $label = new Label('title', 'pages:pageTitle');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addDescription(Entity $entity): Page
    {
        $input = new Textarea('description', 'description', $entity->getMeta()->getDescription());
        $label = new Countable('description', 'pages:pageDescription', Countable::DEFAULT_SIZE);
        $help  = new Help('pages:pageDescriptionHelp');

        $this->form[] = new FormGroup(
            $input,
            $label,
            $help,
            [],
            [Html5::ATTR_CLASS => FormGroup::CLASS_COUNTABLE]
        );

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addMeta(Entity $entity): Page
    {
        $hideable = new Hideable($this->translator->translate('pages:pageMetaBtn'));
        foreach ($this->metaFactory->create($entity) as $component) {
            $hideable[] = $component;
        }

        $this->form[] = $hideable;

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addBody(Entity $entity): Page
    {
        $attribs = [Html5::ATTR_CLASS => 'wysiwyg', Html5::ATTR_ROWS => '15'];
        $input   = new Textarea('body', 'body', $entity->getBody(), [], $attribs);
        $label   = new Label('body', 'pages:pageBody');

        $this->form[] = new FormGroup($input, $label);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayoutId(Entity $entity): Page
    {
        $allLayouts = $this->getAllLayouts();
        $layoutId   = $entity->getLayoutId();

        $options = $this->createLayoutIdOptions($allLayouts, $layoutId);

        $this->form[] = new FormGroup(
            $this->createLayoutIdSelect($options),
            $this->createLayoutIdLabel()
        );

        return $this;
    }

    /**
     * @return PageLayout[]
     */
    protected function getAllLayouts(): array
    {
        return $this->layoutRepo->getAll();
    }

    /**
     * @param PageLayout[] $allLayouts
     * @param string|null  $layoutId
     *
     * @return Option[]
     */
    protected function createLayoutIdOptions(array $allLayouts, ?string $layoutId): array
    {
        $options   = [];
        $options[] = new Option('', 'framework:none', false);
        foreach ($allLayouts as $layout) {
            $content    = $layout->getIdentifier();
            $isSelected = $layout->getId() === $layoutId;
            $options[]  = new Option($layout->getId(), $content, $isSelected);
        }

        return $options;
    }

    /**
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createLayoutIdSelect(array $options): Select
    {
        $select = new Select('layout_id', 'layout_id');

        foreach ($options as $option) {
            $select[] = $option;
        }

        return $select;
    }

    /**
     * @return Label
     */
    protected function createLayoutIdLabel(): Label
    {
        return new Label('layout_id', 'pages:pageLayoutIdLabel');
    }

    /**
     * @param Entity $entity
     * @param bool   $advancedAllowed
     *
     * @return Page
     */
    protected function addLayout(Entity $entity, bool $advancedAllowed): Page
    {
        if (!$advancedAllowed) {
            return $this->addLayoutHidden($entity);
        }

        return $this->addLayoutTextarea($entity);
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayoutHidden(Entity $entity): Page
    {
        $attribs      = [Html5::ATTR_TYPE => Input::TYPE_HIDDEN];
        $this->form[] = new Input('layout', 'layout', $entity->getLayout(), [], $attribs);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayoutTextarea(Entity $entity): Page
    {
        $input = new Textarea('layout', 'layout', $entity->getLayout(), [], [Html5::ATTR_ROWS => '15']);
        $label = new Label('layout', 'pages:pageLayoutLabel');

        $this->form[] = new FormGroup($input, $label, null, [], [Html5::ATTR_ID => 'layout-div']);

        return $this;
    }

    /**
     * @param Entity $entity
     * @param bool   $advancedAllowed
     *
     * @return $this
     */
    protected function addAssets(Entity $entity, bool $advancedAllowed): Page
    {
        if (!$advancedAllowed) {
            return $this;
        }

        $hideable = new Hideable($this->translator->translate('pages:pageAssetsBtn'));

        $nodes = $this->assetsFactory->create($entity);
        foreach ($nodes as $node) {
            $hideable[] = $node;
        }

        $this->form[] = $hideable;

        return $this;
    }
}
