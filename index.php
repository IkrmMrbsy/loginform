<?php
//Untuk memanggil koneksi database di file koneksi.php//
require_once "koneksi.php";

//untuk mendapatkan objek koneksi PDO ke database//
$connection = getConnection();
//untuk menyimpan pesan yang akan ditampilkan kepada pengguna setelah proses login atau registrasi//
$loginMessage = $registerMessage = "";

//memastikan bahwa permintaan yang sedang dihandle adalah metode POST, yang umumnya digunakan untuk mengirimkan data formulir//
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //memeriksa apakah tombol "login" telah dikirimkan dalam formulir//
    if (isset($_POST["login"])) {
        //Mengambil data username dan password dari formulir//
        $username = $_POST["username"];
        $password = $_POST["password"];

        //Menyiapkan dan menjalankan query SQL untuk mendapatkan data pengguna dengan username yang cocok//
        $sql = "SELECT * FROM Users WHERE username = :username";
        $statement = $connection->prepare($sql);
        $statement->bindParam(":username", $username);
        $statement->execute();

        //Mengambil satu baris hasil query//
        $row = $statement->fetch();

        //Memeriksa apakah hasil query mengandung data dan apakah kata sandi cocok//
        if ($row && password_verify($password, $row["password_hash"])) {
            $loginMessage = "Sukses Login: " . $row["username"];
        } else {
            $loginMessage = "Gagal Login";
        }
    } elseif (isset($_POST["register"])) {
        //Mengambil data username dan password baru dari formulir registrasi//
        $newUsername = $_POST["newUsername"];
        $newPassword = $_POST["newPassword"];

        //Meng-hash password baru menggunakan password_hash dan menyiapkan dan menjalankan query SQL untuk memasukkan data pengguna baru ke dalam tabel//
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = "INSERT INTO Users (username, password_hash) VALUES (:newUsername, :hashedPassword)";
        $statement = $connection->prepare($sql);
        $statement->bindParam(":newUsername", $newUsername);
        $statement->bindParam(":hashedPassword", $hashedPassword);

        //Memeriksa apakah query eksekusi berhasil dan menetapkan pesan registrasi yang sesuai//
        if ($statement->execute()) {
            $registerMessage = "Sukses Registrasi";
        } else {
            $registerMessage = "Gagal Registrasi";
        }
    }
}
//Menutup objek koneksi PDO setelah selesai menggunakan database//
$connection = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 50px;
            width: 300px;
            text-align: center;
            margin-right: 90px
        }

        h2 {
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .login-message, .register-message {
            margin-top: 15px;
            color: <?php echo ($loginMessage === "Sukses Login" || $registerMessage === "Sukses Registrasi") ? 'red' : 'green'; ?>;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Login Form</h2>
    <form class="login-form" method="post" action="">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" name="login">Login</button>
        </div>
    </form>
    <p class="login-message" id="loginMessage"><?php echo $loginMessage; ?></p>
</div>

<div class="container">
    <h2>Register Form</h2>
    <form class="register-form" method="post" action="">
        <div class="form-group">
            <label for="newUsername">New Username:</label>
            <input type="text" id="newUsername" name="newUsername" required>
        </div>
        <div class="form-group">
            <label for="newPassword">New Password:</label>
            <input type="password" id="newPassword" name="newPassword" required>
        </div>
        <div class="form-group">
            <button type="submit" name="register">Register</button>
        </div>
    </form>
    <p class="register-message" id="registerMessage"><?php echo $registerMessage; ?></p>
</div>

<script>
    setTimeout(function () {
        document.getElementById('loginMessage').style.display = 'none';
        document.getElementById('registerMessage').style.display = 'none';
    }, 10000); 

    setTimeout(function () {
        var successMessage = document.getElementById('successMessage');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000); 
</script>

</body>
</html>
