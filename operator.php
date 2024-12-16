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
$dbname_prit = "ремонт_компьютерной_техники_притыкин";

$conn_prit = new mysqli($servername_prit, $username_prit, $password_prit, $dbname_prit);

// Проверка соединения
if ($conn_prit->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn_prit->connect_error);
}

// Обработка формы "Заполнение договора услуг"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_passenger'])) {
        // Получение данных из формы
        $passenger_name = $conn_prit->real_escape_string($_POST['passenger_name']);
        $passenger_surname = $conn_prit->real_escape_string($_POST['passenger_surname']);
        $passenger_phone = $conn_prit->real_escape_string($_POST['passenger_phone']);
        $problem_description = $conn_prit->real_escape_string($_POST['problem_description']);
        $service_price = $conn_prit->real_escape_string($_POST['service_price']);
        $request_date = $conn_prit->real_escape_string($_POST['request_date']);
        $issue_date = isset($_POST['issue_date']) ? $conn_prit->real_escape_string($_POST['issue_date']) : NULL; // Проверка существования
        $service_number = $conn_prit->real_escape_string($_POST['service_number']); // Номер услуги
        $obraschenie_id = $conn_prit->real_escape_string($_POST['obraschenie_id']); // ID обращения

        // Исправленный SQL запрос на вставку данных в таблицу "договор услуг"
        $sql = "INSERT INTO `договор услуг` (Фамилия, Имя, `Телефон`, `Номер_услуги`, `Описанная проблема`, 
                 `Дата_обращения`, `Дата_выдачи`, `Обращение_ID`, `Цена_услуги`) 
                VALUES ('$passenger_surname', '$passenger_name', '$passenger_phone', 
                '$service_number', '$problem_description', '$request_date', '$issue_date', 
                '$obraschenie_id', '$service_price')";
        
        // Проверка выполнения запроса
        if ($conn_prit->query($sql) === TRUE) {
            echo "<script>alert('Договор услуг успешно оформлен.');</script>";
        } else {
            echo "<script>alert('Ошибка: " . $conn_prit->error . "');</script>";
        }
    } elseif (isset($_POST['add_client'])) {
        // Обработка формы "Внести данные о клиенте"
        $client_name = $conn_prit->real_escape_string($_POST['client_name']);
        $client_surname = $conn_prit->real_escape_string($_POST['client_surname']);
        $client_phone = $conn_prit->real_escape_string($_POST['client_phone']);
        $client_request_date = $conn_prit->real_escape_string($_POST['client_request_date']);
        $client_description = $conn_prit->real_escape_string($_POST['client_description']);
        $client_service_price = $conn_prit->real_escape_string($_POST['client_service_price']);
        $client_diagnosis = $conn_prit->real_escape_string($_POST['client_diagnosis']);

      // SQL запрос для вставки данных в таблицу клиентов
$sql = "INSERT INTO `клиенты` (Фамилия, Имя, Телефон, Дата_обращения, `Описание проблемы`, `Цена вопроса`, Причина) 
VALUES ('$client_surname', '$client_name', '$client_phone', 
'$client_request_date', '$client_description', '$client_service_price', '$client_diagnosis')";

// Проверка выполнения запроса
if ($conn_prit->query($sql) === TRUE) {
echo "<script>alert('Данные о клиенте успешно добавлены.');</script>";
} else {
echo "<script>alert('Ошибка: " . $conn_prit->error . "');</script>";
}

    } elseif (isset($_POST['delete_passenger'])) {
        // Обработка удаления
        $passenger_id = $conn_prit->real_escape_string($_POST['passenger_id']);
        $sql = "DELETE FROM `договор услуг` WHERE ID='$passenger_id'";
        $conn_prit->query($sql);
    }
}

// Получение данных для отображения
$routes = $conn_prit->query("SELECT * FROM Клиенты");
$passengers = $conn_prit->query("SELECT * FROM `договор услуг`");

