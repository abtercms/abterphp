<?php

declare(strict_types=1);

namespace AbterPhp\Files\Http\Controllers\Api\File;

use AbterPhp\Files\Service\File\Downloader as DownloadService;
use Casbin\Enforcer;
use Casbin\Exceptions\CasbinException;
use AbterPhp\Admin\Domain\Entities\User;
use AbterPhp\Admin\Orm\UserRepo;
use League\Flysystem\FileNotFoundException;
use Opulence\Http\Responses\Response;
use Opulence\Http\Responses\ResponseHeaders;
use Opulence\Http\Responses\StreamResponse;
use Opulence\Routing\Controller;
use Opulence\Sessions\ISession;

class Download extends Controller
{
    /** @var ISession */
    protected $session;

    /** @var Enforcer */
    protected $enforcer;

    /** @var UserRepo */
    protected $userRepo;

    /** @var DownloadService */
    protected $downloadService;

    /**
     * Download constructor.
     *
     * @param ISession        $session
     * @param Enforcer        $enforcer
     * @param UserRepo        $userRepo
     * @param DownloadService $downloadService
     */
    public function __construct(
        ISession $session,
        Enforcer $enforcer,
        UserRepo $userRepo,
        DownloadService $downloadService
    ) {
        $this->session         = $session;
        $this->enforcer        = $enforcer;
        $this->userRepo        = $userRepo;
        $this->downloadService = $downloadService;
    }

    /**
     * @param string $filesystemName
     *
     * @return Response
     */
    public function download(string $filesystemName): Response
    {
        $user = $this->getUser();

        try {
            $entity = $this->downloadService->getUserFile($filesystemName, $user);

            $streamCallable = $this->downloadService->getStream($entity);

            $this->downloadService->logDownload($entity, $user);
        } catch (CasbinException $e) {
            return new Response($e->getMessage(), ResponseHeaders::HTTP_UNAUTHORIZED);
        } catch (FileNotFoundException $e) {
            return new Response($e->getMessage(), ResponseHeaders::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new Response($e->getMessage(), ResponseHeaders::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new StreamResponse(
            $streamCallable,
            ResponseHeaders::HTTP_OK,
            $this->getHeaders($entity->getPublicName())
        );
    }

    /**
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if ($this->session->has(SESSION_USERNAME)) {
            $username = (string)$this->session->get(SESSION_USERNAME);

            return $this->userRepo->getByUsername($username);
        }

        return null;
    }

    /**
     * @param string $filename
     *
     * @return array
     */
    protected function getHeaders(string $filename): array
    {
        return [
            'Content-type'              => 'application/octet-stream',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-disposition'       => sprintf('attachment; filename=%s', $filename),
        ];
    }
}
