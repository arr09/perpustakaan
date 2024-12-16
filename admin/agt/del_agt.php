<?php
if (isset($_GET['kode'])) {
    // Query menggunakan prepared statement
    $sql_hapus = "DELETE FROM tb_anggota WHERE id_anggota = ?";
    $stmt = $koneksi->prepare($sql_hapus);

    // Bind parameter dengan tipe data string (s)
    $stmt->bind_param("s", $_GET['kode']);

    // Eksekusi query
    if ($stmt->execute()) {
        echo "<script>
        Swal.fire({title: 'Hapus Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_agt';
            }
        })</script>";
    } else {
        echo "<script>
        Swal.fire({title: 'Hapus Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_agt';
            }
        })</script>";
    }

    // Tutup statement
    $stmt->close();
}
?>