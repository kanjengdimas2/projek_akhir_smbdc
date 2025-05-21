<?php
include 'koneksi.php'; // koneksi ke database

$id_produk = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$ukuran_result = [];

// Panggil stored procedure untuk mendapatkan ukuran produk
if ($id_produk > 0) {
    $stmt = $conn->prepare("CALL GetUkuranProduk(?)");
    $stmt->bind_param("i", $id_produk);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $ukuran_result[] = $row;
    }
    $stmt->close();
    $conn->next_result();
}

// Proses saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $nama    = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat  = mysqli_real_escape_string($conn, $_POST['alamat']);

    if (!empty($nama) && !empty($alamat)) {
        $query = "INSERT INTO pelanggan (nama, alamat) VALUES ('$nama', '$alamat')";
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Data pelanggan berhasil disimpan!');</script>";
        } else {
            echo "<script>alert('Gagal menyimpan data pelanggan: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Nama dan alamat wajib diisi!');</script>";
    }
}
?>

<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Clothes and Pants</p>
                    <h1>Check Out Product</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- check out section -->
<div class="checkout-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-accordion-wrap">
                    <div class="accordion" id="accordionExample">
                        <div class="card single-accordion">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" type="button" data-toggle="collapse"
                                        data-target="#collapseOne" aria-expanded="true"
                                        aria-controls="collapseOne">
                                        Billing Address
                                    </button>
                                </h5>
                            </div>

                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                data-parent="#accordionExample">
                                <div class="card-body">
                                    <div class="billing-address-form">
                                        <!-- Form mulai dari sini -->
                                        <form action="" method="post">
                                            <p><input type="text" name="nama" placeholder="Name" required></p>
                                            <p><input type="text" name="alamat" placeholder="Address" required></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4">
                <div class="order-details-wrap">
                    <table class="order-details">
                        <thead>
                            <tr>
                                <th>Your order Details</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody class="order-details-body">
                                <tr>
                                    <td>Nama Produk</td>
                                    <td>asd</td>
                                </tr>
                                <tr>
                                    <td>Harga Produk</td>
                                    <td>asd</td>
                                </tr>
                                <tr>
                                    <td>Stok Produk</td>
                                    <td>asd</td>
                                </tr>
                                <tr>
                                    <td>Ukuran</td>
                                    <td>
                                        <select name="ukuran" required>
                                            <option value="">Pilih Ukuran</option>
                                            <?php foreach ($ukuran_result as $ukuran): ?>
                                                <option value="<?= $ukuran['id_kategori_ukuran'] ?>">
                                                    <?= htmlspecialchars($ukuran['nama_kategori_ukuran']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jumlah</td>
                                    <td>
                                        <input type="number" name="jumlah" placeholder="0" min="1" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Harga</td>
                                    <td>asd</td>
                                </tr>
                        </tbody>
                    </table>
                    <button type="submit" name="place_order" class="boxed-btn">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end check out section -->
