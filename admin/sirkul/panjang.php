<?php
if (isset($_GET['kode'])) {
    // Sanitasi input untuk mencegah SQL Injection
    $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);

    // Menggunakan prepared statement untuk query SELECT
    $sql_cek = "SELECT * FROM tb_sirkulasi WHERE id_sk = ?";
    $stmt_cek = $koneksi->prepare($sql_cek);
    $stmt_cek->bind_param("s", $kode); // Parameter "s" untuk string
    $stmt_cek->execute();
    $result = $stmt_cek->get_result();
    $data_cek = $result->fetch_array(MYSQLI_BOTH);

    // Memastikan data ditemukan
    if ($data_cek) {
        // Menangkap tanggal dari database
        $tgl_p = $data_cek['tgl_pinjam'];

        // Membuat tanggal kembali
        $tgl_pp = date('Y-m-d', strtotime('+7 days', strtotime($tgl_p)));
        $tgl_kk = date('Y-m-d', strtotime('+14 days', strtotime($tgl_p)));

        // Menggunakan prepared statement untuk query UPDATE
        $sql_ubah = "UPDATE tb_sirkulasi SET tgl_pinjam = ?, tgl_kembali = ? WHERE id_sk = ?";
        $stmt_ubah = $koneksi->prepare($sql_ubah);
        $stmt_ubah->bind_param("sss", $tgl_pp, $tgl_kk, $kode);
        $query_ubah = $stmt_ubah->execute();

        // Menampilkan pesan berdasarkan hasil update
        if ($query_ubah) {
            echo "<script>
                Swal.fire({
                    title: 'Perpanjang Berhasil',
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
                    title: 'Perpanjang Gagal',
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
    } else {
        echo "<script>
            Swal.fire({
                title: 'Data Tidak Ditemukan',
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

    // Menutup statement
    $stmt_cek->close();
    if (isset($stmt_ubah)) $stmt_ubah->close();
}
?>
