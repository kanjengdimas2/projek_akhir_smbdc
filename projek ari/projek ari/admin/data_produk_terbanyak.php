<?php 
include '../koneksi.php'; // Koneksi ke database toko_baju
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
                    <a href="">Data produk Terbanyak</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data produk Terbanyak</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $query = mysqli_query($conn, "SELECT * FROM vw_produk_stok_banyak");
                                    while ($data = mysqli_fetch_array($query)) {
                                    ?>
                                    <tr>
                                        <td><?= $data['id_produk'] ?></td>
                                        <td><?= $data['nama_produk'] ?></td>
                                        <td><?= $data['stok'] ?></td>
                                        <td>
                                            <button type="button" class="btn btn-icon btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $data['id_produk'] ?>">
                                                <i class="fas fa-info"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail -->
                                    <div class="modal fade" id="modalDetail<?= $data['id_produk'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $data['id_produk'] ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalLabel<?= $data['id_produk'] ?>">Detail Produk</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>ID Produk:</strong> <?= $data['id_produk'] ?></p>
                                                    <p><strong>Nama Produk:</strong> <?= $data['nama_produk'] ?></p>
                                                    <p><strong>Stok:</strong> <?= $data['stok'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
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
