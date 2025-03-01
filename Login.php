<?php
session_start();
include('config.php'); // Koneksi ke database

// Jika user sudah login, langsung arahkan ke home.php
// if (isset($_SESSION['user_id'])) {
//     header("Location: Home.php");
//     exit();
// }

// Proses Registrasi
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Tidak di-hash

    // Cek apakah email sudah terdaftar
    $check_email = "SELECT email FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_email);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $message = "Email sudah terdaftar! Gunakan email lain.";
    } else {
        // Jika email belum ada, lanjutkan registrasi tanpa hashing password
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {
            $message = "Pendaftaran berhasil! <a href='#login'>Login sekarang</a>";
        } else {
            $message = "Terjadi kesalahan: " . $stmt->error;
        }
    }

    $stmt->close();
}

// Proses Login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password']; // Tidak di-hash

    // Query untuk mencari user berdasarkan email
    $sql = "SELECT id, email, password, name FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika user ditemukan
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Memeriksa apakah password cocok (langsung dibandingkan tanpa hashing)
        if ($password == $user['password']) {
            // Set session untuk user yang berhasil login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['name']; 

            // Redirect ke halaman home setelah login sukses
            header("Location: Home.php");
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Login - LokaVata</title>
</head>
<body>
    <!-- <header>
        <div class="logo"> -->
            <!-- <img src="Images/LokaVata.png" alt="LokaVata Logo"> -->
        <!-- </div>
        <nav>
            <a href="Home.php">Home</a>
            <a href="Maps.html">Maps</a>
            <a href="Login.php" class="active">Login</a>
            <a href="Faq.html">FAQ</a>
            <a href="About.html">About Us</a>
            <a href="contactus.html">Contact Us</a> -->
            <!-- <a href="?logout=true" style="color: red; font-weight: bold;">Log Out</a> Link Logout -->
        <!-- </nav>
    </header> -->

    <main>
        <div class="container" id="container">
            <!-- Form Sign Up -->
            <div class="form-container sign-up">
                <form action="login.php" method="POST">
                    <h1>Create Account</h1>
                    <div class="social-icons">
                        <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                    <span>Crate Your Account</span>
                    <input type="text" name="name" placeholder="Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="register">Sign Up</button>
                </form>
                <?php if (!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>
            </div>

            <!-- Form Sign In -->
            <div class="form-container sign-in">
                <form action="login.php" method="POST">
                    <h1>Sign In</h1>
                    <div class="social-icons">
                        <a href="#" class="icon"><i class="fa-brands fa-google-plus-g"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-github"></i></a>
                        <a href="#" class="icon"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                    <span>or use your email password</span>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <a href="#">Forget Your Password?</a>
                    <button type="submit" name="login">Sign In</button>
                </form>
                <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
            </div>

            <!-- Toggle Panels -->
            <div class="toggle-container">
                <div class="toggle">
                    <div class="toggle-panel toggle-left">
                        <h1>Welcome Back!</h1>
                        <p>Enter your personal details to use all of site features</p>
                        <button class="hidden" id="login">Sign In</button>
                    </div>
                    <div class="toggle-panel toggle-right">
                        <h1>Hello, Friend!</h1>
                        <p>Register with your personal details to use all of site features</p>
                        <button class="hidden" id="register">Sign Up</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="Login.js"></script>
    </main>
</body>
</html>
