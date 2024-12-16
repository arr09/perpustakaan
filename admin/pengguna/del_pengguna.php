<?php
if (isset($_GET['kode'])) {
    // Filter input untuk menghindari karakter berbahaya
    $kode = filter_input(INPUT_GET, 'kode', FILTER_SANITIZE_STRING);

    if ($kode) {
        // Menggunakan prepared statement untuk mencegah SQL Injection
        $stmt = $koneksi->prepare("DELETE FROM tb_pengguna WHERE id_pengguna = ?");
        $stmt->bind_param("s", $kode); // "s" menunjukkan tipe data string

        if ($stmt->execute()) {
            // Jika berhasil menghapus data
            echo "<script>
            Swal.fire({
                title: 'Hapus Data Berhasil',
                text: '',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=MyApp/data_pengguna';
                }
            })
            </script>";
        } else {
            // Jika gagal menghapus data
            echo "<script>
            Swal.fire({
                title: 'Hapus Data Gagal',
                text: '',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=MyApp/data_pengguna';
                }
            })
            </script>";
        }

        $stmt->close(); // Tutup prepared statement
    } else {
        // Jika input tidak valid
        echo "<script>
        Swal.fire({
            title: 'Input Tidak Valid',
            text: '',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_pengguna';
            }
        })
        </script>";
    }
}
?>
