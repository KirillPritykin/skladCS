<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .recover-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
        }

        .recover-container img {
            width: 100%;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }

        .input-group {
            width: 100%;
            margin: 10px 0;
            text-align: left;
            position: relative;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
        }

        .input-group img {
            position: absolute;
            left: 10px; /* Отступ от левой стороны */
            top: 30px; /* Центрирование иконки по высоте поля ввода */
            width: 20px; /* Ширина иконки */
            color: #aaa; /* Цвет иконки при использовании иконки FontAwesome */
        }

        input[type="email"] {
            width: 100%;
            padding: 10px 10px 10px 40px; /* Увеличиваем левое внутреннее расстояние для иконки */
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
            display: block;
            margin-top: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="recover-container">
    <img src="во.jpg" alt="Форма" />
    <h2>Восстановление пароля</h2>
    <form action="" method="POST">
        <div class="input-group">
            <label for="email">Введите email</label>
            <img src="icon.png" alt="Иконка"> <!-- Ваш значок -->
            <input type="email" name="email" id="email" required>
        </div>
        <input type="submit" value="Отправить ссылку для восстановления">
    </form>

    <?php
    if (!empty($_POST['email'])) {
        $email = $_POST['email'];
        // Обработка восстановления пароля
        echo "<p style='color:green;'>Ссылка для восстановления пароля отправлена на ваш email, следуйте инструкции.</p>";
    }
    ?>

    <p><a href="on.php">Вернуться к входу</a></p>
</div>

</body>
</html>