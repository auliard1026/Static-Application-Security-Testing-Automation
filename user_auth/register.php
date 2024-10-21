<?php
// Koneksi ke database
$servername = "localhost";
$username_db = "root"; 
$password_db = "";     
$dbname = "user_auth"; 

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     // Ambil input dari form registrasi
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (strlen($username) > 15) {
        echo "Username terlalu panjang, maksimal 15 karakter.";
        exit();
    }

    if (strlen($password) < 8) {
        echo "Password minimal harus 8 karakter.";
        exit();
    }

    // Hash password dengan salt
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);


    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "Registrasi berhasil!";
    } else {
        echo "Registrasi gagal: " . $stmt->error;
    }

    $stmt->close();
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
