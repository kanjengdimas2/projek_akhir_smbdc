<?php
    if (isset($_POST['add'])) {
        $nama_produk = $_POST['nama_produk'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        $id_kategori_pakaian = $_POST['id_kategori_pakaian'];
        $gambar_pakaian = "";
        if(!empty($_FILES['gambar_pakaian']['name'])) {
            $rand = rand();
            $filename = $_FILES['gambar_pakaian']['name'];
            $gambar_pakaian = 'img/'.$rand.'_'.$filename;
        } else {
            $gambar_pakaian = "";
        }

        move_uploaded_file($_FILES['gambar_pakaian']['tmp_name'], './../assets/'.$gambar_pakaian);
        $query = mysqli_query($conn, "INSERT INTO produk VALUES ('', '$nama_produk', '$harga', '$stok','$gambar_pakaian', '$id_kategori_pakaian')");
    }
    if (isset($_POST['update'])) {
        $id = $_POST['update'];
        $nama_produk = $_POST['nama_produk'];
        $harga = $_POST['harga'];
        $stok = $_POST['stok'];
        $id_kategori_pakaian = $_POST['id_kategori_pakaian'];
        $gambar = $_POST['gambar'];
        $gambar_pakaian = "";
        if(!empty($_FILES['gambar_pakaian']['name'])) {
            $rand = rand();
            $filename = $_FILES['gambar_pakaian']['name'];
            $gambar_pakaian = 'img/'.$rand.'_'.$filename;
            move_uploaded_file($_FILES['gambar_pakaian']['tmp_name'], './../assets/'.$gambar_pakaian);
        } else {
            $gambar_pakaian = $gambar;
        }

        $query = mysqli_query($conn, "UPDATE produk SET nama_produk = '$nama_produk', harga = '$harga', stok = '$stok', gambar_pakaian = '$gambar_pakaian', id_kategori_pakaian = '$id_kategori_pakaian' WHERE id_produk = $id");
    }
    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];

        $query = mysqli_query($conn, "DELETE FROM produk WHERE id_produk = $id");
    }

    $data = null;
    if (isset($_POST['cariin'])) {
        $cari = $_POST['cari'];

        $data = mysqli_query($conn, "CALL CariProduk('$cari')");
    }
?>
<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Data Merchandise</h3>
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
                    <a href="">Data Merchandise</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Data Merchandise</h4>
                        <form class="d-none d-sm-inline-block form-inline navbar-search" style="width: 500px;" action="" method="post">
                            <div class="input-group">
                                <input name="cari" type="text" class="form-control bg-light border-2 border-primary small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button name="cariin" class="btn btn-primary" type="submit">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                            Tambah
                        </button>
                    </div>
                    <div class="card-body">
                        <table id="basic-datatables" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Gambar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!isset($_POST['cariin'])) {
                                    $data = mysqli_query($conn, "SELECT * FROM vw_all_data_produk");
                                }
                                if (mysqli_num_rows($data)) {
                                foreach ($data as $key => $row) {
                                ?>
                                <tr>
                                    <td><?= $key+1 ?></td>
                                    <td><?= $row['nama_produk'] ?></td>
                                    <td><?= $row['harga'] ?></td>
                                    <td><?= $row['stok'] ?></td>
                                    <td><img src="./../assets/<?= $row['gambar_pakaian']; ?>" height="70"></td>
                                    <td>
                                        <button type="button" class="btn btn-icon btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id_produk']; ?>"><i class="fas fa-info"></i></button>
                                        <button type="button" class="btn btn-icon btn-warning" data-bs-toggle="modal" data-bs-target="#modalEdit<?= $row['id_produk']; ?>"><i class="fas fa-pen"></i></button>
                                        <button type="button" class="btn btn-icon btn-danger" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row['id_produk']; ?>"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <!-- Modal Detail -->
                                <div class="modal fade" id="modalDetail<?= $row['id_produk']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Detail Data</h5>
                                            </div>
                                            <div class="modal-body">
                                                <p class="fw-bold"><?= $row['nama_produk']; ?></p>
                                                <img class="mb-3" src="./../assets/<?= $row['gambar_pakaian']; ?>" alt="<?= $row['gambar_pakaian']; ?>" width="300">
                                                <p>Pakaian : <?= $row['nama_kategori_pakaian']; ?></p>
                                                <p>Harga : <?= $row['harga']; ?></p>
                                                <p>Stok : <?= $row['stok']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Edit -->
                                <div class="modal fade" id="modalEdit<?= $row['id_produk']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
                                            </div>
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <div class="modal-body">
                                                    <img src="./../assets/<?= $row['gambar_pakaian']; ?>" alt="<?= $row['gambar_pakaian']; ?>" width="300" class="mb-3">
                                                    <input type="hidden" name="gambar" value="<?= $row['gambar_pakaian']; ?>">
                                                    <div class="mb-3">
                                                        <label for="gambar_pakaian" class="form-label">Gambar</label>
                                                        <input type="file" class="form-control" name="gambar_pakaian" id="gambar_pakaian" placeholder="Gambar">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="nama_produk" class="form-label">Nama</label>
                                                        <input value="<?= $row['nama_produk'] ?>" type="text" class="form-control" name="nama_produk" id="nama_produk" placeholder="Nama" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="harga" class="form-label">Harga</label>
                                                        <input value="<?= $row['harga'] ?>" type="text" class="form-control" name="harga" id="harga" placeholder="Harga" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="stok" class="form-label">Stok</label>
                                                        <input value="<?= $row['stok'] ?>" type="text" class="form-control" name="stok" id="stok" placeholder="Stok" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="id_kategori_pakaian" class="form-label">Kategori Pakaian</label>
                                                        <select name="id_kategori_pakaian" id="id_kategori_pakaian" class="form-control">
                                                            <option value="<?= $row['id_kategori_pakaian']; ?>"><?= $row['nama_kategori_pakaian']; ?></option>
                                                            <?php foreach (mysqli_query($conn, "SELECT * FROM kategori_pakaian") as $kp) { ?>
                                                            <option value="<?= $kp['id_kategori_pakaian']; ?>"><?= $kp['nama_kategori_pakaian']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" type="submit" name="update" value="<?= $row['id_produk']; ?>">Edit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal Hapus -->
                                <div class="modal fade" id="modalHapus<?= $row['id_produk']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Hapus Data</h5>
                                            </div>
                                            <form action="" method="post">
                                                <div class="modal-body">
                                                    <p>Yakin ingin hapus?</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button class="btn btn-primary" value="<?= $row['id_produk']; ?>" type="submit" name="delete">Hapus</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php }} else { ?>
                                <tr>
                                    <td colspan="8"><center>Tidak ada data</center></td>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="gambar_pakaian" class="form-label">Gambar</label>
                        <input type="file" class="form-control" name="gambar_pakaian" id="gambar_pakaian" placeholder="Gambar" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_produk" class="form-label">Nama</label>
                        <input type="text" class="form-control" name="nama_produk" id="nama_produk" placeholder="Nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="text" class="form-control" name="harga" id="harga" placeholder="Harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="text" class="form-control" name="stok" id="stok" placeholder="Stok" required>
                    </div>
                    <div class="mb-3">
                        <label for="id_kategori_pakaian" class="form-label">Kategori Pakaian</label>
                        <select name="id_kategori_pakaian" id="id_kategori_pakaian" class="form-control">
                            <?php foreach (mysqli_query($conn, "SELECT * FROM kategori_pakaian") as $kp) { ?>
                            <option value="<?= $kp['id_kategori_pakaian']; ?>"><?= $kp['nama_kategori_pakaian']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" name="add">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>