$conn_prit->close();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кассовый оператор</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            background-image: url('опер.jpg');
            background-size: cover;
            color: #fff;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        h2 {
            text-align: center;
            color: #000;
            font-size: 16pt;
        }

        .container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .form-section {
            background: rgba(0, 0, 0, 0.7);
            padding: 15px;
            border-radius: 10px;
            width: 320px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .form-section h3 {
            margin: 0;
        }

        input, select {
            margin: 10px 0;
            padding: 10px;
            font-size: 1rem;
            width: 90%;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        button {
            padding: 5px;
            background-color: #ffcc00;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 5px;
            width: 100%;
        }

        button:hover {
            background-color: #ffd700;
        }

        .btn-view, .btn-edit {
            background-color: green;
            color: white;
            border: none;
            padding: 5px 5px;
            cursor: pointer;
            font-size: 0.9rem;
            border-radius: 5px;
            margin-top: 10px;
            width: calc(50% - 5px);
        }

        .btn-view:hover, .btn-edit:hover {
            background-color: darkgreen;
        }

        .logout-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: red;
            color: white;
            border: none;
            padding: 5px 5px;
            cursor: pointer;
            font-size: 1rem;
            border-radius: 4px;
        }

        .logout-btn:hover {
            background-color: darkred;
        }
    </style>
    <script>
        function confirmLogout() {
            return confirm('Вы уверены, что хотите выйти из учетной записи?');
        }
    </script>
</head>
<body>
    <h2>Добро пожаловать на страницу ООО "Цифровых систем" персонала! Приступайте к работе, коллеги.</h2>

    <button class="logout-btn" onclick="return confirmLogout() ? window.location.href='on.php' : false;">Log Out</button>

    <div class="container">
        <div class="form-section">
            <h3>Заполнение договора</h3>
            <form method="POST">
                <input type="text" name="passenger_surname" placeholder="Фамилия" required>
                <input type="text" name="passenger_name" placeholder="Имя" required>
                <input type="text" name="passenger_phone" placeholder="Телефон" required>
                <input type="text" name="service_number" placeholder="Номер услуги" required>
                <input type="text" name="problem_description" placeholder="Описанная проблема" required>
                <input type="text" name="obraschenie_id" placeholder="Обращение ID" required> <!-- ID обращения -->
                <input type="date" name="request_date" placeholder="Дата обращения" required>
                <input type="date" name="issue_date" placeholder="Дата выдачи" required> <!-- Дата выдачи -->
                <input type="text" name="service_price" placeholder="Цена услуги" required>
                <button type="submit" name="add_passenger">Нажмите для подтверждения</button>
            </form>
            <button class="btn-view" onclick="window.location.href='view_passengers.php'">Просмотр обращений</button>
            <button class="btn-edit" onclick="window.location.href='edit_passenger.php'">Изменить информацию</button>
        </div>

        <div class="form-section">
            <h3>Внести данные о клиенте</h3>
            <form method="POST">
                <input type="text" name="client_surname" placeholder="Фамилия" required>
                <input type="text" name="client_name" placeholder="Имя" required>
                <input type="text" name="client_phone" placeholder="Телефон для связи" required>
                <input type="date" name="client_request_date" placeholder="Дата обращения" required>
                <input type="text" name="client_description" placeholder="Описание проблемы" required>
                <input type="text" name="client_service_price" placeholder="Стоимость за услугу" required>
                <input type="text" name="client_diagnosis" placeholder="Диагностика и выявлено" required>
                <button type="submit" name="add_client">Нажмите для подтверждения</button>
            </form>
            <button class="btn-view" onclick="window.location.href='clients.php'">Записи от коллег о клиентах</button>
            <button class="btn-edit" onclick="window.location.href='edit client.php'">Внести в таблицу клиенты изменения</button>
            <button class="btn-edit" onclick="window.location.href='dogovor.php'">Изменить договор</button>
            <button class="btn-edit" onclick="window.location.href='pro.php'">Просмотр договора</button>
        </div>
    </div>
</body>
</html>
