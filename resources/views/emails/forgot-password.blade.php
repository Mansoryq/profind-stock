<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Восстановление пароля</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
        .warning {
            background: #fef3cd;
            border: 1px solid #fbbf24;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">ProfGid.</div>
    </div>

    <div class="content">
        <h2>Восстановление пароля</h2>
        
        <p>Здравствуйте, {{ $user->name }}!</p>
        
        <p>Вы запросили восстановление пароля для вашего аккаунта на сайте {{ config('app.name') }}.</p>
        
        <p>Для сброса пароля нажмите на кнопку ниже:</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/reset-password/' . $token . '?email=' . urlencode($user->email)) }}" class="button">
                Сбросить пароль
            </a>
        </div>
        
        <div class="warning">
            <strong>Важно:</strong> Ссылка действительна в течение 60 минут. Если вы не запрашивали сброс пароля, просто проигнорируйте это сообщение.
        </div>
        
        <p>Если кнопка не работает, скопируйте и вставьте эту ссылку в браузер:</p>
        <p style="word-break: break-all; color: #3b82f6;">
            {{ url('/reset-password/' . $token . '?email=' . urlencode($user->email)) }}
        </p>
    </div>

    <div class="footer">
        <p>С уважением,<br>Команда {{ config('app.name') }}</p>
        <p>Это автоматическое сообщение, пожалуйста, не отвечайте на него.</p>
    </div>
</body>
</html>
