<?php

namespace App\MailerTasksManager;

use App\Config;
use App\MailerTasksManager\Contracts\MessageBuilderContract;
use Swift_Message;

/**
 * Class MessageBuilder
 * @package App\MailerTasksManager
 */
class MessageBuilder implements MessageBuilderContract
{
    protected string $emailFrom;
    protected string $nameFrom;
    protected string $title;
    protected string $body;

    /**
     * MessageBuilder constructor.
     *
     * @param string $emailFrom
     * @param string $nameFrom
     * @param string $title
     * @param string $body
     * @return void
     */
    public function __construct(string $emailFrom, string $nameFrom, string $title = '', string $body = '')
    {
        $this->emailFrom = $emailFrom;
        $this->nameFrom = $nameFrom;
        $this->title = $title;
        $this->body = $body;

        if (empty($this->title)) {
            $this->title = Config::get('MAIL_TITLE');
        }

        if (empty($this->body)) {
            $this->body = Config::get('MAIL_BODY');
        }
    }

    public function build(string $emailTo, string $nameTo = null): Swift_Message
    {
        $dataTo = ! is_null($nameTo) ? [$emailTo => $nameTo] : $emailTo;

        return (new Swift_Message($this->title))
            ->setFrom([$this->emailFrom => $this->nameFrom])
            ->setTo($dataTo)
            ->setBody($this->body);
    }
}
