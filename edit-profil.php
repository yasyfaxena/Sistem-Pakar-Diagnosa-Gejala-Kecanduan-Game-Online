<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit;
}

$email = $_SESSION['email'];
$query = mysqli_query($con, "SELECT * FROM pasien WHERE email='$email'");
$data = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($con, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($con, $_POST['tanggal_lahir']);
    $alamat = mysqli_real_escape_string($con, $_POST['alamat']);

    $update = mysqli_query($con, "UPDATE pasien SET 
                                 nama = '$nama',
                                 jenis_kelamin = '$jenis_kelamin',
                                 tanggal_lahir = '$tanggal_lahir',
                                 alamat = '$alamat'
                                 WHERE email = '$email'");

    if ($update) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='profil.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil!'); window.location.href='edit-profil.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Edit Profil - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/tampilan.css">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="container">
        <div class="profile-card">
            <div class="card-header">
                <h2><i class="fa fa-edit"></i> Edit Profil</h2>
            </div>

            <div class="profile-content">
                <form method="post" class="edit-form">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" value="<?php echo htmlspecialchars($data['email']); ?>" readonly class="form-control disabled">
                    </div>

                    <div class="form-group">
                        <label>Nama:</label>
                        <input type="text" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin:</label>
                        <select name="jenis_kelamin" required class="form-control">
                            <option value="Laki-Laki" <?php echo ($data['jenis_kelamin'] == 'Laki-Laki') ? 'selected' : ''; ?>>Laki-Laki</option>
                            <option value="Perempuan" <?php echo ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir:</label>
                        <input type="date" name="tanggal_lahir" value="<?php echo htmlspecialchars($data['tanggal_lahir']); ?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Alamat:</label>
                        <textarea name="alamat" class="form-control" rows="3"><?php echo htmlspecialchars($data['alamat'] ?? ''); ?></textarea>
                    </div>

                    <div class="form-buttons">
                        <a href="profil.php" class="btn-secondary">
                            <i class="fa fa-times"></i> Batal
                        </a>
                        <button type="submit" name="update" class="btn-primary">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        body {
            background-image: url("bg.png");
        }

        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(45deg, #0052cc, #0066ff);
            color: white;
            padding: 1.5rem;
        }

        .profile-content {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #666;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e1e1e1;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #1e90ff;
            outline: none;
        }

        .form-control.disabled {
            background: #f8f9fa;
            cursor: not-allowed;
        }

        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn-primary,
        .btn-secondary {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(45deg, #1e90ff, #4169E1);
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 2px solid #e1e1e1;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            transform: translateY(-2px);
        }
    </style>
</body>

</html>