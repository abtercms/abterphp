<?php

declare(strict_types=1);

namespace AbterPhp\Bootstrap4Website\Events\Listeners;

use AbterPhp\Framework\Events\FormReady;
use AbterPhp\Bootstrap4Website\Decorator\Form;

class FormDecorator
{
    /** @var Form */
    protected $formDecorator;

    /**
     * FormDecorator constructor.
     *
     * @param Form $formDecorator
     */
    public function __construct(Form $formDecorator)
    {
        $this->formDecorator = $formDecorator;
    }

    /**
     * @param FormReady $event
     */
    public function handle(FormReady $event)
    {
        $form = $event->getForm();

        $nodes = array_merge([$form], $form->getExtendedNodes());

        $this->formDecorator->init()->decorate($nodes);
    }
}
