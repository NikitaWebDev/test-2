<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;
use App\Config;

(new Dotenv())->load($_SERVER['DOCUMENT_ROOT'] . '/../' . '.env');
Config::init($_ENV);

// todo test
/*
$text = <<<TEXT
[
  {
    "integration": {
      "service": "mailerlite",
      "apiKey": "1234567892267c15c570b1b4",
      "groupId": 14391234
    },
    "lead": {
      "name": "Вася",
      "email": "vasya@platformalp.ru"
    }
  },
  {
    "integration": {
      "service": "mailchimp",
      "apiKey": "33f401b1123456789-us03",
      "listId": 123481
    },
    "lead": {
      "name": "Петр",
      "email": "petr@platformalp.ru"
    }
  }
]
TEXT;

(new \App\MailerTasksManager\TasksManager($text))->run();
*/
