<?php

// Параметры подключения к базе данных
$servername = "localhost"; // Укажите свой хост
$username = "root"; // Укажите свое имя пользователя
$password = ""; // Укажите свой пароль
$dbname = "ремонт_компьютерной_техники_притыкин"; // Укажите имя вашей базы данных

// Создаем подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получаем данные из POST-запроса
$lastName = $_POST['lastName'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$phone = $_POST['phone'] ?? '';
$description = $_POST['description'] ?? '';

// Подготовка и выполнение SQL-запроса
$stmt = $conn->prepare("INSERT INTO обращениецс (Фамилия, Имя, Телефон, `Описание проблемы`) VALUES (?, ?, ?, ?)");
if ($stmt === false) {
    die("Ошибка подготовки: " . $conn->error);
}

// Привязка параметров
$stmt->bind_param("ssss", $lastName, $firstName, $phone, $description);

// Выполнение запроса и проверка на ошибки
if (!$stmt->execute()) {
    echo "Ошибка выполнения: " . $stmt->error;
} else {
    echo "Запись успешно добавлена";
}

// Закрытие соединения
$stmt->close();
$conn->close();
?>