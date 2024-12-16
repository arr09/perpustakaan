<?php
if (isset($_GET['kode'])) {
    $sql_cek = "SELECT * FROM tb_anggota WHERE id_anggota = ?";
    $stmt = $koneksi->prepare($sql_cek);
    $stmt->bind_param("s", $_GET['kode']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_cek = $result->fetch_assoc();
    $stmt->close();
}
?>

<section class="content-header">
	<h1>
		Master Data
		<small>Data Anggota</small>
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
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Ubah Anggota</h3>
				</div>
				<form action="" method="post" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label>Id Anggota</label>
							<input type="text" class="form-control" name="id_anggota" value="<?php echo htmlspecialchars($data_cek['id_anggota']); ?>" readonly />
						</div>

						<div class="form-group">
							<label>Nama</label>
							<input type="text" class="form-control" name="nama" value="<?php echo htmlspecialchars($data_cek['nama']); ?>" />
						</div>

						<div class="form-group">
							<label>Jenis Kelamin</label>
							<select name="jekel" id="jekel" class="form-control" required>
								<option value="">-- Pilih --</option>
								<option value="Laki-laki" <?php echo ($data_cek['jekel'] == "Laki-laki" ? "selected" : ""); ?>>Laki-laki</option>
								<option value="Perempuan" <?php echo ($data_cek['jekel'] == "Perempuan" ? "selected" : ""); ?>>Perempuan</option>
							</select>
						</div>

						<div class="form-group">
							<label>Kelas</label>
							<input type="text" class="form-control" name="kelas" value="<?php echo htmlspecialchars($data_cek['kelas']); ?>" />
						</div>

						<div class="form-group">
							<label>No HP</label>
							<input type="number" class="form-control" name="no_hp" value="<?php echo htmlspecialchars($data_cek['no_hp']); ?>" />
						</div>
					</div>

					<div class="box-footer">
						<input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
						<a href="?page=MyApp/data_agt" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
		</div>
</section>

<?php
if (isset($_POST['Ubah'])) {
    // Proses ubah data dengan prepared statement
    $sql_ubah = "UPDATE tb_anggota SET nama = ?, jekel = ?, kelas = ?, no_hp = ? WHERE id_anggota = ?";
    $stmt = $koneksi->prepare($sql_ubah);
    $stmt->bind_param("sssss", $_POST['nama'], $_POST['jekel'], $_POST['kelas'], $_POST['no_hp'], $_POST['id_anggota']);
    $result = $stmt->execute();

    if ($result) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_agt';
            }
        })</script>";
    } else {
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_agt';
            }
        })</script>";
    }
    $stmt->close();
}
?>
