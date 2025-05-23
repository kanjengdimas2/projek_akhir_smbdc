<!-- breadcrumb-section -->
<div class="breadcrumb-section breadcrumb-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="breadcrumb-text">
                    <p>Clothes and Pants</p>
                    <h1>Shop</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end breadcrumb section -->

<!-- products -->
<div class="product-section mt-150 mb-150">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="product-filters">
                    <ul>
                        <li class="active" data-filter="*">All</li>
                        <li data-filter=".strawberry">Pria</li>
                        <li data-filter=".berry">Wanita</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row product-lists">
            <?php foreach (mysqli_query($conn, "SELECT * FROM produk p JOIN kategori_pakaian kt ON p.id_kategori_pakaian = kt.id_kategori_pakaian") as $d) {  ?>
            <div class="col-lg-4 col-md-6 text-center">
                <div class="single-product-item">
                    <div class="product-image">
                        <a href="detail?id=<?= $d['id_produk']; ?>"><img src="assets/<?= $d['gambar_pakaian']; ?>" alt=""></a>
                    </div>
                    <h3><?= $d['nama_produk']; ?></h3>
                    <p class="product-price">Rp <?= $d['harga']; ?></p>
                    <a href="detail?id=<?= $d['id_produk']; ?>" class="cart-btn"><i class="fas fa-shopping-cart"></i>Beli</a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- end products -->
