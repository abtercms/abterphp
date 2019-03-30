<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Navigation;

use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Constant\Session;
use AbterPhp\Framework\Html\Component;
use AbterPhp\Framework\Html\Contentless;
use AbterPhp\Framework\Html\Helper\StringHelper;
use AbterPhp\Framework\Html\IComponent;
use AbterPhp\Framework\Html\INode;
use AbterPhp\Framework\Html\INodeContainer;
use AbterPhp\Framework\Html\NodeContainerTrait;
use AbterPhp\Framework\Html\Tag;
use AbterPhp\Framework\I18n\ITranslator;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;

class UserBlock extends Tag implements INodeContainer
{
    const DEFAULT_TAG = Html5::TAG_A;

    const AVATAR_BASE_URL = 'https://www.gravatar.com/avatar/%1$s';

    /** @var ISession */
    protected $session;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var IComponent */
    protected $mediaLeft;

    /** @var IComponent */
    protected $mediaBody;

    /** @var IComponent */
    protected $mediaRight;

    use NodeContainerTrait;

    /**
     * UserBlock constructor.
     *
     * @param ISession     $session
     * @param UrlGenerator $urlGenerator
     * @param string|null  $content
     * @param string[]     $intents
     * @param array        $attributes
     * @param string|null  $tag
     */
    public function __construct(
        ISession $session,
        UrlGenerator $urlGenerator,
        ?string $content = null,
        array $intents = [],
        array $attributes = [],
        ?string $tag = null
    ) {
        $this->session      = $session;
        $this->urlGenerator = $urlGenerator;

        if (!$this->session || !$this->session->has(Session::USERNAME)) {
            throw new \LogicException('session must be set');
        }

        $username = (string)$this->session->get(Session::USERNAME, '');

        $this->mediaLeft  = new Component($this->getUserImage($username), [], [], Html5::TAG_DIV);
        $this->mediaBody  = new Component($username, [], [], Html5::TAG_DIV);
        $this->mediaRight = new Component(null, [], [], Html5::TAG_DIV);

        parent::__construct($content, $intents, $attributes, $tag);
    }

    /**
     * @param string $username
     *
     * @return INode
     */
    protected function getUserImage(string $username): INode
    {
        if (!$this->session->has(Session::EMAIL) || !$this->session->get(Session::IS_GRAVATAR_ALLOWED)) {
            return $this->getDefaultUserImage($username);
        }

        $emailHash = md5((string)$this->session->get(Session::EMAIL));
        $url       = sprintf(static::AVATAR_BASE_URL, $emailHash);

        $img     = new Contentless([], [Html5::ATTR_SRC => $url, Html5::ATTR_ALT => $username], Html5::TAG_IMG);
        $style   = sprintf('background: url(%1$s) no-repeat;', $url);
        $attribs = [Html5::ATTR_CLASS => 'user-img', Html5::ATTR_STYLE => $style];

        return new Component($img, [], $attribs, Html5::TAG_DIV);
    }

    /**
     * @param string $username
     *
     * @return INode
     */
    protected function getDefaultUserImage(string $username): INode
    {
        $url = 'https://via.placeholder.com/40/09f/fff.png';

        return new Contentless([Html5::ATTR_SRC => $url, Html5::ATTR_ALT => $username]);
    }

    /**
     * @param ITranslator|null $translator
     *
     * @return $this
     */
    public function setTranslator(?ITranslator $translator): INode
    {
        $this->translator = $translator;

        $nodes = $this->getNodes();
        foreach ($nodes as $node) {
            $node->setTranslator($translator);
        }

        return $this;
    }

    /**
     * @return INode[]
     */
    public function getNodes(): array
    {
        return [$this->mediaLeft, $this->mediaBody, $this->mediaRight];
    }

    /**
     * @return IComponent
     */
    public function getMediaLeft(): IComponent
    {
        return $this->mediaLeft;
    }

    /**
     * @param IComponent $mediaLeft
     */
    public function setMediaLeft(IComponent $mediaLeft): void
    {
        $this->mediaLeft = $mediaLeft;
    }

    /**
     * @return IComponent
     */
    public function getMediaBody(): IComponent
    {
        return $this->mediaBody;
    }

    /**
     * @param IComponent $mediaBody
     */
    public function setMediaBody(IComponent $mediaBody): void
    {
        $this->mediaBody = $mediaBody;
    }

    /**
     * @return IComponent
     */
    public function getMediaRight(): IComponent
    {
        return $this->mediaRight;
    }

    /**
     * @param IComponent $mediaRight
     */
    public function setMediaRight(IComponent $mediaRight): void
    {
        $this->mediaRight = $mediaRight;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $content[] = (string)$this->mediaLeft;
        $content[] = (string)$this->mediaBody;
        $content[] = (string)$this->mediaRight;

        return StringHelper::wrapInTag(implode("\n", $content), $this->tag, $this->attributes);
    }
}
