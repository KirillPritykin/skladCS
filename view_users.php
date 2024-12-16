<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: on.php");
    exit();
}

// Connect to the database
$servername = "localhost";
$username = "Administrator"; // Ваше имя пользователя
$password = "224"; // Ваш пароль
$dbname = "Bus"; // Имя вашей базы данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users
$sql = "SELECT id, username, password FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Информация о созданных аккаунтах коллег</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 14pt;
            background-color: #f0f0f0;
            color: #333;
            padding: 20px;
        }
        h1 {
            font-size: 24pt; /* Уменьшенный размер шрифта заголовка */
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #4CAF50; /* Зеленый цвет для заголовка */
            color: white;
            font-weight: bold;
        }
        td {
            background-color: #f9f9f9; /* Светлый фон для ячеек */
        }
        tr:nth-child(even) td {
            background-color: #e9f5e9; /* Светло-зеленый для четных строк */
        }
        tr:hover {
            background-color: #d1e7dd; /* Цвет при наведении */
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Созданные аккаунты:</h1>
    <table>
        <tr>
            <th>ID</th>
            <th>Логин</th>
            <th>Пароль</th>
        </tr>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['password']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Нет данных</td>
            </tr>
        <?php endif; ?>
    </table>
    <br>
    <button onclick="window.location.href='dashboard.php'">Возврат на предыдущую страницу</button>
</body>
</html>