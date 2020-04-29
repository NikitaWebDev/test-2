<?php

namespace App\MailerTasksManager;

use App\MailerTasksManager\Services\{MailerLiteService, MailchimpService};
use Exception;

/**
 * Class TasksManager
 * @package App\MailerTasksManager
 */
class TasksManager
{
    protected array $tasks;

    /**
     * TasksManager constructor.
     *
     * @param string $tasks JSON
     * @return void
     */
    public function __construct(string $tasks)
    {
        $this->tasks = json_decode($tasks, true);
    }

    /**
     * @throws \MailerLiteApi\Exceptions\MailerLiteSdkException|Exception
     */
    public function run()
    {
        foreach ($this->tasks as $task) {
            switch ($task['integration']['service']) {
                case 'mailerlite':
                    (new MailerLiteService(
                        $task['integration']['apiKey'],
                        $task['integration']['groupId'],
                        new MessageBuilder($task['lead']['email'], $task['lead']['name'])
                    ))->send();

                    break;
                case 'mailchimp':
                    (new MailchimpService(
                        $task['integration']['apiKey'],
                        $task['integration']['listId'],
                        new MessageBuilder($task['lead']['email'], $task['lead']['name'])
                    ))->send();

                    break;
                default:
                    throw new Exception("Сервис {$task['integration']['service']} не поддерживается!");
            }
        }
    }
}
