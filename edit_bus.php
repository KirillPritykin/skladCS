<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: on.php");
    exit();
}

$servername_prit = "localhost";
$username_prit = "root";
$password_prit = "";
$dbname_prit = "ремонт_компьютерной_техники_притыкин";

$conn_prit = new mysqli($servername_prit, $username_prit, $password_prit, $dbname_prit);

if ($conn_prit->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn_prit->connect_error);
}

// Загрузка информации о клиенте по ID (если передан параметр id)
$bus = null;
$bus_id = null;
if (isset($_GET['id'])) {
    $bus_id = $conn_prit->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM клиенты WHERE ID='$bus_id'";
    $result = $conn_prit->query($sql);

    if ($result->num_rows > 0) {
        $bus = $result->fetch_assoc();
    } else {
        $bus = null; // Клиент не найден
    }
}

// Обработка формы редактирования
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_bus'])) {
    $bus_surname = $conn_prit->real_escape_string($_POST['bus_surname']);
    $bus_name = $conn_prit->real_escape_string($_POST['bus_name']);
    $bus_phone = $conn_prit->real_escape_string($_POST['bus_phone']);
    $bus_problem = $conn_prit->real_escape_string($_POST['bus_problem']);
    $bus_date = $conn_prit->real_escape_string($_POST['bus_date']);
    $bus_price = $conn_prit->real_escape_string($_POST['bus_price']); // Цена вопроса
    $bus_reason = $conn_prit->real_escape_string($_POST['bus_reason']); // Причина поломки

    // Исправленный SQL-запрос
    $sql = "UPDATE клиенты SET 
                Фамилия='$bus_surname', 
                Имя='$bus_name', 
                Телефон='$bus_phone', 
                `Описание проблемы`='$bus_problem', 
                `Дата_обращения`='$bus_date', 
                `Цена вопроса`='$bus_price', 
                `Причина`='$bus_reason'  -- Новое поле
            WHERE ID='$bus_id'";

    if ($conn_prit->query($sql) === TRUE) {
        echo "<script>alert('Информация о клиенте успешно обновлена.'); window.location.href='view_buses.php';</script>";
    } else {
        echo "Ошибка: " . $conn_prit->error; // Для отображения ошибки SQL
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
    <title>Редактирование информации о клиенте</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #ffcc00;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="tel"] {
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
        .back-btn {
            background-color: #e7e7e7;
            color: black;
        }
        .back-btn:hover {
            background-color: #d4d4d4;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Редактирование информации о клиенте</h2>

        <form method="GET" action="">
            <label for="bus_id">Введите ID клиента, чтобы осуществить поиск:</label>
            <input type="text" id="bus_id" name="id" value="<?= htmlspecialchars($bus_id) ?>" placeholder="ID клиента" required>
            <button type="submit">Подтвердить</button>
        </form>

        <?php if ($bus): ?>
            <form method="POST">
                <table>
                    <tr>
                        <td><label for="bus_surname">Фамилия</label></td>
                        <td><input type="text" id="bus_surname" name="bus_surname" value="<?= htmlspecialchars($bus['Фамилия']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="bus_name">Имя</label></td>
                        <td><input type="text" id="bus_name" name="bus_name" value="<?= htmlspecialchars($bus['Имя']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="bus_phone">Телефон</label></td>
                        <td><input type="tel" id="bus_phone" name="bus_phone" value="<?= htmlspecialchars($bus['Телефон']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="bus_problem">Описание проблемы</label></td>
                        <td><input type="text" id="bus_problem" name="bus_problem" value="<?= htmlspecialchars($bus['Описание проблемы']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="bus_date">Дата обращения</label></td>
                        <td><input type="text" id="bus_date" name="bus_date" value="<?= htmlspecialchars($bus['Дата_обращения']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="bus_price">Цена вопроса</label></td>
                        <td><input type="text" id="bus_price" name="bus_price" value="<?= htmlspecialchars($bus['Цена вопроса']) ?>" required></td>
                    </tr>
                    <td><label for="bus_reason">Причина после диагностики:</label></td>
            <td><input type="text" id="bus_reason" name="bus_reason" value="<?= htmlspecialchars($bus['Причина']) ?>" required></td>
        </tr>
                </table>
                <button type="submit" name="update_bus">Обновить информацию</button>
            </form>
        <?php else: ?>
            <p style="color: red;">Клиент не найден.</p>
        <?php endif; ?>

        <button class="back-btn" onclick="window.location.href='view_buses.php'">Вернуться к списку клиентов</button>
    </div>
</body>
</html>
