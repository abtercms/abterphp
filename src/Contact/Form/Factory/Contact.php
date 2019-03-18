<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Form\Factory;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Factory\Base;
use AbterPhp\Framework\Form\Form;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\Html\Component\Button;
use Opulence\Framework\Http\CsrfTokenChecker;
use Opulence\Orm\IEntity;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Contact extends Base
{
    /**
     * @param string $action
     * @param string $method
     * @param string $showUrl
     * @param ?Entity $entity
     *
     * @return Form
     */
    public function create(string $action, string $method, string $showUrl, ?IEntity $entity = null): IForm
    {
        $this->createForm($action, $method)
            ->addDefaultElements()
            ->addFromName()
            ->addFromEmail()
            ->addSubject()
            ->addBody()
            ->addSubmit();

        $form = $this->form;

        $this->form = null;

        return $form;
    }

    /**
     * @return $this
     */
    protected function addDefaultElements(): Base
    {
        $name  = CsrfTokenChecker::TOKEN_INPUT_NAME;
        $value = (string)$this->session->get($name);

        $attributes[Input::ATTRIBUTE_TYPE] = Input::TYPE_HIDDEN;

        $this->form[] = new Input($name, $name, $value, null, $attributes, $this->translator);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addFromName(): Contact
    {
        $input = new Input(
            'from-name',
            'from-name',
            ''
        );
        $label = new Label('from-name', 'contact:fromName', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addFromEmail(): Contact
    {
        $input = new Input(
            'from-email',
            'from-email',
            '',
            null,
            [Input::ATTRIBUTE_TYPE => Input::TYPE_EMAIL]
        );
        $label = new Label('from-email', 'contact:fromEmail', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addSubject(): Contact
    {
        $input = new Input(
            'subject',
            'subject',
            ''
        );
        $label = new Label('subject', 'contact:subject', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addBody(): Contact
    {
        $input = new Textarea(
            'body',
            'body',
            '',
            null,
            [Textarea::ATTRIBUTE_ROWS => '15']
        );
        $label = new Label('body', 'contact:body', null, [], $this->translator);

        $this->form[] = new FormGroup($input, $label, null);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addSubmit(): Contact
    {
        $attributes = [];

        $attributes[Button::ATTRIBUTE_CLASS][] = Button::CLASS_PRIMARY;

        $content = $this->translator->translate('contact:submit');

        $this->form[] = new Button($content, $attributes);

        return $this;
    }
}
