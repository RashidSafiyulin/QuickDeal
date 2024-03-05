# Инструкция по деплою

- Клонируем проект
- Устанавливаем зависимости - `composer install --no-dev`
- Копируем `.env.example` => `.env`, заполняем подключение к БД
- Генерируем ключ приложения - `php artisan key:generate`
- Прогоняем миграции - `php artisan migrate`
- Запускаем сервер - `php artisan serve`
