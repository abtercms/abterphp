<?php

declare(strict_types=1);

namespace AbterPhp\PropellerAdmin\Events\Listeners;

use AbterPhp\Framework\Events\FormReady;
use AbterPhp\PropellerAdmin\Decorator\Form;
use AbterPhp\PropellerAdmin\Decorator\General;

class FormDecorator
{
    /** @var Form */
    protected $formDecorator;

    /** @var General */
    protected $generalDecorator;

    /**
     * FormDecorator constructor.
     *
     * @param Form    $formDecorator
     * @param General $generalDecorator
     */
    public function __construct(Form $formDecorator, General $generalDecorator)
    {
        $this->formDecorator    = $formDecorator;
        $this->generalDecorator = $generalDecorator;
    }

    /**
     * @param FormReady $event
     */
    public function handle(FormReady $event)
    {
        $form = $event->getForm();

        $nodes = array_merge([$form], $form->getExtendedDescendantNodes());

        $this->generalDecorator->init()->decorate($nodes);
        $this->formDecorator->init()->decorate($nodes);
    }
}
