<?php 
include '../koneksi.php';
?>
<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Dashboard</h3>
            </div>
        </div>

        <h4 class="fw-bold mb-3">Merchandise Terlaris</h4>
        <div class="row">
            <?php 
                $terlaris = mysqli_query($conn, "SELECT * FROM `vw_produk_terlaris`");
                $terlaris = mysqli_fetch_array($terlaris);
            ?>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <img src="./../<?= $terlaris['image_url']; ?>" alt="<?= $terlaris['image_url']; ?>" style="width: 100%;">
                        </div>
                        <div class="numbers">
                            <p class="card-category"><?= $terlaris['name']; ?></p>
                            <h4 class="card-title">Terjual <?= $terlaris['total_terjual']; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h4 class="fw-bold mb-3">Merchandise Stok Terbanyak</h4>
        <div class="row">
            <?php 
                $terbanyak = mysqli_query($conn, "SELECT * FROM view_merch_stok_terbanyak");
                foreach ($terbanyak as $key => $b) {
            ?>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <img src="./../<?= $b['image_url']; ?>" alt="<?= $b['image_url']; ?>" style="width: 100%;">
                        </div>
                        <div class="numbers">
                            <p class="card-category"><?= $b['name']; ?></p>
                            <h4 class="card-title">Stok: <?= $b['stock']; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <h4 class="fw-bold mb-3">Merchandise Stok Tersedikit</h4>
        <div class="row">
            <?php 
                $tersedikit = mysqli_query($conn, "SELECT * FROM `view_merch_stok_tersedikit`");
                foreach ($tersedikit as $key => $s) {
            ?>
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="icon-big text-center icon-primary bubble-shadow-small">
                            <img src="./../<?= $s['image_url']; ?>" alt="<?= $s['image_url']; ?>" style="width: 100%;">
                        </div>
                        <div class="numbers">
                            <p class="card-category"><?= $s['name']; ?></p>
                            <h4 class="card-title">Stok: <?= $s['stock']; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <h4 class="fw-bold mb-3 mt-5">Data Transaksi</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Penjualan</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $transaksi = mysqli_query($conn, "SELECT * FROM view_data_transaksi");
                        if (mysqli_num_rows($transaksi) > 0) {
                            while ($row = mysqli_fetch_assoc($transaksi)) {
                                echo "<tr>";
                                echo "<td>" . $row['id_penjualan'] . "</td>";
                                echo "<td>" . $row['nama_pelanggan'] . "</td>";
                                echo "<td>" . $row['tanggal'] . "</td>";
                                echo "<td>" . $row['nama_produk'] . "</td>";
                                echo "<td>" . $row['jumlah'] . "</td>";
                                echo "<td>Rp. " . number_format($row['subtotal'], 0, ',', '.') . "</td>";
                                echo "<td>Rp. " . number_format($row['total_harga'], 0, ',', '.') . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>Tidak ada data transaksi.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
