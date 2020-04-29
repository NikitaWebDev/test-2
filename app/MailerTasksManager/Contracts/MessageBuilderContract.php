<?php

namespace App\MailerTasksManager\Contracts;

/**
 * Interface MessageBuilderContract
 * @package App\MailerTasksManager\Contracts
 */
interface MessageBuilderContract
{
    public function build(string $emailTo, string $nameTo = null): \Swift_Message;
}
