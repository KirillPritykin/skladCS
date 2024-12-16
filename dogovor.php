<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: on.php");
    exit();
}

$servername_prit = "localhost";
$username_prit = "root";
$password_prit = "";
$dbname_prit = "ремонт_компьютерной_техники_притыкин"; // Проверьте, что имя базы данных введено правильно

$conn_prit = new mysqli($servername_prit, $username_prit, $password_prit, $dbname_prit);

if ($conn_prit->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn_prit->connect_error);
}

// Загрузка информации о договоре услуг по ID (если передан параметр id)
$contract = null;
$contract_id = null;

if (isset($_GET['id'])) {
    $contract_id = $conn_prit->real_escape_string($_GET['id']);
    $sql = "SELECT * FROM `договор услуг` WHERE ID='$contract_id'";
    $result = $conn_prit->query($sql);

    if (!$result) {
        die("Ошибка при выполнении запроса: " . $conn_prit->error);
    }

    if ($result->num_rows > 0) {
        $contract = $result->fetch_assoc();
    } else {
        echo "<p style='color: red;'>Договор не найден.</p>";
    }
}

// Обработка формы редактирования
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_contract'])) {
    $contract_surname = $conn_prit->real_escape_string($_POST['contract_surname']);
    $contract_name = $conn_prit->real_escape_string($_POST['contract_name']);
    $contract_phone = $conn_prit->real_escape_string($_POST['contract_phone']);
    $contract_service_number = $conn_prit->real_escape_string($_POST['contract_service_number']);
    $contract_problem = $conn_prit->real_escape_string($_POST['contract_problem']);
    $contract_request_date = $conn_prit->real_escape_string($_POST['contract_request_date']);
    $contract_issue_date = $conn_prit->real_escape_string($_POST['contract_issue_date']);
    $contract_price = $conn_prit->real_escape_string($_POST['contract_price']);
    $contract_obraschenie_id = $conn_prit->real_escape_string($_POST['contract_obraschenie_id']);

    // Исправленный SQL-запрос
    $sql = "UPDATE `договор услуг` SET 
                Фамилия='$contract_surname', 
                Имя='$contract_name', 
                Телефон='$contract_phone', 
                Номер_услуги='$contract_service_number', 
                `Описанная проблема`='$contract_problem', 
                `Дата_обращения`='$contract_request_date', 
                `Дата_выдачи`='$contract_issue_date', 
                `Цена_услуги`='$contract_price',
                Обращение_ID='$contract_obraschenie_id'
            WHERE ID='$contract_id'";

    if ($conn_prit->query($sql) === TRUE) {
        echo "<script>alert('Информация о договоре успешно обновлена.'); window.location.href='pro.php';</script>";
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
    <title>Редактирование информации о договоре услуг</title>
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
        input[type="text"], input[type="date"], input[type="tel"], input[type="number"] {
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
        <h2>Редактирование информации о договоре услуг</h2>

        <form method="GET" action="">
            <label for="contract_id">Введите ID договора услуг, чтобы осуществить поиск:</label>
            <input type="text" id="contract_id" name="id" value="<?= htmlspecialchars($contract_id) ?>" placeholder="ID договора" required>
            <button type="submit">Подтвердить</button>
        </form>

        <?php if ($contract): ?>
            <form method="POST">
                <table>
                    <tr>
                        <td><label for="contract_surname">Фамилия</label></td>
                        <td><input type="text" id="contract_surname" name="contract_surname" value="<?= htmlspecialchars($contract['Фамилия'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="contract_name">Имя</label></td>
                        <td><input type="text" id="contract_name" name="contract_name" value="<?= htmlspecialchars($contract['Имя'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="contract_phone">Телефон</label></td>
                        <td><input type="tel" id="contract_phone" name="contract_phone" value="<?= htmlspecialchars($contract['Телефон'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="contract_service_number">Номер услуги</label></td>
                        <td><input type="text" id="contract_service_number" name="contract_service_number" value="<?= htmlspecialchars($contract['Номер_услуги'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="contract_problem">Описание проблемы</label></td>
                        <td><input type="text" id="contract_problem" name="contract_problem" value="<?= htmlspecialchars($contract['Описанная проблема'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="contract_request_date">Дата обращения</label></td>
                        <td><input type="date" id="contract_request_date" name="contract_request_date" value="<?= htmlspecialchars($contract['Дата_обращения'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="contract_issue_date">Дата выдачи</label></td>
                        <td><input type="date" id="contract_issue_date" name="contract_issue_date" value="<?= htmlspecialchars($contract['Дата_выдачи'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="contract_price">Цена услуги</label></td>
                        <td><input type="number" id="contract_price" name="contract_price" value="<?= htmlspecialchars($contract['Цена_услуги'] ?? '') ?>" required></td>
                    </tr>
                    <tr>
                        <td><label for="contract_obraschenie_id">Обращение ID</label></td>
                        <td><input type="text" id="contract_obraschenie_id" name="contract_obraschenie_id" value="<?= htmlspecialchars($contract['Обращение_ID'] ?? '') ?>" required></td>
                    </tr>
                </table>
                <button type="submit" name="update_contract">Обновить информацию</button>
            </form>
        <?php else: ?>
            <p style="color: red;">Договор услуг не найден.</p>
        <?php endif; ?>

        <button class="back-btn" onclick="window.location.href='operator.php'">Вернуться к списку договоров</button>
    </div>
</body>
</html>
