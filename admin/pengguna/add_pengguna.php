<section class="content-header">
	<h1>
		Pengguna Sistem
	</h1>
	<ol class="breadcrumb">
		<li>
			<a href="index.php">
				<i class="fa fa-home"></i>
				<b>Si Tabsis</b>
			</a>
		</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header with-border">
					<h3 class="box-title">Tambah Pengguna</h3>
					<div class="box-tools pull-right">
						<button type="button" class="btn btn-box-tool" data-widget="collapse">
							<i class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-box-tool" data-widget="remove">
							<i class="fa fa-remove"></i>
						</button>
					</div>
				</div>
				<form action="" method="post" enctype="multipart/form-data">
					<div class="box-body">
						<div class="form-group">
							<label for="nama_pengguna">Nama Pengguna</label>
							<input type="text" name="nama_pengguna" id="nama_pengguna" class="form-control" placeholder="Nama pengguna" required>
						</div>

						<div class="form-group">
							<label for="username">Username</label>
							<input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
						</div>

						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
						</div>

						<div class="form-group">
							<label for="level">Level</label>
							<select name="level" id="level" class="form-control" required>
								<option value="">-- Pilih Level --</option>
								<option value="Administrator">Administrator</option>
								<option value="Petugas">Petugas</option>
							</select>
						</div>
					</div>

					<div class="box-footer">
						<input type="submit" name="Simpan" value="Simpan" class="btn btn-info">
						<a href="?page=MyApp/data_pengguna" title="Kembali" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
		</div>
</section>

<?php
if (isset($_POST['Simpan'])) {
    // Validasi input
    $nama_pengguna = trim($_POST['nama_pengguna']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $level = $_POST['level'];

    // Periksa apakah semua data telah diisi
    if (empty($nama_pengguna) || empty($username) || empty($password) || empty($level)) {
        echo "<script>
        Swal.fire({title: 'Form Tidak Lengkap',text: 'Harap isi semua kolom.',icon: 'warning',confirmButtonText: 'OK'});
        </script>";
    } else {
        // Hash password menggunakan BCRYPT
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Gunakan prepared statement untuk menyimpan data
        $stmt = $koneksi->prepare("INSERT INTO tb_pengguna (nama_pengguna, username, password, level) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nama_pengguna, $username, $hashed_password, $level);

        // Eksekusi query
        if ($stmt->execute()) {
            echo "<script>
            Swal.fire({title: 'Tambah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=MyApp/data_pengguna';
                }
            })</script>";
        } else {
            echo "<script>
            Swal.fire({title: 'Tambah Data Gagal',text: 'Terjadi kesalahan saat menyimpan data.',icon: 'error',confirmButtonText: 'OK'
            }).then((result) => {
                if (result.value) {
                    window.location = 'index.php?page=MyApp/add_pengguna';
                }
            })</script>";
        }

        // Tutup prepared statement
        $stmt->close();
    }
}
?>
