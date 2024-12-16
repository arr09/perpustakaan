<?php
if (isset($_GET['kode'])) {
    // Sanitasi input untuk mencegah SQL Injection
    $kode = mysqli_real_escape_string($koneksi, $_GET['kode']);

    // Menggunakan prepared statement untuk query SELECT
    $sql_cek = "SELECT * FROM tb_buku WHERE id_buku = ?";
    $stmt_cek = $koneksi->prepare($sql_cek);
    $stmt_cek->bind_param("s", $kode); // "s" untuk parameter string
    $stmt_cek->execute();
    $result = $stmt_cek->get_result();
    $data_cek = $result->fetch_array(MYSQLI_BOTH);

    // Tutup statement
    $stmt_cek->close();
}
?>

<section class="content-header">
	<h1>
		Master Data
		<small>Data Buku</small>
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
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Ubah Buku</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<form action="" method="post" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label>Id Buku</label>
							<input type="text" class="form-control" name="id_buku" value="<?php echo htmlspecialchars($data_cek['id_buku']); ?>" readonly />
						</div>
						<div class="form-group">
							<label>Judul Buku</label>
							<input type="text" class="form-control" name="judul_buku" value="<?php echo htmlspecialchars($data_cek['judul_buku']); ?>" />
						</div>
						<div class="form-group">
							<label>Pengarang</label>
							<input type="text" class="form-control" name="pengarang" value="<?php echo htmlspecialchars($data_cek['pengarang']); ?>" />
						</div>
						<div class="form-group">
							<label>Penerbit</label>
							<input type="text" class="form-control" name="penerbit" value="<?php echo htmlspecialchars($data_cek['penerbit']); ?>" />
						</div>
						<div class="form-group">
							<label>Th Terbit</label>
							<input type="text" class="form-control" name="th_terbit" value="<?php echo htmlspecialchars($data_cek['th_terbit']); ?>" />
						</div>
					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
						<a href="?page=MyApp/data_buku" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
			<!-- /.box -->
</section>

<?php
if (isset($_POST['Ubah'])) {
    // Validasi data dari form
    $id_buku = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
    $judul_buku = mysqli_real_escape_string($koneksi, $_POST['judul_buku']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $th_terbit = mysqli_real_escape_string($koneksi, $_POST['th_terbit']);

    // Menggunakan prepared statement untuk query UPDATE
    $sql_ubah = "UPDATE tb_buku SET judul_buku = ?, pengarang = ?, penerbit = ?, th_terbit = ? WHERE id_buku = ?";
    $stmt_ubah = $koneksi->prepare($sql_ubah);
    $stmt_ubah->bind_param("sssss", $judul_buku, $pengarang, $penerbit, $th_terbit, $id_buku);
    $query_ubah = $stmt_ubah->execute();

    // Menampilkan pesan berdasarkan hasil
    if ($query_ubah) {
        echo "<script>
            Swal.fire({
                title: 'Ubah Data Berhasil',
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
                title: 'Ubah Data Gagal',
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
    $stmt_ubah->close();
}
?>
