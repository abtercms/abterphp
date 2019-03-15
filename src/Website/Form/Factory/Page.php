<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

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
use AbterPhp\Framework\Html\Component\Option;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\Page as Entity;
use AbterPhp\Website\Domain\Entities\PageLayout;
use AbterPhp\Website\Form\Factory\Page\Assets as AssetsFactory;
use AbterPhp\Website\Form\Factory\Page\Meta as MetaFactory;
use AbterPhp\Website\Orm\PageLayoutRepo;
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

    /**
     * Page constructor.
     *
     * @param ISession       $session
     * @param ITranslator    $translator
     * @param PageLayoutRepo $layoutRepo
     * @param MetaFactory    $metaFactory
     * @param AssetsFactory  $assetsFactory
     */
    public function __construct(
        ISession $session,
        ITranslator $translator,
        PageLayoutRepo $layoutRepo,
        MetaFactory $metaFactory,
        AssetsFactory $assetsFactory
    ) {
        parent::__construct($session, $translator);

        $this->layoutRepo = $layoutRepo;

        $this->metaFactory   = $metaFactory;
        $this->assetsFactory = $assetsFactory;
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
            ->addTitle($entity)
            ->addDescription($entity)
            ->addMeta($entity)
            ->addBody($entity)
            ->addLayoutId($entity)
            ->addLayout($entity)
            ->addAssets($entity)
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
        $input = new Input(
            'identifier',
            'identifier',
            $entity->getIdentifier()
        );
        $label = new Label('title', 'pages:pageIdentifier', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addTitle(Entity $entity): Page
    {
        $input = new Input(
            'title',
            'title',
            $entity->getTitle()
        );
        $label = new Label('title', 'pages:pageTitle', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addDescription(Entity $entity): Page
    {
        $input = new Textarea(
            'description',
            'description',
            $entity->getMeta()->getDescription()
        );
        $label = new Countable(
            'description',
            'pages:pageDescription',
            Countable::DEFAULT_SIZE,
            null,
            [],
            $this->translator
        );
        $help  = new Help('pages:pageDescriptionHelp', null, [], $this->translator);

        $this->form[] = new FormGroup(
            $input,
            $label,
            $help,
            null,
            [FormGroup::ATTRIBUTE_CLASS => FormGroup::CLASS_COUNTABLE]
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
        $input = new Textarea(
            'body',
            'body',
            $entity->getBody(),
            null,
            [Textarea::ATTRIBUTE_CLASS => ['wysiwyg'], Textarea::ATTRIBUTE_ROWS => '15']
        );
        $label = new Label('body', 'pages:pageBody', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

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
            $this->createLayoutIdSelect($entity, $options),
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
     * @param int|null     $layoutId
     *
     * @return Option[]
     */
    protected function createLayoutIdOptions(array $allLayouts, ?int $layoutId): array
    {
        $options   = [];
        $options[] = new Option('framework:none', null, [Option::ATTRIBUTE_VALUE => ''], $this->translator);
        foreach ($allLayouts as $layout) {
            $attributes = [Option::ATTRIBUTE_VALUE => $layout->getId()];
            if ((int)$layout->getId() === $layoutId) {
                $attributes[Option::ATTRIBUTE_SELECTED] = null;
            }
            $options[] = new Option($layout->getIdentifier(), null, $attributes);
        }

        return $options;
    }

    /**
     * @param Entity   $entity
     * @param Option[] $options
     *
     * @return Select
     */
    protected function createLayoutIdSelect(Entity $entity, array $options): Select
    {
        $value = $entity->getLayoutId() === null ? '' : (string)$entity->getLayoutId();

        $select = new Select('layout_id', 'layout_id', $value);

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
        return new Label('layout_id', 'pages:pageLayoutIdLabel', null, [], $this->translator);
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayout(Entity $entity): Page
    {
        $input = new Textarea(
            'layout',
            'layout',
            $entity->getLayout(),
            null,
            [Textarea::ATTRIBUTE_ROWS => '15']
        );
        $label = new Label('layout', 'pages:pageLayoutLabel', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null, null, [FormGroup::ATTRIBUTE_ID => 'layout-div']);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addAssets(Entity $entity): Page
    {
        $hideable = new Hideable($this->translator->translate('pages:pageAssetsBtn'));
        foreach ($this->assetsFactory->create($entity) as $component) {
            $hideable[] = $component;
        }

        $this->form[] = $hideable;

        return $this;
    }
}
