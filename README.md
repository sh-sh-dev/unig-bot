# unig-bot
Telegram bot interface for university classes (+ crawler)

## Installation
```bash
composer install
mv .env.example .env
```

## Configuration
Edit the `.env` file

- `TOKEN` - Telegram bot token
- `WEBHOOK_SECRET` - Random text to avoid fake requests
- `DB_{HOST,USER,PASS,NAME}` - Database properties
- `TIMEZONE` - Bot timezone
