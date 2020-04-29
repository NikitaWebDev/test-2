Задача: написать библиотеку для работы отправки почты.

На входе:
Tasks = '[
  {
    integration: {
      service: "mailerlite",
      apiKey: "1234567892267c15c570b1b4",
      groupId: 14391234,
    },
    lead: {
      name: "Вася",
      email: "vasya@platformalp.ru",
    },
  },
  {
    integration: {
      service: "mailchimp",
      apiKey: "33f401b1123456789-us03",
      listId: 123481,
    },
    lead: {
      name: "Петр",
      email: "petr@platformalp.ru",
    },
  }
]'
E-mail получателя: test@platformalp.ru
Заголовок письма: Заявка с сайта
Текст письма: some text

Перед отправкой email получателя проверяется на валидность.
Домен проверяется на наличие в черном списке ($domain = ['domain1.com', 'domain2.ru'])

Вместо отправки письма достаточно выводить в stdout: Email sent to <E-mail получателя> 