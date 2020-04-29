<?php

namespace App\MailerTasksManager\Services;

use DrewM\MailChimp\MailChimp;
use Swift_Mailer;
use App\MailerTasksManager\Contracts\MessageBuilderContract;

/**
 * Class MailchimpService
 * @package App\MailerTasksManager\Services
 */
class MailchimpService extends AbstractMailerService
{
    protected ?Mailchimp $service;

    /**
     * MailchimpService constructor.
     *
     * @param string $apiKey
     * @param int $entityId
     * @param MessageBuilderContract $messageBuilder
     * @param Swift_Mailer|null $mailer
     * @param array $blackDomainList
     * @param mixed $stdin
     * @param mixed $stdout
     * @param mixed $stderr
     * @param MailChimp|null $service
     * @return void
     * @throws \Exception
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
        MailChimp $service = null
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
            $this->service = new MailChimp($this->apiKey);
        }
    }

    public function send(): void
    {
        foreach ($this->getMembers() as $member) {
            if (! $this->checkEmail($member['email_address'])) {
                $this->logError($member['email_address']);
                continue;
            }

            $message = $this->messageBuilder->build($member['email_address']);
            // делаем заглушку в виде stdout
//            $this->mailer->send($message);
            $this->logSuccess($member['email_address']);
        }
    }

    protected function getMembers(): array
    {
        return $this->service->get("lists/{$this->entityId}/members")['members'];
    }
}
