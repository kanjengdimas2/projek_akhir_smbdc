<?php
include '../koneksi.php';

// Handle Tambah
if (isset($_POST['add'])) {
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['id_kategori_pakaian'];
    $ukuran = $_POST['id_kategori_ukuran'];

    mysqli_query($conn, "INSERT INTO produk (nama_produk, harga, stok, id_kategori_pakaian, id_kategori_ukuran) 
                         VALUES ('$nama', '$harga', '$stok', '$kategori', '$ukuran')");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle Update
if (isset($_POST['update'])) {
    $id = $_POST['id_produk'];
    $nama = $_POST['nama_produk'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $_POST['id_kategori_pakaian'];
    $ukuran = $_POST['id_kategori_ukuran'];

    mysqli_query($conn, "UPDATE produk SET 
        nama_produk='$nama', harga='$harga', stok='$stok', 
        id_kategori_pakaian='$kategori', id_kategori_ukuran='$ukuran' 
        WHERE id_produk='$id'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle Delete
if (isset($_POST['delete'])) {
    $id = $_POST['id_produk'];
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk='$id'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Handle Jalankan Stored Procedure kurangi_stok_dari_penjualan
if (isset($_POST['kurangi_stok'])) {
    $id_penjualan = $_POST['id_penjualan_kurang_stok'];
    mysqli_query($conn, "CALL kurangi_stok_dari_penjualan($id_penjualan)");
    echo "<script>alert('Stok berhasil dikurangi untuk penjualan ID: $id_penjualan'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
    exit;
}

// Handle Jalankan Stored Procedure UpdateHargaProduk
if (isset($_POST['ubah_harga'])) {
    $id_produk = $_POST['id_produk_ubah_harga'];
    $harga_baru = $_POST['harga_baru'];
    mysqli_query($conn, "CALL UpdateHargaProduk($id_produk, $harga_baru)");
    echo "<script>alert('Harga berhasil diubah untuk produk ID: $id_produk'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
    exit;
}

// Ambil Data Kategori & Ukuran
$kategoriList = mysqli_query($conn, "SELECT * FROM kategori_pakaian");
$ukuranList = mysqli_query($conn, "SELECT * FROM kategori_ukuran");
?>

<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Data Produk</h3>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <!-- Form Kurangi Stok -->
                    <form method="post" class="d-flex align-items-center me-3">
                        <input type="number" name="id_penjualan_kurang_stok" class="form-control form-control-sm me-2" placeholder="ID Penjualan" required style="width: 130px;">
                        <button type="submit" name="kurangi_stok" class="btn btn-danger btn-sm">Kurangi Stok</button>
                    </form>

                    <!-- Form Ubah Harga -->
                    <form method="post" class="d-flex align-items-center">
                        <input type="number" name="id_produk_ubah_harga" class="form-control form-control-sm me-2" placeholder="ID Produk" required style="width: 130px;">
                        <input type="number" name="harga_baru" class="form-control form-control-sm me-2" placeholder="Harga Baru" required style="width: 130px;">
                        <button type="submit" name="ubah_harga" class="btn btn-warning btn-sm">Ubah Harga</button>
                    </form>
                </div>

                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah</button>
            </div>

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Ukuran</th>
                            <th>Kategori</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = mysqli_query($conn, "
                            SELECT p.*, kp.nama_kategori_pakaian, ku.nama_kategori_ukuran 
                            FROM produk p 
                            LEFT JOIN kategori_pakaian kp ON p.id_kategori_pakaian = kp.id_kategori_pakaian 
                            LEFT JOIN kategori_ukuran ku ON p.id_kategori_ukuran = ku.id_kategori_ukuran
                        ");
                        while ($row = mysqli_fetch_assoc($data)) {
                        ?>
                        <tr>
                            <td><?= $row['id_produk'] ?></td>
                            <td><?= $row['nama_produk'] ?></td>
                            <td><?= $row['harga'] ?></td>
                            <td><?= $row['stok'] ?></td>
                            <td><?= $row['nama_kategori_ukuran'] ?></td>
                            <td><?= $row['nama_kategori_pakaian'] ?></td>
                            <td><?= $row['gambar_pakaian'] ?: '-' ?></td>
                            <td>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detail<?= $row['id_produk'] ?>">Detail</button>
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $row['id_produk'] ?>">Edit</button>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?= $row['id_produk'] ?>">Hapus</button>
                            </td>
                        </tr>

                        <!-- Modal Modal lainnya tetap sama -->
                        <!-- Modal Detail, Edit, Hapus, Tambah (sudah sesuai sebelumnya) -->

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post">
                <div class="modal-header"><h5>Tambah Produk</h5></div>
                <div class="modal-body">
                    <label>Nama</label>
                    <input type="text" class="form-control" name="nama_produk" required>
                    <label>Harga</label>
                    <input type="text" class="form-control" name="harga" required>
                    <label>Stok</label>
                    <input type="text" class="form-control" name="stok" required>
                    <label>Kategori</label>
                    <select name="id_kategori_pakaian" class="form-control">
                        <?php foreach ($kategoriList as $kat) { ?>
                            <option value="<?= $kat['id_kategori_pakaian'] ?>"><?= $kat['nama_kategori_pakaian'] ?></option>
                        <?php } ?>
                    </select>
                    <label>Ukuran</label>
                    <select name="id_kategori_ukuran" class="form-control">
                        <?php foreach ($ukuranList as $uku) { ?>
                            <option value="<?= $uku['id_kategori_ukuran'] ?>"><?= $uku['nama_kategori_ukuran'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" name="add">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
