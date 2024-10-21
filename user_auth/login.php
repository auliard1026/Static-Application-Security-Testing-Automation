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
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password_from_db);
        $stmt->fetch();

         // Verifikasi password
        if (password_verify($password, $hashed_password_from_db)) {
            echo "Login berhasil!";
        } else {
            echo "Autentikasi gagal: Password salah!";
        }
    } else {
        echo "Autentikasi gagal: Username tidak ditemukan!";
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Form HTML untuk login -->
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Form Login</h2>
    <form method="POST" action="login.php">
        <label>Username:</label>
        <input type="text" name="username" maxlength="15" required><br>
        <label>Password:</label>
        <input type="password" name="password" minlength="8" required><br>
        <input type="submit" value="Login">
    </form>
    <br>
    <a href="register.php">Belum punya akun? Registrasi di sini</a>
</body>
</html>
