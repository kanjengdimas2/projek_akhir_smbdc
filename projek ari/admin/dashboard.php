<?php 
include '../koneksi.php';
if (isset($_POST['update'])) {
    $id = $_POST['update'];
    $stok = $_POST['stok'];

    $query = mysqli_query($conn, "UPDATE produk SET stok = '$stok' WHERE id_produk = $id");
}
?>
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
            </div>
        </div>

        <div>
        <div class="row">
            <div class="col-12">
                <h4 class="fw-bold mb-3">Rekap Penjualan Produk</h4>
                <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Terjual</th>
                            <th>Gambar</th>
                            <th>...</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $query = mysqli_query($conn, "SELECT * FROM vw_produk_terjual_dan_jumlah");
                            foreach ($query as $key => $row) {
                        ?>
                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= $row['nama_produk'] ?></td>
                                <td><?= $row['total_terjual'] ?></td>
                                <td><img src="./../assets/<?= $row['gambar_pakaian']; ?>" height="70"></td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_produk']; ?>"><i class="fas fa-pen"></i></button>
                                </td>
                            </tr>   
                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id_produk']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                                        </div>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="stok" class="form-label">Stok</label>
                                                    <input value="<?= $row['stok'] ?>" type="text" class="form-control" name="stok" id="stok" placeholder="Stok" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" type="submit" name="update" value="<?= $row['id_produk']; ?>">Edit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <h4 class="fw-bold mb-3">Produk Stok Sedikit</h4>
                <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th>Gambar</th>
                            <th>...</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $query = mysqli_query($conn, "CALL GetProdukStokSedikit()");
                            foreach ($query as $key => $row) { 
                        ?>
                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= $row['nama_produk'] ?></td>
                                <td><?= $row['stok'] ?></td>
                                <td><img src="./../assets/<?= $row['gambar_pakaian']; ?>" height="70"></td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_produk']; ?>"><i class="fas fa-pen"></i></button>
                                </td>
                            </tr>
                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id_produk']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                                        </div>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="stok" class="form-label">Stok</label>
                                                    <input value="<?= $row['stok'] ?>" type="text" class="form-control" name="stok" id="stok" placeholder="Stok" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" type="submit" name="update" value="<?= $row['id_produk']; ?>">Edit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <h4 class="fw-bold mb-3">Produk Stok Banyak</h4>
                <table id="basic-datatables" class="display table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Stok</th>
                            <th>Gambar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            mysqli_next_result($conn);
                            $query = mysqli_query($conn, "SELECT * FROM vw_produk_stok_banyak");
                            foreach ($query as $key => $row) { 
                        ?>
                            <tr>
                                <td><?= $key+1 ?></td>
                                <td><?= $row['nama_produk'] ?></td>
                                <td><?= $row['stok'] ?></td>
                                <td><img src="./../assets/<?= $row['gambar_pakaian']; ?>" height="70"></td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_produk']; ?>"><i class="fas fa-pen"></i></button>
                                </td>
                            </tr>
                            <!-- Modal Edit -->
                            <div class="modal fade" id="modalEdit<?= $row['id_produk']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                                        </div>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="stok" class="form-label">Stok</label>
                                                    <input value="<?= $row['stok'] ?>" type="text" class="form-control" name="stok" id="stok" placeholder="Stok" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary" type="submit" name="update" value="<?= $row['id_produk']; ?>">Edit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
