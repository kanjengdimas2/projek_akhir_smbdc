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
                    <a href="">Data riwayat update harga produk</a>
                </li>
            </ul>
        </div>

        <!-- Alert pesan terbaru -->
        <?php 
        $lastLog = mysqli_query($conn, "SELECT * FROM log_update_harga ORDER BY waktu_update DESC LIMIT 1");
        if ($row = mysqli_fetch_assoc($lastLog)) {
            echo '<div class="alert alert-info" role="alert">
                Harga produk <strong>' . $row['nama_produk'] . '</strong> telah diubah dari <strong>Rp' . number_format($row['harga_lama']) . '</strong> menjadi <strong>Rp' . number_format($row['harga_baru']) . '</strong> pada ' . $row['waktu_update'] . '.
            </div>';
        }
        ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Riwayat Update Harga Produk</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Log</th>
                                        <th>Nama Produk</th>
                                        <th>Harga Lama</th>
                                        <th>Harga Baru</th>
                                        <th>Waktu Update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $query = mysqli_query($conn, "SELECT * FROM log_update_harga ORDER BY waktu_update DESC");
                                    while ($data = mysqli_fetch_assoc($query)) {
                                        echo "<tr>
                                            <td>{$data['id_log']}</td>
                                            <td>{$data['nama_produk']}</td>
                                            <td>Rp " . number_format($data['harga_lama']) . "</td>
                                            <td>Rp " . number_format($data['harga_baru']) . "</td>
                                            <td>{$data['waktu_update']}</td>
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
