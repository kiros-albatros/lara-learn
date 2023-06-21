# ВсеКолёса.ру Admin

## Установка

1. Загрузить backend-зависимости, чтобы был Laravel и Sail, входящий в него

```bash
composer install
```

2. Установить [Sail](https://laravel.com/docs/10.x/sail)

```bash
echo "alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'" >>~/.bash_profile
source ~/.bash_profile
```

3. Создать первоначальный файл конфигурации среды

```bash
cp .env.example .env
```

4. Запустить backend в docker в первый раз

```bash
sail up -d
```

5. Сгенерировать стартовые данные для Базы Данных

```bash
sail artisan migrate:fresh --seed
```

Добавился пользователь: **admin@vsekolesa.ru** / **admin** , залогиниться можно будет после завершения установки.

6. Загрузить frontend-зависимости

```bash
sail npm install
```

7. Запустить frontend в первый раз

```bash
sail npm run dev
```

8. Открыть в браузере http://0.0.0.0/

## Запуск

```bash
# Back
sail up -d

# Первоначальная миграция
sail artisan migrate

# Front (vite compiler)
sail npm run dev
```

Открыть в браузере: http://0.0.0.0/

## Завершение работы

```bash
# Прервать процесc npm run dev
Ctrl+C 

# Завершить docker
sail down
```
