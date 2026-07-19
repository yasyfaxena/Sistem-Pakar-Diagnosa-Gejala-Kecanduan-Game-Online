<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['adminlogin'])) {
    header("location:login.php");
    exit;
}

if (isset($_POST['update'])) {
    $kode_tingkat = $_POST['edit_kode_tingkat'];
    $nama_tingkat = $_POST['edit_nama_tingkat'];
    $solusi = $_POST['edit_solusi'];

    $update_query = mysqli_query($con, "UPDATE tingkat_kecanduan SET 
        nama_tingkat = '$nama_tingkat' 
        WHERE kode_tingkat = '$kode_tingkat'");

    $update_solusi = mysqli_query($con, "UPDATE solusi SET 
        solusi = '$solusi' 
        WHERE kode_solusi = (SELECT kode_solusi FROM tingkat_kecanduan WHERE kode_tingkat = '$kode_tingkat')");

    if ($update_query && $update_solusi) {
        echo "<script>alert('Data berhasil diupdate!');</script>";
        echo "<script>window.location.href='hipotesis.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data!');</script>";
    }
}

$query = mysqli_query($con, "SELECT tk.kode_tingkat, tk.nama_tingkat, s.solusi 
                           FROM tingkat_kecanduan tk
                           JOIN solusi s ON tk.kode_solusi = s.kode_solusi
                           ORDER BY tk.kode_tingkat");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Hipotesis - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-image: url("bg.png");
            padding-top: 80px;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(45deg, #0052cc, #0066ff);
            color: white;
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table-container {
            padding: 1.5rem;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th,
        td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #0052cc;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit,
        .btn-delete {
            padding: 0.4rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            transition: all 0.3s ease;
        }

        .btn-edit {
            background: #4CAF50;
            color: white;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-edit:hover,
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .solusi-text {
            max-width: 400px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 600px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            font-weight: bold;
            color: #aaa;
            cursor: pointer;
            transition: color 0.3s;
        }

        .close:hover {
            color: #333;
        }

        .modal-content h2 {
            margin-bottom: 1.5rem;
            color: #0052cc;
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            border-color: #0066ff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn-submit {
            background: linear-gradient(45deg, #0052cc, #0066ff);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 82, 204, 0.2);
        }

        @media (max-width: 768px) {
            .modal-content {
                margin: 10% auto;
                width: 95%;
                padding: 1.5rem;
            }

            .form-group input,
            .form-group textarea {
                padding: 0.6rem;
            }

            .btn-submit {
                padding: 0.7rem 1.2rem;
            }
        }
    </style>
</head>

<body>
    <?php include "header_admin.php"; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fa fa-sitemap"></i> Data Hipotesis</h2>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Penanganan/Solusi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($data = mysqli_fetch_array($query)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($data['kode_tingkat']) . "</td>";
                            echo "<td>" . htmlspecialchars($data['nama_tingkat']) . "</td>";
                            echo "<td class='solusi-text' title='" . htmlspecialchars($data['solusi']) . "'>" .
                                htmlspecialchars($data['solusi']) . "</td>";
                            echo "<td class='action-buttons'>
                                   <button onclick='openEditModal(\"" . $data['kode_tingkat'] . "\", \"" .
                                htmlspecialchars($data['nama_tingkat'], ENT_QUOTES) . "\", \"" .
                                htmlspecialchars($data['solusi'], ENT_QUOTES) . "\")' class='btn-edit'>
                                       <i class='fa fa-edit'></i> Ubah
                                   </button>
                                   <a href='hapus_hipotesis.php?id=" . $data['kode_tingkat'] . "' 
                                      onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")' 
                                      class='btn-delete'>
                                       <i class='fa fa-trash'></i> Hapus
                                   </a>
                                 </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Hipotesis</h2>
            <form id="editForm" method="POST">
                <input type="hidden" id="edit_kode_tingkat" name="edit_kode_tingkat">
                <div class="form-group">
                    <label for="edit_nama_tingkat">Nama Hipotesis:</label>
                    <input type="text" id="edit_nama_tingkat" name="edit_nama_tingkat" required>
                </div>
                <div class="form-group">
                    <label for="edit_solusi">Solusi:</label>
                    <textarea id="edit_solusi" name="edit_solusi" required></textarea>
                </div>
                <button type="submit" name="update" class="btn-submit">Update</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById("editModal");
        const span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function openEditModal(kode_tingkat, nama_tingkat, solusi) {
            document.getElementById("edit_kode_tingkat").value = kode_tingkat;
            document.getElementById("edit_nama_tingkat").value = nama_tingkat;
            document.getElementById("edit_solusi").value = solusi;
            modal.style.display = "block";
        }
    </script>
</body>

</html>