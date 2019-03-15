<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Select;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\Form\Form;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Countable;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Option;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\Block as Entity;
use AbterPhp\Website\Domain\Entities\BlockLayout;
use AbterPhp\Website\Orm\BlockLayoutRepo;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Block extends Base
{
    /** @var BlockLayoutRepo */
    protected $layoutRepo;

    /**
     * Block constructor.
     *
     * @param ISession        $session
     * @param ITranslator     $translator
     * @param BlockLayoutRepo $layoutRepo
     */
    public function __construct(ISession $session, ITranslator $translator, BlockLayoutRepo $layoutRepo)
    {
        parent::__construct($session, $translator);

        $this->layoutRepo = $layoutRepo;
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
            ->addBody($entity)
            ->addLayoutId($entity)
            ->addLayout($entity)
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
    protected function addIdentifier(Entity $entity): Block
    {
        $input = new Input(
            'identifier',
            'identifier',
            $entity->getIdentifier()
        );
        $label = new Label('title', 'pages:blockIdentifier', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addTitle(Entity $entity): Block
    {
        $input = new Input(
            'title',
            'title',
            $entity->getTitle()
        );
        $label = new Label('title', 'pages:blockTitle', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addBody(Entity $entity): Block
    {
        $input = new Textarea(
            'body',
            'body',
            $entity->getBody(),
            null,
            [Textarea::ATTRIBUTE_CLASS => [Textarea::CLASS_WYSIWYG], Textarea::ATTRIBUTE_ROWS => '15']
        );
        $label = new Label('body', 'pages:blockBody', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayoutId(Entity $entity): Block
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
     * @return BlockLayout[]
     */
    protected function getAllLayouts(): array
    {
        return $this->layoutRepo->getAll();
    }

    /**
     * @param BlockLayout[] $allLayouts
     * @param int|null      $layoutId
     *
     * @return Option[]
     */
    protected function createLayoutIdOptions(array $allLayouts, ?int $layoutId): array
    {
        $options = [];
        $options[] = new Option('form:none', null, [Option::ATTRIBUTE_VALUE => ''], $this->translator);
        foreach ($allLayouts as $layout) {
            $attributes = [Option::ATTRIBUTE_VALUE => (string)$layout->getId()];
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
        return new Label('layout_id', 'pages:blockLayoutIdLabel', null, [], $this->translator);
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addLayout(Entity $entity): Block
    {
        $input = new Textarea(
            'layout',
            'layout',
            $entity->getLayout(),
            null,
            [Textarea::ATTRIBUTE_ROWS => '15']
        );
        $label = new Countable(
            'description',
            'pages:blockLayoutLabel',
            Countable::DEFAULT_SIZE,
            null,
            [],
            $this->translator
        );

        $this->form[] = new FormGroup(
            $input,
            $label,
            null,
            null,
            [
                FormGroup::ATTRIBUTE_ID    => 'layout-div',
                FormGroup::ATTRIBUTE_CLASS => FormGroup::CLASS_COUNTABLE,
            ]
        );

        return $this;
    }
}
