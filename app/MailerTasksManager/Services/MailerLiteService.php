<?php

namespace App\MailerTasksManager\Services;

use MailerLiteApi\MailerLite;
use Swift_Mailer;
use App\MailerTasksManager\Contracts\MessageBuilderContract;

/**
 * Class MailerLiteService
 * @package App\MailerTasksManager\Services
 */
class MailerLiteService extends AbstractMailerService
{
    protected ?MailerLite $service;

    /**
     * MailerLiteService constructor.
     *
     * @param string $apiKey
     * @param int $entityId
     * @param MessageBuilderContract $messageBuilder,
     * @param Swift_Mailer $mailer
     * @param array $blackDomainList
     * @param mixed $stdin
     * @param mixed $stdout
     * @param mixed $stderr
     * @param MailerLite|null $service
     * @return void
     * @throws \MailerLiteApi\Exceptions\MailerLiteSdkException
     */
    public function __construct(
        string $apiKey,
        int $entityId,
        MessageBuilderContract $messageBuilder,
        Swift_Mailer $mailer = null,
        array $blackDomainList = [],
        $stdin = null,
        $stdout = null,
        $stderr = null,
        MailerLite $service = null
    ) {
        parent::__construct(
            $apiKey,
            $entityId,
            $messageBuilder,
            $mailer,
            $blackDomainList,
            $stdin,
            $stdout,
            $stderr
        );

        $this->service = $service;

        if (is_null($this->service)) {
            $this->service = new MailerLite($this->apiKey);
        }
    }

    public function send(): void
    {
        foreach ($this->getSubscribers() as $subscriber) {
            if (! $this->checkEmail($subscriber['email'])) {
                $this->logError($subscriber['email']);
                continue;
            }

            $message = $this->messageBuilder->build($subscriber['email'], $subscriber['name']);
            // делаем заглушку в виде stdout
//            $this->mailer->send($message);
            $this->logSuccess($subscriber['email']);
        }
    }

    protected function getSubscribers(): array
    {
        /** @var string $subscribers JSON */
        $subscribers = $this->service->groups()->getSubscribers($this->entityId);

        return json_decode($subscribers, true);
    }
}
