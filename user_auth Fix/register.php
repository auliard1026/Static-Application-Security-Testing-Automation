<?php
$servername = "localhost";
$username_db = "root"; 
$password_db = "";     
$dbname = "user_auth"; 

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strlen($username) > 15) {
        echo "Username terlalu panjang, maksimal 15 karakter.";
        exit();
    }

    if (strlen($password) < 8) {
        echo "Password minimal harus 8 karakter.";
        exit();
    }

    $stmt_check = $conn->prepare("SELECT id FROM users WHERE username = ?");
    if ($stmt_check) {
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo "Username sudah terdaftar. Silakan pilih username lain.";
            $stmt_check->close();
            exit();
        }
        $stmt_check->close();
    } else {
        echo "Kesalahan saat memeriksa username: " . $conn->error;
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashed_password);
        if ($stmt->execute()) {
            echo "Registrasi berhasil!";
        } else {
            echo "Registrasi gagal: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Kesalahan saat mempersiapkan pernyataan: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Registrasi</title>
</head>
<body>
    <h2>Form Registrasi</h2>
    <form method="POST" action="register.php">
        <label>Username:</label>
        <input type="text" name="username" maxlength="15" required><br>
        <label>Password:</label>
        <input type="password" name="password" minlength="8" required><br>
        <input type="submit" value="Register">
    </form>
    <br>
    <a href="login.php">Sudah punya akun? Login di sini</a>
</body>
</html>
