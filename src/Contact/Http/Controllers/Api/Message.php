<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Http\Controllers\Api;

use AbterPhp\Admin\Http\Controllers\ApiDataTrait;
use AbterPhp\Admin\Http\Controllers\ApiIssueTrait;
use AbterPhp\Contact\Domain\Entities\Message as Entity;
use AbterPhp\Contact\Service\Execute\Message as MessageService;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Routing\Controller;
use Psr\Log\LoggerInterface;

class Message extends Controller
{
    use ApiIssueTrait;
    use ApiDataTrait;

    public const ENTITY_SINGULAR = 'message';
    public const ENTITY_PLURAL   = 'messages';

    protected const LOG_MSG_CREATE_FAILURE = 'Creating %1$s failed.';
    protected const LOG_CONTEXT_EXCEPTION  = 'Exception';
    protected const LOG_PREVIOUS_EXCEPTION = 'Previous exception #%d';

    protected LoggerInterface $logger;

    protected MessageService $messageService;

    /**
     * Message constructor.
     *
     * @param LoggerInterface $logger
     * @param MessageService  $messageService
     * @param string          $problemBaseUrl
     */
    public function __construct(
        LoggerInterface $logger,
        MessageService $messageService,
        string $problemBaseUrl
    ) {
        $this->logger         = $logger;
        $this->messageService = $messageService;
        $this->problemBaseUrl = $problemBaseUrl;
    }

    /**
     * @return Response
     */
    public function create(): Response
    {
        try {
            $data = $this->getCreateData();

            $formIdentifier = $data['form_id'];

            $errors = $this->messageService->validateForm($formIdentifier, $data);

            if (count($errors) > 0) {
                $msg = sprintf(static::LOG_MSG_CREATE_FAILURE, static::ENTITY_SINGULAR);

                return $this->handleErrors($msg, $errors);
            }

            $entity = $this->messageService->createEntity('');
            $entity = $this->messageService->fillEntity($formIdentifier, $entity, $data, []);

            assert($entity instanceof Entity, new \RuntimeException('Invalid entity.'));

            $this->messageService->send($entity);
        } catch (\Exception $e) {
            $msg = sprintf(static::LOG_MSG_CREATE_FAILURE, static::ENTITY_SINGULAR);

            return $this->handleException($msg, $e);
        }

        return $this->handleCreateSuccess();
    }

    /**
     * @return Response
     */
    protected function handleCreateSuccess(): Response
    {
        $response = new Response();
        $response->setStatusCode(ResponseHeaders::HTTP_NO_CONTENT);

        return $response;
    }

    /**
     * @return Response
     */
    protected function handleNotImplemented(): Response
    {
        $response = new Response();
        $response->setStatusCode(ResponseHeaders::HTTP_NOT_IMPLEMENTED);

        return $response;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $entityId
     *
     * @return Response
     */
    public function get(string $entityId): Response
    {
        return $this->handleNotImplemented();
    }

    /**
     * @return Response
     */
    public function list(): Response
    {
        return $this->handleNotImplemented();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $entityId
     *
     * @return Response
     */
    public function update(string $entityId): Response
    {
        return $this->handleNotImplemented();
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string $entityId
     *
     * @return Response
     */
    public function delete(string $entityId): Response
    {
        return $this->handleNotImplemented();
    }
}
