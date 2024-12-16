<?php
if (isset($_GET['kode'])) {
    // Sanitasi input
    $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);

    // Menggunakan prepared statement untuk query DELETE
    $sql_hapus = "DELETE FROM tb_buku WHERE id_buku = ?";
    $stmt_hapus = $koneksi->prepare($sql_hapus);
    $stmt_hapus->bind_param("s", $kode); // "s" untuk parameter string
    $query_hapus = $stmt_hapus->execute();

    // Menampilkan pesan berdasarkan hasil
    if ($query_hapus) {
        echo "<script>
            Swal.fire({
                title: 'Hapus Data Berhasil',
                text: '',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'index.php?page=MyApp/data_buku';
                }
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                title: 'Hapus Data Gagal',
                text: '',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'index.php?page=MyApp/data_buku';
                }
            });
        </script>";
    }

    // Tutup statement
    $stmt_hapus->close();
}
?>
