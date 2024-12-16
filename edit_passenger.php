<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: on.php");
    exit();
}

if ($_SESSION['role'] !== 'оператор') {
    header("Location: dashboard.php");
    exit();
}

$servername_prit = "localhost";
$username_prit = "root";
$password_prit = "";
$dbname_prit = "ремонт_компьютерной_техники_притыкин"; // Измените на правильное имя базы данных

$conn_prit = new mysqli($servername_prit, $username_prit, $password_prit, $dbname_prit);

if ($conn_prit->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn_prit->connect_error);
}

// Загрузка информации об обращении по ID
$appeal = null;
$appeal_id = null;
if (isset($_GET['id'])) {
    $appeal_id = $conn_prit->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM ОбращениеЦС WHERE ID='$appeal_id'";
    $result = $conn_prit->query($sql);

    if ($result->num_rows > 0) {
        $appeal = $result->fetch_assoc();
    } else {
        $appeal = null; // Обращение не найдено
    }
}

// Обработка формы редактирования
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_appeal'])) {
        // Обновление информации о обращении
        $appeal_surname = $conn_prit->real_escape_string($_POST['appeal_surname']);
        $appeal_name = $conn_prit->real_escape_string($_POST['appeal_name']);
        $appeal_phone = $conn_prit->real_escape_string($_POST['appeal_phone']);
        $appeal_description = $conn_prit->real_escape_string($_POST['appeal_description']);

        // Исправленный SQL-запрос без даты обращения
        $sql = "UPDATE ОбращениеЦС SET 
                    Фамилия='$appeal_surname', 
                    Имя='$appeal_name', 
                    Телефон='$appeal_phone', 
                    `Описание проблемы`='$appeal_description' 
                WHERE ID='$appeal_id'";

        if ($conn_prit->query($sql) === TRUE) {
            echo "<script>alert('Информация о обращении успешно обновлена.'); window.location.href='operator.php';</script>";
        } else {
            echo "Ошибка: " . $conn_prit->error;
        }
    } elseif (isset($_POST['delete_appeal'])) {
        // Удаление обращения
        $sql = "DELETE FROM ОбращениеЦС WHERE ID='$appeal_id'";
        if ($conn_prit->query($sql) === TRUE) {
            echo "<script>alert('Обращение удалено.'); window.location.href='operator.php';</script>";
        } else {
            echo "Ошибка при удалении: " . $conn_prit->error;
        }
    }
}

// Закрытие соединения с базой данных
$conn_prit->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменение данных обращения</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #2c3e50;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="text"],
        input[type="tel"] {
            width: calc(100% - 24px);
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-button {
            background-color: #e7e7e7;
            color: black;
        }
        .back-button:hover {
            background-color: #d4d4d4;
        }
        .delete-button {
            background-color: #f44336; /* Красный цвет для кнопки удаления */
        }
        .delete-button:hover {
            background-color: #d32f2f; /* Темно-красный цвет при наведении */
        }
        .view-appeals-button {
            background-color: #2196F3; /* Синий цвет для кнопки просмотра */
            margin-top: 10px;
        }
        .view-appeals-button:hover {
            background-color: #1976D2; /* Темно-синий цвет при наведении */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Редактирование информации о обращении</h2>

        <form method="GET" action="">
            <label for="appeal_id">Введите ID обращения, чтобы осуществить поиск:</label>
            <input type="text" id="appeal_id" name="id" value="<?= htmlspecialchars($appeal_id) ?>" placeholder="ID обращения" required>
            <button type="submit">Подтвердить</button>
        </form>

        <?php if ($appeal): ?>
            <form method="POST">
                <table>
                    <tr>
                        <td><label for="appeal_surname">Фамилия</label></td>
                        <td><input type="text" id="appeal_surname" name="appeal_surname" value="<?= htmlspecialchars($appeal['Фамилия']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="appeal_name">Имя</label></td>
                        <td><input type="text" id="appeal_name" name="appeal_name" value="<?= htmlspecialchars($appeal['Имя']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="appeal_phone">Телефон</label></td>
                        <td><input type="tel" id="appeal_phone" name="appeal_phone" value="<?= htmlspecialchars($appeal['Телефон']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="appeal_description">Описание проблемы</label></td>
                        <td><input type="text" id="appeal_description" name="appeal_description" value="<?= htmlspecialchars($appeal['Описание проблемы']) ?>" required></td>
                    </tr>
                </table>
                <button type="submit" name="update_appeal">Обновить информацию</button>
            </form>

            <form method="POST" style="margin-top: 10px;">
                <button type="submit" name="delete_appeal" class="delete-button" onclick="return confirm('Вы уверены, что хотите удалить это обращение?');">Удалить обращение</button>
            </form>

        <?php else: ?>
            <p style="color: red;">Обращение не найдено.</p>
        <?php endif; ?>

        <button class="view-appeals-button" onclick="window.open('view_passengers.php', '_blank');">Просмотреть обращения</button>
        <button class="back-button" onclick="window.location.href='operator.php'">Назад</button>
    </div>
</body>
</html>