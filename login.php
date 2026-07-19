<?php
include("koneksi.php");

if (isset($_POST['button'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];

    $query_admin = mysqli_query($con, "SELECT * FROM Admin WHERE username = '$email'");
    $data_admin = mysqli_fetch_array($query_admin);

    if (mysqli_num_rows($query_admin) > 0) {
        if ($password == $data_admin['password']) { 
            session_start();
            $_SESSION['username'] = $data_admin['username'];
            $_SESSION['nama'] = $data_admin['nama'];
            $_SESSION['adminlogin'] = "login";
            header("Location: dashboard_admin.php");
            exit();
        } else {
            echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
        }
    } else {
        $query_pasien = mysqli_query($con, "SELECT * FROM pasien WHERE email = '$email'");
        $data_pasien = mysqli_fetch_array($query_pasien);

        if (mysqli_num_rows($query_pasien) > 0) {
            $verify = password_verify($password, $data_pasien['password']);

            if ($verify) {
                session_start();
                $_SESSION['email'] = $data_pasien['email'];
                $_SESSION['nama'] = $data_pasien['nama'];
                $_SESSION['userlogin'] = "login";
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<script>alert('Password salah!'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('Email/Username tidak terdaftar!'); window.location.href='login.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<style>
    body {
        background-image: url("bg.png");
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #banner {
        max-width: 1000px;
        margin: 5rem auto;
        padding: 0 1rem;
        transform: translateY(-10px);
        opacity: 0;
        animation: slideUp 0.6s ease forwards;
    }

    @keyframes slideUp {
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .head-banner {
        background: linear-gradient(45deg, #0052cc, #0066ff);
        padding: 1rem;
        border-radius: 15px 15px 0 0;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .head-banner strong {
        font-size: 1.5rem;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .head-banner img {
        filter: drop-shadow(2px 2px 4px rgba(0, 0, 0, 0.2));
        transition: transform 0.3s ease;
    }

    .head-banner img:hover {
        transform: scale(1.05);
    }

    .isi-banner {
        background: rgba(255, 255, 255, 0.95);
        padding: 1.5rem;
        border-radius: 0 0 15px 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        backdrop-filter: blur(10px);
    }

    .box-login {
        max-width: 500px;
        margin: 0 auto;
        animation: fadeIn 0.5s ease-out;
    }

    .box-login form {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .box-login b {
        color: #0052cc;
        font-size: 0.9rem;
        margin-left: 0.2rem;
        display: block;
        margin-top: 0.5rem;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 0.6rem;
        border: 2px solid #e1e1e1;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        border-color: #1e90ff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.25);
        transform: translateY(-1px);
    }

    button {
        background: linear-gradient(45deg, #1e90ff, #4169E1);
        color: white;
        padding: 0.8rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(30, 144, 255, 0.3);
    }

    button:hover {
        background: linear-gradient(45deg, #1873cc, #3557b7);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(30, 144, 255, 0.4);
    }

    button:active {
        transform: translateY(0);
        box-shadow: 0 2px 10px rgba(30, 144, 255, 0.3);
    }

    .register-link {
        text-align: center;
        margin-top: 1rem;
    }

    .register-link a {
        color: #1e90ff;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .register-link a:hover {
        color: #0052cc;
        text-decoration: underline;
    }

    .password-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-wrapper input {
        flex: 1;
    }

    .password-wrapper i {
        position: absolute;
        right: 10px;
        cursor: pointer;
        color: #888;
    }

    .password-wrapper i:hover {
        color: #0052cc;
    }

    @media (max-width: 768px) {
        .head-banner {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }

        .box-login {
            padding: 0.5rem;
        }
    }
</style>

<body>
    <div id="banner">
        <div class="head-banner">
            <img src="sipagon.png" height="70">
            <strong>
                <font color="white">Login</font>
            </strong>
        </div>
        <div class="isi-banner">
            <div class="box-login">
                <form method="post">
                    <b>Email/Username :</b>
                    <input type="text" name="email" required placeholder="Email untuk pasien / Username untuk admin" />
                    <b>Password :</b>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" required placeholder="Masukkan password" />
                        <i id="togglePassword" class="fa fa-eye"></i>
                    </div>
                    <button type="submit" name="button">LOGIN</button>
                </form>
                <div class="register-link">
                    Belum punya akun? <a href="buat-akun.php">Daftar disini</a>
                </div>
            </div>
        </div>
    </div>
    <script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordField = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
        passwordField.setAttribute("type", type);

        // Ganti ikon mata
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
</script>
</body>

</html>