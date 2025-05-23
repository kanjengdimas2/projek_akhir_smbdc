<?php 
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
                    <a href="">Data Transaksi</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data Transaksi</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>Produk</th>
                                        <th>Jumlah</th>
                                        <th>Total Harga</th>
                                        <th>...</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (mysqli_query($conn, "SELECT * FROM view_data_transaksi") as $key => $row) { ?>
                                        <tr>
                                            <td><?= $row['id_penjualan'] ?></td>
                                            <td><?= $row['tanggal'] ?></td>
                                            <td><?= $row['nama'] ?></td>
                                            <td><?= $row['nama_produk'] ?></td>
                                            <td><?= $row['jumlah'] ?></td>
                                            <td>Rp<?= number_format($row['subtotal'], 0, ',', '.') ?></td>
                                            <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id_penjualan'] . $row['nama_produk'] ?>">
                                                    <i class="fas fa-info"></i>
                                                </button>

                                                <!-- Modal Detail -->
                                                <div class="modal fade" id="modalDetail<?= $row['id_penjualan'] . $row['nama_produk'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $row['id_penjualan'] . $row['nama_produk'] ?>" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalLabel<?= $row['id_penjualan'] . $row['nama_produk'] ?>">Detail Data</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><strong>ID Penjualan:</strong> <?= $row['id_penjualan'] ?></p>
                                                                <p><strong>Tanggal:</strong> <?= $row['tanggal'] ?></p>
                                                                <p><strong>Nama Pelanggan:</strong> <?= $row['nama'] ?></p>
                                                                <p><strong>Produk:</strong> <?= $row['nama_produk'] ?></p>
                                                                <p><strong>Jumlah:</strong> <?= $row['jumlah'] ?></p>
                                                                <p><strong>Subtotal:</strong> Rp<?= number_format($row['subtotal'], 0, ',', '.') ?></p>
                                                                <p><strong>Total Harga:</strong> Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
