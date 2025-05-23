<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>See more Details</p>
                    <h1>Single Product</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->
<?php 
    $id = $_GET['id'];
    $query = mysqli_query($conn, 
        "SELECT * FROM produk p 
        JOIN kategori_pakaian kt ON p.id_kategori_pakaian = kt.id_kategori_pakaian
        WHERE id_produk = '$id'"
    );
    $data = mysqli_fetch_array($query);

    if (isset($_POST['add'])) {
        $id_pelanggan = $_SESSION['userLogin']['id_pelanggan'];
        $total = $_POST['total'];
        $jumlah = $_POST['jumlah'];
        if ($jumlah <= $data['stok']) {
            $query = mysqli_query($conn, "CALL tambah_transaksi('$id_pelanggan','$id','$jumlah','$total')");
            if ($query) {
                echo "<script>alert('Berhasil')</script>";
            }
        } else {
            echo "<script>alert('Jumlah melebihi stok tersedia');</script>";
        }
    }
?>
<!-- single product -->
<div class="single-product mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="single-product-img">
                    <img src="assets/<?= $data['gambar_pakaian']; ?>" alt="">
                </div>
            </div>
            <div class="col-md-3">
            </div>
            <div class="col-lg-4">
                <div class="order-details-wrap">
                    <form action="" method="post">
                        <table class="order-details">
                            <thead>
                                <tr>
                                    <th>Details Product</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody class="order-details-body">
                                <tr>
                                    <td>Nama Produk</td>
                                    <td><?= $data['nama_produk']; ?></td>
                                </tr>
                                <tr>
                                    <td>Harga Produk</td>
                                    <td id="harga"><?= number_format($data['harga'], 0, ',', '.'); ?></td>
                                </tr>
                                <tr>
                                    <td>Stok Produk</td>
                                    <td><?= $data['stok']; ?></td>
                                </tr>
                                <tr>
                                    <td>Jumlah</td>
                                    <td>
                                        <input type="number" name="jumlah" id="jumlah-barang" placeholder="1" min="1" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Harga</td>
                                    <td id="total">
                                        <?= number_format($data['harga'], 0, ',', '.'); ?>
                                    </td>
                                    <input type="hidden" id="totalForm" name="total" value="<?= number_format($data['harga'], 0, ',', '.'); ?>">
                                </tr>   
                            </tbody>
                        </table>
                        <button type="submit" name="add" class="cart-btn">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end single product -->
 <script>
    function hitungTotal() {
        const harga = parseInt(document.getElementById('harga').textContent.replace(/[^0-9]/g, ''));
        const jumlahBarang = parseInt(document.getElementById('jumlah-barang').value);

        const total = harga * jumlahBarang;
        document.getElementById('total').textContent = total;
        document.getElementById('totalForm').value = new Intl.NumberFormat('id-ID').format(total);
    }

    function ubahJumlah(jumlah) {
        const input = document.getElementById('jumlah-barang');
        let nilai = parseInt(input.value);
        nilai += jumlah;
        if (nilai < 1) nilai = 1;
        input.value = nilai;
        hitungTotal();
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('jumlah-barang').addEventListener('input', hitungTotal);
    });
</script>