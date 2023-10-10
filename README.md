# Телеграм-бот под API Beget

**⚠️ Бот не официальный. Пишите вопросы, предложения. Работает без SQL**

## Что есть в боте на этот момент:

- Работа с хостингом:
   - Работа с аккаунтом:
      - Тарифный план
      - Сервер
      - Переключение SSH
   - Бэкапы:
      - Все узлы на сентябрь 2023
   - Cron:
      - Все узлы на сентябрь 2023
- Мультиаккаунты
- Удаление всех данных (из бота)

## Краткая инструкция: 

1. Заполните все файлы с префиксом tg_ в папке DB
   - **tg_bot_base_directory** - если бот помещен не в корень, а в подпапку (включая /, например /beget_bot/subdir)
   - **tg_bot_owner** - Telegram ID пользователя
   - **tg_bot_secret_key** - Фрагмент для веб-хука для дополнительной проверки (?secret_key=YOUR_SECRET)
   - **tg_bot_token** - Токен бота, полученный у https://t.me/BotFather
   - **tg_bot_url** - URL до бота (не включая папку tg_bot_base_directory), нужен чтобы отправлять изображения или переназначать веб-хук
2. Направьте веб-хуки из Телеграма на index.php бота, примерно так: https://api.telegram.org/botYOUR_TOKEN/setwebhook?url=https://YOUR_SITE/BOT_DIR/index.php?secret_key=YOUR_SECRET
   -  **YOUR_TOKEN** - получите у https://t.me/BotFather
   -  **YOUR_SITE** - доменное имя, где бот лежит
   -  **BOT_DIR** - папка, где бот лежит, если он положен не в корень
   -  **YOUR_SECRET** - секретный ключ из tg_bot_secret_key
   -  **Важно наличие https (SSL)**

  
## Схема работы
![](https://github.com/Feelosov/beget_tg_bot/blob/main/beget_bot_struct.jpg)
