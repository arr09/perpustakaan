<?php
include '../inc/koneksi.php';
include '../inc/rupiah.php';

$output = '';

if (isset($_POST["nis"])) {
    // Menggunakan prepared statement
    $sql = "SELECT SUM(setor) - SUM(tarik) AS Total FROM tb_tabungan WHERE nis = ?";
    $stmt = $koneksi->prepare($sql);

    // Bind parameter untuk mencegah SQL Injection
    $stmt->bind_param("s", $_POST["nis"]); // "s" untuk string
    $stmt->execute();
    $result = $stmt->get_result();

    // Ambil hasil query
    if ($row = $result->fetch_assoc()) {
        $output = $row["Total"];
    }

    // Tutup statement
    $stmt->close();

    // Format output ke rupiah
    echo rupiah($output);
}
?>
