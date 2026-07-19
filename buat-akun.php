<?php
include("koneksi.php");

if (isset($_POST['button'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($con, $_POST['jenis_kelamin']);
    $umur = mysqli_real_escape_string($con, $_POST['umur']);
    $profesi = mysqli_real_escape_string($con, $_POST['profesi']);
    $tanggal_lahir = mysqli_real_escape_string($con, $_POST['tanggal_lahir']); // New field
    $alamat = mysqli_real_escape_string($con, $_POST['alamat']); // New field
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query_cek = mysqli_query($con, "SELECT * FROM pasien WHERE email = '$email'");
    $cek = mysqli_num_rows($query_cek);

    if ($cek > 0) {
        echo "<script>alert('Email telah terdaftar'); window.location.href='buat-akun.php';</script>";
    } else {
        $insert = mysqli_query($con, "INSERT INTO pasien (email, nama, jenis_kelamin, umur, profesi, tanggal_lahir, alamat, password) 
        VALUES ('$email', '$nama', '$jenis_kelamin', '$umur', '$profesi', '$tanggal_lahir', '$alamat', '$password')");

        if ($insert) {
            echo "<script>
                alert('Pendaftaran berhasil! Silahkan login.');
                window.location.href='login.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Gagal membuat akun'); window.location.href='buat-akun.php';</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
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
        margin: 2rem auto;
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

    input[type="email"],
    input[type="text"],
    input[type="number"],
    input[type="password"],
    select {
        width: 100%;
        padding: 0.6rem;
        border: 2px solid #e1e1e1;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    input[type="email"]:focus,
    input[type="text"]:focus,
    input[type="number"]:focus,
    input[type="password"]:focus,
    select:focus {
        border-color: #1e90ff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.25);
        transform: translateY(-1px);
    }

    .radio-group {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        margin: 0.3rem 0;
    }

    .radio-option {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    input[type="radio"] {
        appearance: none;
        width: 16px;
        height: 16px;
        border: 2px solid #1e90ff;
        border-radius: 50%;
        margin: 0;
        margin-right: 0.5rem;
        cursor: pointer;
        position: relative;
        top: 0;
    }

    input[type="radio"]:checked {
        background: #1e90ff;
        box-shadow: inset 0 0 0 3px white;
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

    .box-login form>div {
        transition: transform 0.2s ease;
    }

    .box-login form>div:hover {
        transform: translateX(3px);
    }

    input:invalid {
        border-color: rgb(65, 122, 214);
    }

    input:valid {
        border-color: #00c851;
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

        input[type="email"],
        input[type="text"],
        input[type="number"],
        input[type="password"],
        select {
            font-size: 16px;

        }
    }

    button.loading {
        background: linear-gradient(45deg, #1e90ff, #4169E1);
        position: relative;
        color: transparent;
    }

    button.loading::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        border: 2px solid white;
        border-top-color: transparent;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    [data-tooltip] {
        position: relative;
    }

    [data-tooltip]:hover::before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 5px 10px;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        font-size: 12px;
        border-radius: 4px;
        white-space: nowrap;
        pointer-events: none;
        animation: fadeIn 0.2s ease;
    }

    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%231e90ff' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 12px) center;
        padding-right: 35px;
    }

    input[type="password"] {
        margin-bottom: 0;
    }

    .password-strength {
        height: 3px;
        background: #f1f1f1;
        margin-top: 5px;
        border-radius: 2px;
        overflow: hidden;
    }

    .password-strength::before {
        content: '';
        display: block;
        height: 100%;
        width: var(--strength, 0%);
        background: linear-gradient(to right, #ff4444, #ffbb33, #00C851);
        transition: width 0.3s ease;
    }

    input[type="date"] {
        width: 100%;
        padding: 0.6rem;
        border: 2px solid #e1e1e1;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    input[type="date"]:focus,
    textarea:focus {
        border-color: #1e90ff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 144, 255, 0.25);
        transform: translateY(-1px);
    }

    textarea {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
</style>

<body>
    <div id="banner">
        <div class="head-banner">
            <img src="sipagon.png" height="70">
            <strong>
                <font color="white">Daftar</font>
            </strong>
        </div>
        <div class="isi-banner">
            <div class="box-login">
                <form method="post">
                    <b>Nama :</b>
                    <input type="text" name="nama" required />
                    <b>Tanggal Lahir :</b>
                    <input type="date" name="tanggal_lahir" required />
                    <b>Umur (tahun) :</b>
                    <input type="number" name="umur" required min="1" max="120" />
                    <b>Jenis Kelamin :</b>
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type='radio' name='jenis_kelamin' value='Laki-Laki' required />
                            Laki-Laki
                        </label>
                        <label class="radio-option">
                            <input type='radio' name='jenis_kelamin' value='Perempuan' />
                            Perempuan
                        </label>
                    </div>
                    <b>Profesi :</b>
                    <select name="profesi" required>
                        <option value="">Pilih Profesi</option>
                        <option>Pekerja</option>
                        <option>Mahasiswa</option>
                        <option>Pelajar</option>
                        <option>Tidak Bekerja</option>
                    </select>
                    <b>Alamat :</b>
                    <textarea name="alamat" required rows="3" style="
                        width: 100%;
                        padding: 0.6rem;
                        border: 2px solid #e1e1e1;
                        border-radius: 8px;
                        font-size: 0.9rem;
                        transition: all 0.3s ease;
                        background: white;
                        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
                        resize: vertical;
                    "></textarea>
                    <b>Email :</b>
                    <input type="email" name="email" required />
                    <b>Password :</b>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password" required placeholder="Masukkan password" />
                        <i id="togglePassword" class="fa fa-eye"></i>
                    </div>
                    <button type="submit" name="button">DAFTAR</button>
                </form>
            </div>
        </div>
    </div>
    <script>
    const togglePassword = document.querySelector("#togglePassword");
    const passwordField = document.querySelector("#password");

    togglePassword.addEventListener("click", function () {
        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
        passwordField.setAttribute("type", type);

        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
</script>
</body>

</html>