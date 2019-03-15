<?php

declare(strict_types=1);

namespace AbterPhp\Website\Form\Factory\Page;

use AbterPhp\Framework\Form\Container\FormGroup;
use AbterPhp\Framework\Form\Container\IContainer;
use AbterPhp\Framework\Form\Element\Input;
use AbterPhp\Framework\Form\Element\Textarea;
use AbterPhp\Framework\Form\Extra\Help;
use AbterPhp\Framework\Form\Label\Countable;
use AbterPhp\Framework\Form\Label\Label;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Website\Domain\Entities\Page as Entity;

class Meta
{
    /** @var ITranslator */
    protected $translator;

    /**
     * Hideable constructor.
     *
     * @param ITranslator $translator
     */
    public function __construct(ITranslator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return IContainer[]
     */
    public function create(Entity $entity): array
    {
        $components = [];

        $components[] = $this->addOGTitle($entity);
        $components[] = $this->addOGImage($entity);
        $components[] = $this->addOGDescription($entity);
        $components[] = $this->addAuthor($entity);
        $components[] = $this->addCopyright($entity);
        $components[] = $this->addKeywords($entity);
        $components[] = $this->addRobots($entity);

        return $components;
    }

    /**
     * @param Entity $entity
     *
     * @return IContainer
     */
    protected function addOGTitle(Entity $entity): IContainer
    {
        $input = new Input('og-title', 'og-title', $entity->getMeta()->getOGTitle());
        $label = new Label('og-title', 'pages:pageOGTitle', null, [], $this->translator);
        $help  = new Help('pages:pageOGTitleHelp', null, [], $this->translator);

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return IContainer
     */
    protected function addOGImage(Entity $entity): IContainer
    {
        $input = new Input('og-image', 'og-image', $entity->getMeta()->getOGImage());
        $label = new Label('og-image', 'pages:pageOGImage', null, [], $this->translator);
        $help  = new Help('pages:pageOGImageHelp', null, [], $this->translator);

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return IContainer
     */
    protected function addOGDescription(Entity $entity): IContainer
    {
        $input = new Textarea('og-description', 'og-description', $entity->getMeta()->getOGDescription());
        $label = new Countable(
            'og-description',
            'pages:pageOGDescription',
            Countable::DEFAULT_SIZE,
            null,
            [],
            $this->translator
        );
        $help  = new Help('pages:pageOGDescriptionHelp', null, [], $this->translator);

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return IContainer
     */
    protected function addAuthor(Entity $entity): IContainer
    {
        $input = new Input('author', 'author', $entity->getMeta()->getAuthor());
        $label = new Label('author', 'pages:pageAuthor', null, [], $this->translator);
        $help  = new Help('pages:pageAuthor', null, [], $this->translator);

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return IContainer
     */
    protected function addCopyright(Entity $entity): IContainer
    {
        $input = new Input('copyright', 'copyright', $entity->getMeta()->getAuthor());
        $label = new Label('copyright', 'pages:pageCopyright', null, [], $this->translator);
        $help  = new Help('pages:pageCopyrightHelp', null, [], $this->translator);

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return IContainer
     */
    protected function addKeywords(Entity $entity): IContainer
    {
        $input = new Input('keywords', 'keywords', $entity->getMeta()->getKeywords());
        $label = new Label('keywords', 'pages:pageKeywords', null, [], $this->translator);
        $help  = new Help('pages:pageKeywordsHelp', null, [], $this->translator);

        return new FormGroup($input, $label, $help);
    }

    /**
     * @param Entity $entity
     *
     * @return IContainer
     */
    protected function addRobots(Entity $entity): IContainer
    {
        $input = new Input('robots', 'robots', $entity->getMeta()->getRobots());
        $label = new Label('robots', 'pages:pageRobots', null, [], $this->translator);
        $help  = new Help('pages:pageRobotsHelp', null, [], $this->translator);

        return new FormGroup($input, $label, $help);
    }
}
