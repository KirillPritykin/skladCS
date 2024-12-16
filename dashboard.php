<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "bus"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Save last visit time
$_SESSION['last_visit'] = date("Y-m-d H:i:s");

// Initialize messages
$message = null;

// Handle creating a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_user'])) {
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];
    
    // Check if the username already exists
    $check_sql = "SELECT * FROM users WHERE username = '$new_username'";
    $result = $conn->query($check_sql);
    
    if ($result->num_rows > 0) {
        $message = "Error: User already exists with this username.";
    } else {
        // If user does not exist, proceed to insert the new user
        $sql = "INSERT INTO users (username, password, role) VALUES ('$new_username', '$new_password', 'user')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "User successfully added"; // Message for success
        } else {
            $message = "Error: " . $conn->error;
        }
    }
}

// Handle updating a user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $new_username = $_POST['username'];
    $new_password = $_POST['password'];

    // Update username and password
    $sql = "UPDATE users SET username='$new_username', password='$new_password' WHERE id=$user_id";

    if ($conn->query($sql) === TRUE) {
        $message = "User successfully updated"; // Message for success
    } else {
        $message = "Error updating user: " . $conn->error;
    }
}

// Handle deleting a user
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['user_id'];
    $sql = "DELETE FROM users WHERE id=$user_id";

    if ($conn->query($sql) === TRUE) {
        $message = "User successfully deleted"; // Message for success
    } else {
        $message = "Error deleting user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ООО Цифровые системы</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            background-color: #f0f0f0;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            position: relative; /* Для снежинок */
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 800px;
            width: 100%;
            position: relative; 
        }
        .button-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        button {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            font-size: 15px;
            transition: background 0.3s;
        }
        button:hover {
            background-color: darkred;
        }
        .green-button {
            background-color: green;
        }
        .green-button:hover {
            background-color: darkgreen;
        }
        .form-container {
            margin-top: 20px;
        }
        .form-container form {
            display: inline-block;
            margin: 10px 0;
        }
        input[type="text"], input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            margin-right: 5px;
        }
        .notification {
            background-color: lightgreen;
            padding: 10px;
            border-radius: 5px;
            margin: 10px auto;
            font-size: 14px; 
            width: 250px; 
            display: <?php echo isset($message) ? 'block' : 'none'; ?>; 
            color: #000;
            text-align: center; 
            transition: opacity 0.5s ease; 
        }
        img {
            max-width: 100%;
            border-radius: 8px;
            margin-top: 20px;
        }
        .close-button {
            position: absolute;
            top: 10px;
            right: 20px; 
            background: none;
            border: none;
            cursor: pointer;
        }
        .close-button img {
            width: 24px; 
            height: 24px; 
        }

        /* Стили снежинок */
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Функцонал сисадмина</h1>

        <button class="close-button" onclick="confirmClose()">
            <img src="Закрыть.png" alt="Close" /> <!-- Путь к вашему PNG изображению -->
        </button>

        <div class="notification" id="notification"><?php echo $message; ?></div>
        
        <div class="button-container">
            <button onclick="document.getElementById('createUserForm').style.display='block'">Новый аккаунт</button>
            <button onclick="document.getElementById('updateUserForm').style.display='block'">Обновить об аккаунте</button>
            <button onclick="document.getElementById('deleteUserForm').style.display='block'">Удалить аккаунт</button>
            <button class="green-button" onclick="window.location.href='view_users.php'">Просмотр данных из таблицы аккаунты</button>
            <button class="logout-button" onclick="logout()">Log out</button> 
        </div>
        
        <div id="createUserForm" class="form-container" style="display:none;">
            <h3>Добавить аккаунт</h3>
            <form method="POST">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="create_user">Create</button>
            </form>
        </div>
        
        <div id="updateUserForm" class="form-container" style="display:none;">
            <h3>Обновить данные аккаунта</h3>
            <form method="POST">
                <input type="text" name="user_id" placeholder="User ID" required>
                <input type="text" name="username" placeholder="New Username" required>
                <input type="password" name="password" placeholder="New Password" required>
                <button type="submit" name="update_user">Update</button>
            </form>
        </div>

        <div id="deleteUserForm" class="form-container" style="display:none;">
            <h3>Удаление аккаунта</h3>
            <form method="GET">
                <input type="text" name="user_id" placeholder="User ID" required>
                <button type="submit" name="delete_user">Delete</button>
            </form>
        </div>

        <div>
            <?php
            date_default_timezone_set('Asia/Krasnoyarsk');
            echo "Kemerovo: " . date("H:i:s");
            echo "<br>Last visit: " . $_SESSION['last_visit'];
            ?>
        </div>
        
        <img src="banner.jpg" alt="Your photo">
    </div>

    <script>
        function confirmClose() {
            if (confirm("Вы уверены, что хотите закрыть сайт?")) {
                window.close(); 
            }
        }
        
        function logout() {
            if (confirm("Вы уверены, что хотите выйти на сайте B.S.-Belovo?")) {
                window.location.href = 'on.php'; // Перенаправление на страницу входа
            }
        }

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

        setInterval(createSnowflake, 300); // Генерация снежинок

        window.onload = function() {
            var notification = document.getElementById("notification");
            if (notification.innerHTML.trim() !== "") {
                setTimeout(function() {
                    notification.style.opacity = "0";
                    setTimeout(function() {
                        notification.style.display = "none"; // Полностью скрыть после исчезновения
                    }, 500);
                }, 5000); // Скрыть уведомление через 5 секунд
            }
        };
    </script>
</body>
</html>
