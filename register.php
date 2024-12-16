<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .register-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            transition: transform 0.3s;
        }

        .register-container:hover {
            transform: scale(1.05);
        }

        .register-container img {
            width: 100%;
            border-radius: 12px 12px 0 0;
            margin-bottom: 20px;
        }

        h2 {
            color: #4CAF50;
            margin-bottom: 15px;
        }

        .info-text {
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 1.5;
            color: #555;
        }

        .email {
            margin: 10px 0;
            font-size: 20px; /* Размер шрифта для текста "Почта для связи" (такой же, как и для заголовка) */
            font-weight: bold; /* Сделать текст жирным */
            color: #4CAF50; /* Зеленый цвет текста */
        }

        a.mail-link {
            color: #fff;
            text-decoration: none;
            background-color: #4CAF50; /* Зеленый цвет кнопки */
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            padding: 10px 15px; /* Внутреннее расстояние */
            transition: background-color 0.3s;
        }

        a.mail-link:hover {
            background-color: #45a049; /* Более темный зеленый при наведении */
        }

        a {
            color: #fff;
            text-decoration: none;
            background-color: #007bff; /* Синий цвет кнопки */
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            padding: 10px 15px; /* Уменьшенное внутреннее расстояние */
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #0056b3; /* Более темный синий при наведении */
        }
    </style>
</head>

<body>
    <div class="register-container">
        <img src="рего.jpg" alt="Логотип">
        <h2>Вход в систему</h2>
        <p class="info-text">Чтобы войти в систему коллегам, не получившим "Логин" и "Пароль", необходимо связаться с администратором базы данных, либо авторизируйтесь как пользователь для просмотра актуальной информации.</p>
        
        <p class="email">Связь с администратором по кнопке</p>
        <a class="mail-link" href="mailto:kirya.morozov.05@internet.ru">kirya.morozov.05@internet.ru</a>
        
        <a href="on.php">Войти как гость</a>
    </div>
</body>

</html>

