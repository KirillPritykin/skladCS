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

$passengers = $conn_prit->query("SELECT * FROM ОбращениеЦС");

$conn_prit->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Просмотр обращений</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ffcc00;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: rgba(255, 204, 0, 0.5);
        }
        tr:nth-child(even) {
            background-color: rgba(255, 204, 0, 0.2);
        }
        .back-btn {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 5px;
            display: block;
            margin: 20px auto;
            text-align: center;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Списки обращений в сервис ООО "Цифровые системы"</h2>
    <table>
        <thead>
            <tr>
            <th>ID</th>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Телефон</th>
                <th>Описание проблемы</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $passengers->fetch_assoc()): ?>
                <tr>
                <td><?php echo $row['ID']; ?></td>
                    <td><?php echo $row['Фамилия']; ?></td>
                    <td><?php echo $row['Имя']; ?></td>
                    <td><?php echo $row['Телефон']; ?></td>
                    <td><?php echo $row['Описание проблемы']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <button class="back-btn" onclick="window.location.href='operator.php'">Вернуться</button>
</body>
</html>