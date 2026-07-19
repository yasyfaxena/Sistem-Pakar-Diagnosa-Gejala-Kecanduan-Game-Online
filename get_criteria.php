<?php
session_start();

error_reporting(0);
ini_set('display_errors', 0);

ob_start();

try {
    include("koneksi.php");

    if (!isset($_GET['kode_tingkat'])) {
        throw new Exception("Kode tingkat kecanduan tidak ditemukan");
    }

    $kode_tingkat = mysqli_real_escape_string($con, $_GET['kode_tingkat']);

    $query = "SELECT e.kode_evidence, e.nama_evidence 
             FROM evidence e 
             INNER JOIN basis_pengetahuan bp ON e.kode_evidence = bp.kode_evidence 
             WHERE bp.kode_penyakit = ?
             ORDER BY e.kode_evidence";

    $stmt = mysqli_prepare($con, $query);
    if (!$stmt) {
        throw new Exception("Query preparation failed");
    }

    mysqli_stmt_bind_param($stmt, "s", $kode_tingkat);

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Query execution failed");
    }

    $result = mysqli_stmt_get_result($stmt);

    $evidence = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $evidence[] = [
            'kode_evidence' => $row['kode_evidence'],
            'nama_evidence' => $row['nama_evidence']
        ];
    }

    ob_clean();

    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    echo json_encode($evidence);
} 

catch (Exception $e) {
    ob_clean();
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    echo json_encode(['error' => $e->getMessage()]);
}

exit;
