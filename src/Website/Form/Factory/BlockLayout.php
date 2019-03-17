<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\Form\Form;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\BlockLayout as Entity;
use Opulence\Orm\IEntity;
use Opulence\Sessions\ISession;

class BlockLayout extends Base
{
    /**
     * BlockLayout constructor.
     *
     * @param ISession    $session
     * @param ITranslator $translator
     */
    public function __construct(ISession $session, ITranslator $translator)
    {
        parent::__construct($session, $translator);
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
            ->addBody($entity)
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
    protected function addIdentifier(Entity $entity): BlockLayout
    {
        $input = new Input(
            'identifier',
            'identifier',
            $entity->getIdentifier()
        );
        $label = new Label('identifier', 'pages:blockLayoutIdentifier', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @param Entity $entity
     *
     * @return $this
     */
    protected function addBody(Entity $entity): BlockLayout
    {
        $input = new Textarea(
            'body',
            'body',
            htmlspecialchars($entity->getBody()),
            null,
            [Textarea::ATTRIBUTE_ROWS => '15']
        );
        $label = new Label('body', 'pages:blockLayoutBody', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }
}
