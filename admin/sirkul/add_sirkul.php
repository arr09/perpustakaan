<?php
//kode 9 digit
$carikode = $koneksi->query("SELECT id_sk FROM tb_sirkulasi ORDER BY id_sk DESC");
$datakode = $carikode->fetch_assoc();
$kode = $datakode['id_sk'] ?? '';
$urut = substr($kode, 1, 3);
$tambah = (int)$urut + 1;

if (strlen($tambah) == 1) {
    $format = "S" . "00" . $tambah;
} elseif (strlen($tambah) == 2) {
    $format = "S" . "0" . $tambah;
} else {
    $format = "S" . $tambah;
}
?>

<section class="content-header">
    <h1>
        Sirkulasi
        <small>Buku</small>
    </h1>
    <ol class="breadcrumb">
        <li>
            <a href="index.php">
                <i class="fa fa-home"></i>
                <b>Si Perpustakaan</b>
            </a>
        </li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- general form elements -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Peminjaman</h3>
                </div>
                <!-- form start -->
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Id Sirkulasi</label>
                            <input type="text" name="id_sk" id="id_sk" class="form-control"
                                value="<?php echo htmlspecialchars($format); ?>" readonly />
                        </div>

                        <div class="form-group">
                            <label>Nama Peminjam</label>
                            <select name="id_anggota" id="id_anggota" class="form-control select2" style="width: 100%;">
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                $stmt = $koneksi->prepare("SELECT id_anggota, nama FROM tb_anggota");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                <option value="<?php echo htmlspecialchars($row['id_anggota']); ?>">
                                    <?php echo htmlspecialchars($row['id_anggota']); ?> - <?php echo htmlspecialchars($row['nama']); ?>
                                </option>
                                <?php
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Buku</label>
                            <select name="id_buku" id="id_buku" class="form-control select2" style="width: 100%;">
                                <option selected="selected">-- Pilih --</option>
                                <?php
                                $stmt = $koneksi->prepare("SELECT id_buku, judul_buku FROM tb_buku");
                                $stmt->execute();
                                $result = $stmt->get_result();
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                <option value="<?php echo htmlspecialchars($row['id_buku']); ?>">
                                    <?php echo htmlspecialchars($row['id_buku']); ?> - <?php echo htmlspecialchars($row['judul_buku']); ?>
                                </option>
                                <?php
                                }
                                $stmt->close();
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tgl Pinjam</label>
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" />
                        </div>
                    </div>

                    <div class="box-footer">
                        <input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
                        <a href="?page=data_sirkul" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
</section>

<?php
if (isset($_POST['Simpan'])) {
    $id_sk = $_POST['id_sk'];
    $id_buku = $_POST['id_buku'];
    $id_anggota = $_POST['id_anggota'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $tgl_kembali = date('Y-m-d', strtotime('+7 days', strtotime($tgl_pinjam)));
    $tgl_dikembalikan = date('Y-m-d');

    // Prepared statement untuk mencegah SQL Injection
    $stmt = $koneksi->prepare("INSERT INTO tb_sirkulasi (id_sk, id_buku, id_anggota, tgl_pinjam, status, tgl_kembali, tgl_dikembalikan) VALUES (?, ?, ?, ?, 'PIN', ?, ?)");
    $stmt->bind_param("ssssss", $id_sk, $id_buku, $id_anggota, $tgl_pinjam, $tgl_kembali, $tgl_dikembalikan);

    $stmt2 = $koneksi->prepare("INSERT INTO log_pinjam (id_buku, id_anggota, tgl_pinjam) VALUES (?, ?, ?)");
    $stmt2->bind_param("sss", $id_buku, $id_anggota, $tgl_pinjam);

    if ($stmt->execute() && $stmt2->execute()) {
        echo "<script>
        Swal.fire({
            title: 'Tambah Data Berhasil',
            text: '',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=data_sirkul';
            }
        })
        </script>";
    } else {
        echo "<script>
        Swal.fire({
            title: 'Tambah Data Gagal',
            text: '',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=add_sirkul';
            }
        })
        </script>";
    }

    $stmt->close();
    $stmt2->close();
    $koneksi->close();
}
?>
