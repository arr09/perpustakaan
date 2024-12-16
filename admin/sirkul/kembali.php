<?php
if (isset($_GET['kode'])) {
    // Sanitasi input
    $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);

    // Menggunakan prepared statement untuk query UPDATE
    $sql_ubah = "UPDATE tb_sirkulasi SET status = ? WHERE id_sk = ?";
    $stmt_ubah = $koneksi->prepare($sql_ubah);
    $status = "KEM";
    $stmt_ubah->bind_param("ss", $status, $kode); // "ss" untuk dua parameter string
    $query_ubah = $stmt_ubah->execute();

    // Menampilkan pesan berdasarkan hasil
    if ($query_ubah) {
        echo "<script>
            Swal.fire({
                title: 'Kembalikan Buku Berhasil',
                text: '',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'index.php?page=data_sirkul';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Kembalikan Buku Gagal',
                text: '',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'index.php?page=data_sirkul';
                }
            });
        </script>";
    }

    // Tutup statement
    $stmt_ubah->close();
}
?>
