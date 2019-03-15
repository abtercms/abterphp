<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Http\Controllers\Admin;

use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\Form\Factory\IFormFactory;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Orm\IGridRepo;
use AbterPhp\Framework\Session\FlashService;
use Casbin\Exceptions\CasbinException;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Http\Responses\Response;
use Opulence\Orm\OrmException;
use Opulence\Routing\Urls\URLException;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class FormAbstract extends AdminAbstract
{
    use UrlTrait;
    use MessageTrait;

    const ENTITY_TITLE_SINGULAR = '';

    const VIEW_FORM = 'contents/backend/form';

    const VAR_ENTITY = 'entity';
    const VAR_FORM   = 'form';

    const TITLE_NEW  = 'framework:titleNew';
    const TITLE_EDIT = 'framework:titleEdit';

    const URL_NEW = '%s-new';

    /** @var IGridRepo */
    protected $repo;

    /** @var ISession */
    protected $session;

    /** @var IFormFactory */
    protected $formFactory;

    /**
     * FormAbstract constructor.
     *
     * @param FlashService $flashService
     * @param ITranslator  $translator
     * @param UrlGenerator $urlGenerator
     * @param IGridRepo    $repo
     * @param ISession     $session
     * @param IFormFactory $formFactory
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        IGridRepo $repo,
        ISession $session,
        IFormFactory $formFactory
    ) {
        parent::__construct($flashService, $translator, $urlGenerator);

        $this->repo        = $repo;
        $this->session     = $session;
        $this->formFactory = $formFactory;
    }

    /**
     * @return Response
     * @throws CasbinException
     * @throws URLException
     * @throws \Throwable
     */
    public function new(): Response
    {
        $entity = $this->createEntity();

        $url   = $this->urlGenerator->createFromName(sprintf(static::URL_NEW, static::ENTITY_PLURAL));
        $title = $this->translator->translate(static::TITLE_NEW, static::ENTITY_TITLE_SINGULAR);
        $form  = $this->formFactory->create($url, RequestMethods::POST, $this->getShowUrl(), $entity);

        $this->view = $this->viewFactory->createView(static::VIEW_FORM);
        $this->view->setVar(static::VAR_ENTITY, $entity);
        $this->view->setVar(static::VAR_FORM, $form);

        $this->addCustomAssets($entity);

        return $this->createResponse($title);
    }

    /**
     * @param int $entityId
     *
     * @return Response
     * @throws CasbinException
     * @throws URLException
     * @throws \Throwable
     */
    public function edit(int $entityId): Response
    {
        $entity = $this->retrieveEntity($entityId);

        $url   = $this->getEditUrl($entityId);
        $title = $this->translator->translate(static::TITLE_EDIT, static::ENTITY_TITLE_SINGULAR, (string)$entity);
        $form  = $this->formFactory->create($url, RequestMethods::PUT, $this->getShowUrl(), $entity);

        $this->view = $this->viewFactory->createView(sprintf(static::VIEW_FORM, strtolower(static::ENTITY_SINGULAR)));
        $this->view->setVar(static::VAR_ENTITY, $entity);
        $this->view->setVar(static::VAR_FORM, $form);

        $this->addCustomAssets($entity);

        return $this->createResponse($title);
    }

    /**
     * @param int|null $entityId
     *
     * @return IStringerEntity
     */
    public function retrieveEntity(int $entityId = null): IStringerEntity
    {
        /** @var FlashService $flashService */
        $flashService = $this->flashService;

        try {
            /** @var IStringerEntity $entity */
            $entity = $this->repo->getById($entityId);
        } catch (OrmException $e) {
            $errorMessage = $this->getMessage(static::ENTITY_LOAD_FAILURE);

            $flashService->mergeErrorMessages([$errorMessage]);

            return $this->createEntity();
        }

        return $entity;
    }

    /**
     * @param int|null $entityEntityId
     *
     * @return IStringerEntity
     */
    abstract protected function createEntity(int $entityEntityId = null): IStringerEntity;
}
