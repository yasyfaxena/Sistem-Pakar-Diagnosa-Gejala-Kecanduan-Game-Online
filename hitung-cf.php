<?php
session_start();
include("koneksi.php");

if (!isset($_SESSION['email'])) {
    header("location:login.php");
    exit;
}

$email = $_SESSION['email'];
$hipotesis = $_POST['hipotesis'];
$evidence_array = isset($_POST['evidence']) ? $_POST['evidence'] : [];
$jawaban = isset($_POST['jawaban']) ? $_POST['jawaban'] : [];

mysqli_query($con, "DELETE FROM certainty_factor WHERE email='$email'");

function getValueMD($keyakinan)
{
    $nilai_keyakinan = [
        'Sangat yakin' => 1.0,
        'Yakin' => 0.8,
        'Cukup Yakin' => 0.6,
        'Hampir yakin' => 0.4,
        'Kurang yakin' => 0.2,
        'Tidak yakin' => 0.0
    ];
    return $nilai_keyakinan[$keyakinan] ?? 0;
}

foreach ($evidence_array as $kode_evidence) {
    if (isset($jawaban[$kode_evidence])) {
        $md_value = getValueMD($jawaban[$kode_evidence]);

        $mb_query = mysqli_query($con, "SELECT mb FROM basis_pengetahuan WHERE kode_penyakit = '$hipotesis' AND kode_evidence = '$kode_evidence'");
        $mb_data = mysqli_fetch_assoc($mb_query);
        $mb_value = $mb_data['mb'];

        $query = "INSERT INTO certainty_factor (email, kode_penyakit, kode_evidence, mb, md) 
                  VALUES (?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($con, $query)) {
            mysqli_stmt_bind_param($stmt, 'sssdd', $email, $hipotesis, $kode_evidence, $mb_value, $md_value);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

$query = mysqli_query($con, "SELECT bp.mb, cf.md
                            FROM basis_pengetahuan bp
                            JOIN certainty_factor cf ON bp.kode_evidence = cf.kode_evidence
                            WHERE bp.kode_penyakit = '$hipotesis'
                            AND cf.email = '$email'
                            AND cf.kode_penyakit = '$hipotesis'
                            ORDER BY bp.kode_evidence");

$cf_old = 0;
$cf_kombinasi = 0;
$first = true;

while ($data = mysqli_fetch_assoc($query)) {
    $cf_he = $data['mb'] * $data['md'];

    if ($first) {
        $cf_old = $cf_he;
        $first = false;
    } else {
        $cf_old = $cf_old + ($cf_he * (1 - $cf_old));
    }
    $cf_kombinasi = $cf_old;
}

$cf_persen = round($cf_kombinasi * 100, 2);

mysqli_query($con, "DELETE FROM hasil_cf WHERE email='$email'");

mysqli_query($con, "INSERT INTO hasil_cf (email, kode_penyakit, hasil) 
                   VALUES ('$email', '$hipotesis', $cf_persen)");

header("Location: hasil-diagnosa.php");
exit;
