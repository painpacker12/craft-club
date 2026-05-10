# CI/CD Pipeline

## Ветки
- `main` - production
- `uat` - user acceptance testing  
- `dev` - development

## Шаги пайплайна
1. **Тесты** - PHPUnit с покрытием ≥50%
2. **Статический анализ** - PHPStan (Larastan)
3. **Линтинг** - Laravel Pint (PSR-12)
4. **Симуляция деплоя** - копирование .env файла
5. **Деплой в production** - только для main с ручным подтверждением

## Переменные окружения
- `.env.dev` - для ветки dev
- `.env.uat` - для ветки uat  
- `.env.prod` - для ветки main
- `.env.ci` - для пайплайна