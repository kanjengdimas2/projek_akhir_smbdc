<!-- hero area -->
<div class="hero-area hero-bg">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 offset-lg-2 text-center">
                <div class="hero-text">
                    <div class="hero-text-tablecell">
                        <p class="subtitle">BAJU</p>
                        <h1>Toko Baju Telang</h1>
                        <div class="hero-btns">
                            <a href="produk" class="boxed-btn">Baju Lainnya</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end hero area -->

<!-- product section -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">	
                    <h3><span class="orange-text">Produk </span>Segera Habis</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet beatae optio.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <?php foreach (mysqli_query($conn, "CALL GetProdukStokSedikit()") as $d) { ?>
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
<!-- end product section -->

 <!-- product section -->
<div class="product-section mt-150 mb-150">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 offset-lg-2 text-center">
                <div class="section-title">	
                    <h3><span class="orange-text">Produk</span> Terlaris </h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid, fuga quas itaque eveniet beatae optio.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <?php 
            mysqli_next_result($conn);
            $data = mysqli_query($conn, "SELECT * FROM vw_produk_terlaris");
            foreach ($data as $d) { 
            ?>
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
<!-- end product section -->