<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Events;

use AbterPhp\Framework\Form\Form;

class FormReady
{
    /** @var Form */
    private $form;

    /**
     * TemplateEngineReady constructor.
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }
}
