<?php
session_start();

// Подключение к базе данных
$servername = "localhost";
$db_username = "root"; // Имя пользователя базы данных
$db_password = ""; // Пароль базы данных
$dbname = "bus"; // Имя базы данных

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Проверяем метод запроса
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Используем подготовленный запрос
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Проверяем без хеширования
        if ($user['password'] === $password) {
            $_SESSION['user_id'] = $user['id']; // Сохраняем ID пользователя в сессии
            $_SESSION['role'] = $user['role']; // Сохраняем роль пользователя в сессии
            
            // Перенаправление на соответствующую страницу в зависимости от роли
            if ($user['role'] === 'оператор') {
                header("Location: operator.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        } else {
            $error = "Неправильный логин или пароль.";
        }
    } else {
        $error = "Неправильный логин или пароль.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> ООО "Цифровые системы"</title>
    <style>
        body {
            font-family: 'Times New Roman', serif; 
            font-size: 14px; /* Установка размера шрифта 14px */
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative; 
            overflow: hidden; 
        }

        .login-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 300px;
            text-align: center;
            z-index: 1; 
        }

        .login-container img {
            width: 100%;
            border-radius: 8px 8px 0 0;
            margin-bottom: 20px;
        }

        .login-container h2 {
            margin-bottom: 20px;
        }

        .input-group {
            width: 100%;
            margin: 10px 0;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50; /* Зеленая кнопка "Войти" */
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%; /* Установка ширины для кнопки Войти */
            margin: 5px 0;
            font-size: 14px; /* Размер шрифта кнопки */
            font-family: 'Times New Roman', serif; /* Шрифт для кнопки */
        }

        input[type="submit"]:hover {
            background-color: #45a049; /* Более тёмный зеленый при наведении */
        }

        .login-button {
            background-color: rgba(0, 123, 255, 0.8); /* Цвет для кнопки "Войти гостем" */
            padding: 10px;
            border-radius: 4px;
            color: white;
            text-align: center;
            border: none;
            cursor: pointer;
            width: 100%; /* Установка ширины для кнопки Войти гостем */
            margin: 5px 0;
            font-size: 14px; /* Размер шрифта кнопки */
            font-family: 'Times New Roman', serif; /* Шрифт для кнопки */
        }

        .login-button:hover {
            background-color: rgba(0, 105, 217, 1); /* Более темный синий при наведении */
        }

        p {
            color: red;
        }

        .link-buttons {
            margin-top: 10px;
            text-align: center;
        }

        .link-buttons a {
            color: #4CAF50; /* Зеленый цвет для ссылок */
            text-decoration: none;
            display: block;
            margin: 5px 0;
            padding: 5px 0; /* Уменьшенные отступы */
            width: 100%; /* Установка ширины для ссылок */
            text-align: center; 
        }

        .link-buttons a:hover {
            color: #2e7d32; /* Темно-зеленый цвет при наведении */
        }

        .snowflake {
            position: absolute;
            color: lightblue; 
            font-size: 1.5em; 
            opacity: 0.8; 
            pointer-events: none; 
            animation: fall linear infinite; 
        }

        @keyframes fall {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(100vh); 
            }
        }

        .bus {
            position: absolute;
            width: 100px; 
            z-index: 0; 
        }

        .bus-top {
            top: 20px; 
            animation: drive-top 10s linear infinite; 
        }

        .bus-bottom {
            bottom: 20px; 
            animation: drive-bottom 10s linear infinite; 
        }

        @keyframes drive-top {
            0% {
                left: -120px; 
            }

            100% {
                left: 100vw; 
            }
        }

        @keyframes drive-bottom {
            0% {
                left: -120px; 
            }

            100% {
                left: 100vw; 
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <img src="ЦС.jpg" alt="Логотип">
        <h2> "ООО Цифровые системы" </h2>
        <form method="POST" action="">
            <div class="input-group">
                <label for="username">Введите логин:</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Введите пароль:</label>
                <input type="password" name="password" required>
            </div>
            <input type="submit" value="Войти">
            <button class="login-button" type="button" onclick="location.href='Пользователь.html'">Войти гостем</button>
            <?php if (isset($error)): ?>
                <p><?php echo $error; ?></p>
            <?php endif; ?>
        </form>
        <div class="link-buttons">
            <a href="register.php">Регистрация</a>
            <a href="recover.php">Восстановление пароля</a>
        </div>
    </div>

    <img src="СК.png" alt="Автобус" class="bus bus-top"> 
    <img src="ИН.png" alt="Автобус" class="bus bus-bottom">

    <script>
        function createSnowflake() {
            const snowflake = document.createElement('div');
            snowflake.className = 'snowflake';
            snowflake.innerHTML = '&#10052;'; 
            snowflake.style.left = Math.random() * 100 + 'vw'; 
            snowflake.style.animationDuration = (Math.random() * 3 + 3) + 's'; 
            snowflake.style.fontSize = (Math.random() * 1 + 1) + 'em'; 

            document.body.appendChild(snowflake);

            snowflake.addEventListener('animationend', () => {
                snowflake.remove();
            });
        }

        setInterval(createSnowflake, 300);
    </script>
</body>

</html>
