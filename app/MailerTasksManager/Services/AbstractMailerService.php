<?php

namespace App\MailerTasksManager\Services;

use Swift_Mailer, Swift_SendmailTransport;
use App\Config;
use App\MailerTasksManager\Contracts\MessageBuilderContract;

/**
 * Class AbstractMailerService
 * @package App\MailerTasksManager\Services
 */
abstract class AbstractMailerService
{
    protected string $apiKey;
    /** @var int groupId или listId */
    protected int $entityId;
    protected MessageBuilderContract $messageBuilder;
    protected ?Swift_Mailer $mailer;
    protected array $blackDomainList;
    /** @var resource|bool|null */
    protected $stdin;
    /** @var resource|bool|null */
    protected $stdout;
    /** @var resource|bool|null */
    protected $stderr;

    /**
     * AbstractMailerService constructor.
     *
     * @param string $apiKey
     * @param int $entityId
     * @param MessageBuilderContract $messageBuilder
     * @param Swift_Mailer|null $mailer
     * @param array $blackDomainList
     * @param mixed $stdin
     * @param mixed $stdout
     * @param mixed $stderr
     * @return void
     */
    public function __construct(
        string $apiKey,
        int $entityId,
        MessageBuilderContract $messageBuilder,
        Swift_Mailer $mailer = null,
        array $blackDomainList = [],
        $stdin = null,
        $stdout = null,
        $stderr = null
    ) {
        $this->apiKey = $apiKey;
        $this->entityId = $entityId;
        $this->messageBuilder = $messageBuilder;
        $this->mailer = $mailer;
        $this->blackDomainList = $blackDomainList;
        $this->stdin = $stdin;
        $this->stdout = $stdout;
        $this->stderr = $stderr;

        if (is_null($this->mailer)) {
            $this->mailer = new Swift_Mailer(new Swift_SendmailTransport('/usr/sbin/sendmail -bs'));
        }

        if (empty($this->blackDomainList)) {
            $this->blackDomainList = Config::get('MAIL_BLACK_DOMAIN_LIST');
        }

        if (is_null($this->stdin)) {
            $this->stdin = fopen('/dev/null', 'r');
        }

        if (is_null($this->stdout)) {
            $this->stdout = fopen($_SERVER['DOCUMENT_ROOT'] . '/../logs/mail_success.log', 'a');
        }

        if (is_null($this->stderr)) {
            $this->stderr = fopen($_SERVER['DOCUMENT_ROOT'] . '/../logs/mail_error.log', 'a');
        }
    }

    abstract public function send(): void;

    protected function checkEmail(string $email): bool
    {
        $domain = explode('@', $email)[1];

        return filter_var($email, FILTER_VALIDATE_EMAIL) && ! in_array($domain, $this->blackDomainList);
    }

    /**
     * @param string $email
     * @return false|int
     */
    protected function logSuccess(string $email)
    {
        return fwrite($this->stdout, "Email sent to $email.\r\n");
    }

    /**
     * @param string $email
     * @return false|int
     */
    protected function logError(string $email)
    {
        return fwrite(STDERR, "Email $email is invalid.");
    }
}
