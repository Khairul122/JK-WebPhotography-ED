<?php
session_start();
error_reporting(0);
include 'includes/config.php';
include 'includes/format_rupiah.php';
include 'includes/library.php';
if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
} else {
    ?>

    <!doctype html>
    <html lang="en" class="no-js">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="theme-color" content="#3e454c">

        <title><?php echo $pagedesc; ?></title>
        <link rel="shortcut icon" href="img/fav.png">

        <!-- Font awesome -->
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <!-- Sandstone Bootstrap CSS -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <!-- Bootstrap Datatables -->
        <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
        <!-- Bootstrap social button library -->
        <link rel="stylesheet" href="css/bootstrap-social.css">
        <!-- Bootstrap select -->
        <link rel="stylesheet" href="css/bootstrap-select.css">
        <!-- Bootstrap file input -->
        <link rel="stylesheet" href="css/fileinput.min.css">
        <!-- Awesome Bootstrap checkbox -->
        <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
        <!-- Admin Stye -->
        <link rel="stylesheet" href="css/style.css">
        <style>
            .errorWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #dd3d36;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }

            .succWrap {
                padding: 10px;
                margin: 0 0 20px 0;
                background: #fff;
                border-left: 4px solid #5cb85c;
                -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
                box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
            }
        </style>
        <script type="text/javascript">
            function valid() {
                if (document.laporan.akhir.value < document.laporan.awal.value) {
                    alert("Tanggal akhir harus lebih besar dari tanggal awal!");
                    return false;
                }
                return true;
            }
        </script>

    </head>

    <body>
        <?php include 'includes/header.php';?>

        <div class="ts-main-content">
            <?php include 'includes/leftbar.php';?>
            <div class="content-wrapper">
                <div class="container-fluid">
                    <h2 class="page-title">Laporan</h2>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>Laporan Harian</h4>
                            <form method="get" name="laporan" onSubmit="return valid();">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label>Tanggal Awal</label>
                                        <input type="date" class="form-control" name="awal" placeholder="From Date(dd/mm/yyyy)" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>Tanggal Akhir</label>
                                        <input type="date" class="form-control" name="akhir" placeholder="To Date(dd/mm/yyyy)" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>&nbsp;</label><br />
                                        <input type="submit" name="submit" value="Lihat Laporan" class="btn btn-primary">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php
if (isset($_GET['submit'])) {
        $no = 0;
        $mulai = $_GET['awal'];
        $selesai = $_GET['akhir'];
        $stt = "Sudah Dibayar";
        $stt1 = "Selesai";
        $sqlsewa = "SELECT * FROM transaksi WHERE (stt_trx='$stt' OR stt_trx='$stt1') AND tgl_bayar BETWEEN '$mulai' AND '$selesai'";

        $querysewa = mysqli_query($koneksidb, $sqlsewa);
        ?>
                        <!-- Zero Configuration Table -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Laporan Booking Tanggal <?php echo IndonesiaTgl($mulai); ?> sampai <?php echo IndonesiaTgl($selesai); ?></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Booking</th>
                                                <th>Tanggal Transaksi</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
while ($result = mysqli_fetch_array($querysewa)) {
            $paket = $result['id_paket'];
            $sqlpaket = "SELECT * FROM paket WHERE id_paket='$paket'";
            $querypaket = mysqli_query($koneksidb, $sqlpaket);
            $res = mysqli_fetch_array($querypaket);

            $no++;
            ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td>
                                                        <a href="#myModal" data-toggle="modal" data-load-code="<?php echo $result['id_trx']; ?>" data-remote-target="#myModal .modal-body"><?php echo $result['id_trx']; ?></a>
                                                    </td>
                                                    <td><?php echo IndonesiaTgl(htmlentities($result['tgl_trx'])); ?></td>
                                                    <td><?php echo IndonesiaTgl(htmlentities($result['tgl_bayar'])); ?></td>
                                                    <td><?php echo format_rupiah($res['harga']); ?></td>
                                                </tr>
                                            <?php }
        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="laporan_cetak.php?awal=<?php echo $mulai; ?>&akhir=<?php echo $selesai; ?>" target="_blank" class="btn btn-primary">Cetak</a>
                        </div>
                    <?php }?>

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>Laporan Bulanan</h4>
                            <form method="get" name="laporan" onSubmit="return valid();">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label>Pilih Bulan dan Tahun</label>
                                        <input type="month" class="form-control" name="bulan_tahun" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>&nbsp;</label><br />
                                        <input type="submit" name="submit" value="Lihat Laporan" class="btn btn-primary">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php
if (isset($_GET['submit'])) {
        $no = 0;
        $bulan_tahun = $_GET['bulan_tahun'];
        // Ubah format bulan dan tahun ke format yang sesuai dengan basis data (YYYY-MM)
        $bulan_tahun_formatted = date('Y-m', strtotime($bulan_tahun));
        $stt = "Sudah Dibayar";
        $stt1 = "Selesai";
        // Ubah kueri SQL untuk mengambil data berdasarkan bulan dan tahun yang dipilih
        $sqlsewa = "SELECT * FROM transaksi WHERE (stt_trx='$stt' OR stt_trx='$stt1') AND DATE_FORMAT(tgl_bayar, '%Y-%m') = '$bulan_tahun_formatted'";

        $querysewa = mysqli_query($koneksidb, $sqlsewa);
        ?>
                        <!-- Zero Configuration Table -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Laporan Booking Bulan <?php echo date('F Y', strtotime($bulan_tahun)); ?></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Booking</th>
                                                <th>Tanggal Transaksi</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
while ($result = mysqli_fetch_array($querysewa)) {
            $paket = $result['id_paket'];
            $sqlpaket = "SELECT * FROM paket WHERE id_paket='$paket'";
            $querypaket = mysqli_query($koneksidb, $sqlpaket);
            $res = mysqli_fetch_array($querypaket);

            $no++;
            ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td>
                                                        <a href="#myModal" data-toggle="modal" data-load-code="<?php echo $result['id_trx']; ?>" data-remote-target="#myModal .modal-body"><?php echo $result['id_trx']; ?></a>
                                                    </td>
                                                    <td><?php echo IndonesiaTgl(htmlentities($result['tgl_trx'])); ?></td>
                                                    <td><?php echo IndonesiaTgl(htmlentities($result['tgl_bayar'])); ?></td>
                                                    <td><?php echo format_rupiah($res['harga']); ?></td>
                                                </tr>
                                            <?php }
        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="laporan_bulanan.php?bulan_tahun=<?php echo $bulan_tahun; ?>" target="_blank" class="btn btn-primary">Cetak</a>
                        </div>
                    <?php }?>


                    <div class="panel panel-default">
                        <div class="panel-body">
                            <h4>Laporan Tahunan</h4>
                            <form method="get" name="laporan" onSubmit="return valid();">
                                <div class="form-group">
                                    <div class="col-sm-4">
                                        <label>Pilih Tahun</label>
                                        <select class="form-control" name="tahun" required>
                                            <option value="">Pilih Tahun</option>
                                            <?php
// Mendapatkan tahun saat ini
    $tahun_sekarang = date("Y");
    // Loop untuk membuat opsi tahun dari tahun sekarang ke belakang 10 tahun
    for ($i = $tahun_sekarang; $i >= ($tahun_sekarang - 10); $i--) {
        echo "<option value='$i'>$i</option>";
    }
    ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <label>&nbsp;</label><br />
                                        <input type="submit" name="submit" value="Lihat Laporan" class="btn btn-primary">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php
if (isset($_GET['submit'])) {
        $no = 0;
        $tahun = $_GET['tahun'];
        $stt = "Sudah Dibayar";
        $stt1 = "Selesai";
        // Ubah kueri SQL untuk mengambil data berdasarkan tahun yang dipilih
        $sqlsewa = "SELECT * FROM transaksi WHERE (stt_trx='$stt' OR stt_trx='$stt1') AND YEAR(tgl_bayar) = '$tahun'";

        $querysewa = mysqli_query($koneksidb, $sqlsewa);
        ?>
                        <!-- Zero Configuration Table -->
                        <div class="panel panel-default">
                            <div class="panel-heading">Laporan Booking Tahun <?php echo $tahun; ?></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Booking</th>
                                                <th>Tanggal Transaksi</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
while ($result = mysqli_fetch_array($querysewa)) {
            $paket = $result['id_paket'];
            $sqlpaket = "SELECT * FROM paket WHERE id_paket='$paket'";
            $querypaket = mysqli_query($koneksidb, $sqlpaket);
            $res = mysqli_fetch_array($querypaket);

            $no++;
            ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td>
                                                        <a href="#myModal" data-toggle="modal" data-load-code="<?php echo $result['id_trx']; ?>" data-remote-target="#myModal .modal-body"><?php echo $result['id_trx']; ?></a>
                                                    </td>
                                                    <td><?php echo IndonesiaTgl(htmlentities($result['tgl_trx'])); ?></td>
                                                    <td><?php echo IndonesiaTgl(htmlentities($result['tgl_bayar'])); ?></td>
                                                    <td><?php echo format_rupiah($res['harga']); ?></td>
                                                </tr>
                                            <?php }
        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="laporan_tahunan.php?tahun=<?php echo $tahun; ?>" target="_blank" class="btn btn-primary">Cetak</a>
                        </div>
                    <?php }?>




                    <!-- Large modal -->
                    <div class="modal fade bs-example-modal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <p>One fine body…</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Large modal -->


                </div>
            </div>

        </div>
        </div>
        </div>

        <!-- Loading Scripts -->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap-select.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/jquery.dataTables.min.js"></script>
        <script src="js/dataTables.bootstrap.min.js"></script>
        <script src="js/Chart.min.js"></script>
        <script src="js/fileinput.js"></script>
        <script src="js/chartData.js"></script>
        <script src="js/main.js"></script>


    </body>

    </html>
<?php }?>
