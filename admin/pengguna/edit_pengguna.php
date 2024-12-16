<?php
if (isset($_GET['kode'])) {
    $sql_cek = "SELECT * FROM tb_pengguna WHERE id_pengguna = ?";
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
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Ubah Pengguna</h3>
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
							<input type="hidden" class="form-control" name="id_pengguna" value="<?php echo htmlspecialchars($data_cek['id_pengguna']); ?>" readonly />
						</div>

						<div class="form-group">
							<label>Nama Pengguna</label>
							<input class="form-control" name="nama_pengguna" value="<?php echo htmlspecialchars($data_cek['nama_pengguna']); ?>" />
						</div>

						<div class="form-group">
							<label>Username</label>
							<input class="form-control" name="username" value="<?php echo htmlspecialchars($data_cek['username']); ?>" />
						</div>

						<div class="form-group">
							<label for="exampleInputPassword1">Password</label>
							<input type="password" class="form-control" name="password" id="pass" placeholder="Kosongkan jika tidak ingin mengubah" />
							<input id="mybutton" onclick="change()" type="checkbox" class="form-checkbox"> Lihat Password
						</div>

						<div class="form-group">
							<label>Level</label>
							<select name="level" id="level" class="form-control" required>
								<option value="">-- Pilih Level --</option>
								<option value="Administrator" <?php echo ($data_cek['level'] == "Administrator" ? "selected" : ""); ?>>Administrator</option>
								<option value="Petugas" <?php echo ($data_cek['level'] == "Petugas" ? "selected" : ""); ?>>Petugas</option>
							</select>
						</div>

					</div>

					<div class="box-footer">
						<input type="submit" name="Ubah" value="Ubah" class="btn btn-success">
						<a href="?page=MyApp/data_pengguna" title="Kembali" class="btn btn-warning">Batal</a>
					</div>
				</form>
			</div>
		</div>
</section>

<?php
if (isset($_POST['Ubah'])) {
    // Proses update data dengan prepared statement
    $sql_ubah = "UPDATE tb_pengguna SET nama_pengguna = ?, username = ?, level = ?";

    // Jika password diisi, tambahkan ke query
    if (!empty($_POST['password'])) {
        $hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql_ubah .= ", password = ?";
    }
    $sql_ubah .= " WHERE id_pengguna = ?";

    $stmt = $koneksi->prepare($sql_ubah);

    // Bind parameter sesuai kondisi
    if (!empty($_POST['password'])) {
        $stmt->bind_param("sssss", $_POST['nama_pengguna'], $_POST['username'], $_POST['level'], $hashed_password, $_POST['id_pengguna']);
    } else {
        $stmt->bind_param("ssss", $_POST['nama_pengguna'], $_POST['username'], $_POST['level'], $_POST['id_pengguna']);
    }

    // Eksekusi dan cek hasil
    if ($stmt->execute()) {
        echo "<script>
        Swal.fire({title: 'Ubah Data Berhasil',text: '',icon: 'success',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_pengguna';
            }
        })</script>";
    } else {
        echo "<script>
        Swal.fire({title: 'Ubah Data Gagal',text: '',icon: 'error',confirmButtonText: 'OK'
        }).then((result) => {
            if (result.value) {
                window.location = 'index.php?page=MyApp/data_pengguna';
            }
        })</script>";
    }
    $stmt->close();
}
?>

<script type="text/javascript">
    function change() {
        var x = document.getElementById('pass').type;
        if (x == 'password') {
            document.getElementById('pass').type = 'text';
        } else {
            document.getElementById('pass').type = 'password';
        }
    }
</script>
