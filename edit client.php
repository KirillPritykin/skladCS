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

// Загрузка информации о клиенте по ID
$client = null;
$client_id = null;
if (isset($_GET['id'])) {
    $client_id = $conn_prit->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM клиенты WHERE ID='$client_id'";
    $result = $conn_prit->query($sql);

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
    } else {
        $client = null; // Клиент не найден
    }
}

// Обработка формы редактирования
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_client'])) {
        // Обновление информации о клиенте
        $client_surname = $conn_prit->real_escape_string($_POST['client_surname']);
        $client_name = $conn_prit->real_escape_string($_POST['client_name']);
        $client_phone = $conn_prit->real_escape_string($_POST['client_phone']);
        $client_request_date = $conn_prit->real_escape_string($_POST['client_request_date']);
        $client_description = $conn_prit->real_escape_string($_POST['client_description']);
        $client_service_price = $conn_prit->real_escape_string($_POST['client_service_price']);
        $client_diagnosis = $conn_prit->real_escape_string($_POST['client_diagnosis']);
        
        // SQL-запрос на обновление 
        $sql = "UPDATE клиенты SET 
                    Фамилия='$client_surname', 
                    Имя='$client_name', 
                    Телефон='$client_phone', 
                    Дата_обращения='$client_request_date',
                    `Описание проблемы`='$client_description', 
                    `Цена вопроса`='$client_service_price', 
                    Причина='$client_diagnosis' 
                WHERE ID='$client_id'";

        if ($conn_prit->query($sql) === TRUE) {
            echo "<script>alert('Информация о клиенте успешно обновлена.'); window.location.href='operator.php';</script>";
        } else {
            echo "Ошибка: " . $conn_prit->error;
        }
    } elseif (isset($_POST['delete_client'])) {
        // Удаление клиента
        $sql = "DELETE FROM клиенты WHERE ID='$client_id'";
        if ($conn_prit->query($sql) === TRUE) {
            echo "<script>alert('Клиент удален.'); window.location.href='operator.php';</script>";
        } else {
            echo "Ошибка при удалении клиента: " . $conn_prit->error;
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
    <title>Изменение данных клиента</title>
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
        input[type="text"], input[type="date"], input[type="tel"] {
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
        .delete-button {
            background-color: #f44336;
        }
        .delete-button:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Редактирование информации о клиенте</h2>

        <form method="GET" action="">
            <label for="client_id">Введите ID клиента, чтобы осуществить поиск:</label>
            <input type="text" id="client_id" name="id" value="<?= htmlspecialchars($client_id) ?>" placeholder="ID клиента" required>
            <button type="submit">Подтвердить</button>
        </form>

        <?php if ($client): ?>
            <form method="POST">
                <input type="text" name="client_surname" value="<?= htmlspecialchars($client['Фамилия']) ?>" placeholder="Фамилия" required>
                <input type="text" name="client_name" value="<?= htmlspecialchars($client['Имя']) ?>" placeholder="Имя" required>
                <input type="tel" name="client_phone" value="<?= htmlspecialchars($client['Телефон']) ?>" placeholder="Телефон" required>
                <input type="date" name="client_request_date" value="<?= htmlspecialchars($client['Дата_обращения']) ?>" required>
                <input type="text" name="client_description" value="<?= htmlspecialchars($client['Описание проблемы']) ?>" placeholder="Описание проблемы" required>
                <input type="text" name="client_service_price" value="<?= htmlspecialchars($client['Цена вопроса']) ?>" placeholder="Стоимость услуги" required>
                <input type="text" name="client_diagnosis" value="<?= htmlspecialchars($client['Причина']) ?>" placeholder="Причина" required>
                <button type="submit" name="update_client">Обновить информацию</button>
            </form>

            <form method="POST" style="margin-top: 10px;">
                <button type="submit" name="delete_client" class="delete-button" onclick="return confirm('Вы уверены, что хотите удалить этого клиента?');">Удалить клиента</button>
            </form>
        <?php else: ?>
            <p style="color: red;">Клиент не найден.</p>
        <?php endif; ?>
    </div>
</body>
</html>