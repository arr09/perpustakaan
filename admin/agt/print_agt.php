<?php
include "inc/koneksi.php";
?>

<?php
error_reporting(0);

// Download file dalam format Word atau Excel
if (!empty($_GET['download']) && $_GET['download'] == 'doc') {
    header("Content-Type: application/vnd.ms-word");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Disposition: attachment;filename=" . date('d-m-Y') . "_laporan_rekam_medis.doc");
} elseif (!empty($_GET['download']) && $_GET['download'] == 'xls') {
    header("Content-Type: application/vnd.ms-excel");
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    header("Content-Disposition: attachment;filename=" . date('d-m-Y') . "_laporan_rekam_medis.xls");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="assets_style/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets_style/assets/bower_components/font-awesome/css/font-awesome.min.css">
    <title><?= htmlspecialchars($title_web); ?></title>
    <style>
        body {
            background: rgba(0, 0, 0, 0.2);
        }
        page[size="A4"] {
            background: white;
            width: 21cm;
            height: 29.7cm;
            display: block;
            margin: 0 auto;
            margin-bottom: 0.5pc;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
            padding: 2.54cm;
        }
        @media print {
            body, page[size="A4"] {
                margin: 0;
                box-shadow: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <br/>
        <div class="center">
            Preview HTML to DOC [ size paper A4 ]
        </div>
        <div class="right">
            <button type="button" class="btn btn-success btn-md" onclick="printDiv('printableArea')">
                <i class="fa fa-print"></i> Print File
            </button>
        </div>
    </div>
    <br/>
    <div id="printableArea">
        <page size="A4">
            <div class="panel panel-default">
                <div class="panel-body bg-primary">
                    <h4 class="text-center">KARTU ANGGOTA PERPUSTAKAAN</h4>
                    <br/>
                    <div class="row">
                        <div class="col-sm-8">
                            <table class="table table-striped">
                                <?php
                                // Gunakan prepared statement untuk keamanan
                                if (!empty($_GET['kode'])) {
                                    $stmt = $koneksi->prepare("SELECT * FROM tb_anggota WHERE id_anggota = ?");
                                    $stmt->bind_param("s", $_GET['kode']);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    while ($data = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td>ID Anggota</td>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($data['id_anggota']); ?></td>
                                </tr>
                                <tr>
                                    <td>Nama</td>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($data['nama']); ?></td>
                                </tr>
                                <tr>
                                    <td>Jenis Kelamin</td>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($data['jekel']); ?></td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($data['kelas']); ?></td>
                                </tr>
                                <tr>
                                    <td>No HP</td>
                                    <td>:</td>
                                    <td><?= htmlspecialchars($data['no_hp']); ?></td>
                                </tr>
                                <?php
                                    }
                                    $stmt->close();
                                }
                                ?>
                            </table>
                        </div>
                        <div class="col-sm-4 text-center">
                            <center>
                                <div style="border: 1px solid rgb(100, 100, 100); height: 4cm; overflow: auto; padding: 10px; text-align: center; width: 3cm;">
                                    <div style="top: 50%; margin-top: 1.4cm;">
                                        <h4>3x4</h4>
                                    </div>
                                </div>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </page>
    </div>
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>
</body>
</html>
