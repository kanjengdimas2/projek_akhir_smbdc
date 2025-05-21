<?php 
include '../koneksi.php';
?>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Data</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="dashboard">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="">Data riwayat produk dihapus</a>
                </li>
            </ul>
        </div>

        <!-- Alert jika ada data terakhir -->
        <?php 
        $last = mysqli_query($conn, "SELECT * FROM log_hapus_produk ORDER BY waktu_hapus DESC LIMIT 1");
        if ($row = mysqli_fetch_assoc($last)) {
            echo '<div class="alert alert-danger" role="alert">
                Produk <strong>' . $row['nama_produk'] . '</strong> telah dihapus pada ' . $row['waktu_hapus'] . ' (Harga: Rp' . number_format($row['harga']) . ', Stok: ' . $row['stok'] . ').
            </div>';
        }
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data Riwayat Produk Dihapus</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Log</th>
                                        <th>ID Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Waktu Hapus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $query = mysqli_query($conn, "SELECT * FROM log_hapus_produk ORDER BY waktu_hapus DESC");
                                    while ($data = mysqli_fetch_assoc($query)) {
                                        echo "<tr>
                                            <td>{$data['id_log']}</td>
                                            <td>{$data['id_produk']}</td>
                                            <td>{$data['nama_produk']}</td>
                                            <td>Rp " . number_format($data['harga']) . "</td>
                                            <td>{$data['stok']}</td>
                                            <td>{$data['waktu_hapus']}</td>
                                        </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
