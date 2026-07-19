<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['adminlogin'])) {
    header("location:login.php");
    exit;
}

if (isset($_POST['add'])) {
    $kode = $_POST['kode_evidence'];
    $nama = $_POST['nama_evidence'];

    $insert = mysqli_query($con, "INSERT INTO evidence (kode_evidence, nama_evidence) 
                                 VALUES ('$kode', '$nama')");

    if ($insert) {
        echo "<script>alert('Data berhasil ditambahkan!');</script>";
        echo "<script>window.location.href='gejala.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}

if (isset($_POST['update'])) {
    $kode = $_POST['edit_kode_evidence'];
    $nama = $_POST['edit_nama_evidence'];

    $update = mysqli_query($con, "UPDATE evidence SET 
                                 nama_evidence = '$nama'
                                 WHERE kode_evidence = '$kode'");

    if ($update) {
        echo "<script>alert('Data berhasil diupdate!');</script>";
        echo "<script>window.location.href='gejala.php';</script>";
    } else {
        echo "<script>alert('Gagal mengupdate data!');</script>";
    }
}

$query = mysqli_query($con, "SELECT kode_evidence, nama_evidence 
                           FROM evidence 
                           ORDER BY kode_evidence");

$result = mysqli_query($con, "SELECT kode_evidence FROM evidence ORDER BY kode_evidence DESC LIMIT 1");
$row = mysqli_fetch_array($result);
$last_code = $row ? substr($row['kode_evidence'], 1) : 0;
$next_code = 'G' . sprintf('%03d', $last_code + 1);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Gejala - SIPAGON</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
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
            margin: 10% auto;
            padding: 2rem;
            border-radius: 15px;
            width: 90%;
            max-width: 500px;
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

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #0066ff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 255, 0.1);
        }

        .modal-title {
            margin-bottom: 1.5rem;
            color: #0052cc;
            font-size: 1.5rem;
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
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 82, 204, 0.2);
        }

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

        .btn-add {
            background: white;
            color: #0052cc;
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
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
            text-decoration: none;
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

        .nama-gejala {
            max-width: 500px;
        }

        @media (max-width: 768px) {
            .card-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .btn-add {
                width: 100%;
                justify-content: center;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-edit,
            .btn-delete {
                width: 100%;
                justify-content: center;
            }

            .nama-gejala {
                max-width: 200px;
            }
        }
    </style>
</head>

<body>
    <?php include "header_admin.php"; ?>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fa fa-list"></i> Data Gejala</h2>
                <button onclick="openAddModal()" class="btn-add">
                    <i class="fa fa-plus"></i> Tambah Data
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 70px;">No</th>
                            <th style="width: 120px;">Kode Gejala</th>
                            <th>Nama Gejala</th>
                            <th style="width: 200px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($data = mysqli_fetch_array($query)) {
                            echo "<tr>";
                            echo "<td>" . $no++ . "</td>";
                            echo "<td>" . htmlspecialchars($data['kode_evidence']) . "</td>";
                            echo "<td class='nama-gejala'>" . htmlspecialchars($data['nama_evidence']) . "</td>";
                            echo "<td class='action-buttons'>
                                   <button onclick='openEditModal(\"" . $data['kode_evidence'] . "\", \"" .
                                htmlspecialchars($data['nama_evidence'], ENT_QUOTES) . "\")' class='btn-edit'>
                                       <i class='fa fa-edit'></i> Ubah
                                   </button>
                                   <a href='hapus_gejala.php?id=" . $data['kode_evidence'] . "' 
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

    <!-- Add Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddModal()">&times;</span>
            <h3 class="modal-title">Tambah Data Gejala</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Kode Gejala:</label>
                    <input type="text" name="kode_evidence" value="<?php echo $next_code; ?>" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Gejala:</label>
                    <input type="text" name="nama_evidence" required>
                </div>
                <button type="submit" name="add" class="btn-submit">Tambah Data</button>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3 class="modal-title">Edit Data Gejala</h3>
            <form method="POST">
                <div class="form-group">
                    <label>Kode Gejala:</label>
                    <input type="text" id="edit_kode_evidence" name="edit_kode_evidence" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Gejala:</label>
                    <input type="text" id="edit_nama_evidence" name="edit_nama_evidence" required>
                </div>
                <button type="submit" name="update" class="btn-submit">Update Data</button>
            </form>
        </div>
    </div>

    <script>
        // Modal functions
        const addModal = document.getElementById("addModal");
        const editModal = document.getElementById("editModal");

        function openAddModal() {
            addModal.style.display = "block";
        }

        function closeAddModal() {
            addModal.style.display = "none";
        }

        function openEditModal(kode, nama) {
            document.getElementById("edit_kode_evidence").value = kode;
            document.getElementById("edit_nama_evidence").value = nama;
            editModal.style.display = "block";
        }

        function closeEditModal() {
            editModal.style.display = "none";
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            if (event.target == addModal) {
                closeAddModal();
            }
            if (event.target == editModal) {
                closeEditModal();
            }
        }
    </script>
</body>

</html>