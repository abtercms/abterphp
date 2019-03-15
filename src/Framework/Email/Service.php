<?php

declare(strict_types=1);

namespace AbterPhp\Framework\Email;

use Swift_Mailer;
use Swift_Message;
use Swift_Transport;

class Service
{
    /** @var Swift_Mailer */
    protected $mailer;

    /** @var array */
    protected $failedRecipients = [];

    /**
     * Contact constructor.
     *
     * @param Swift_Transport $transport
     */
    public function __construct(Swift_Transport $transport)
    {
        $this->mailer = new Swift_Mailer($transport);
    }

    /**
     * @param string $subject
     * @param string $body
     * @param array  $recipients
     * @param array  $senders
     * @param array  $replyTo
     *
     * @return int
     */
    public function send(string $subject, string $body, array $recipients, array $senders, array $replyTo): int
    {
        $message = (new Swift_Message($subject))->setBody($body)->setFrom($senders)->setReplyTo($replyTo);

        $this->failedRecipients = [];

        $numSent = 0;
        foreach ($recipients as $address => $name) {
            if (is_int($address)) {
                $message->setTo($name);
            } else {
                $message->setTo([$address => $name]);
            }

            $numSent += $this->mailer->send($message, $this->failedRecipients);
        }

        return $numSent;
    }

    /**
     * @return array
     */
    public function getFailedRecipients(): array
    {
        return $this->failedRecipients;
    }
}
