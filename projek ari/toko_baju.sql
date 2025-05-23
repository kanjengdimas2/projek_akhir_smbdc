-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Bulan Mei 2025 pada 01.56
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `toko_baju`
--

DELIMITER $$
--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CariProduk` (IN `keyword` VARCHAR(100))   BEGIN
    SELECT 
        p.*
    FROM produk p
    WHERE p.nama_produk LIKE CONCAT('%', keyword, '%');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetProdukStokSedikit` ()   BEGIN
    SELECT * FROM produk p
    JOIN kategori_pakaian kt ON p.id_kategori_pakaian = kt.id_kategori_pakaian 
    WHERE stok <= 5
    ORDER BY stok ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `kurangi_stok_dari_penjualan` (IN `pid_penjualan` INT)   BEGIN
  DECLARE selesai INT DEFAULT 0;
  DECLARE idProduk INT;
  DECLARE jumlahBeli INT;

  -- Cursor untuk ambil semua produk di detail_penjualan dari id_penjualan tersebut
  DECLARE cur CURSOR FOR
    SELECT id_produk, jumlah
    FROM detail_penjualan
    WHERE id_penjualan = pid_penjualan;

  -- Handler jika cursor selesai
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET selesai = 1;

  -- Buka cursor
  OPEN cur;

  kurangi_loop: LOOP
    FETCH cur INTO idProduk, jumlahBeli;
    IF selesai = 1 THEN
      LEAVE kurangi_loop;
    END IF;

    -- Kurangi stok dari produk
    UPDATE produk
    SET stok = stok - jumlahBeli
    WHERE id_produk = idProduk;
  END LOOP;

  CLOSE cur;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `tambah_transaksi` (IN `pid_pelanggan` INT, IN `pid_produk` INT, IN `pjumlah` INT, IN `ptotal` INT)   BEGIN
    DECLARE pharga INT;
    DECLARE ptgl DATE;
    DECLARE pid_penjualan INT;

    -- Hitung harga satuan
    SET pharga = ptotal / pjumlah;
    SET ptgl = CURDATE();

    -- Insert ke tabel penjualan
    INSERT INTO penjualan (id_pelanggan, tanggal, total_harga) 
    VALUES (pid_pelanggan, ptgl, ptotal);

    -- Ambil ID penjualan terakhir
    SET pid_penjualan = LAST_INSERT_ID();

    -- Insert ke detail_penjualan
    INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, subtotal) 
    VALUES (pid_penjualan, pid_produk, pjumlah, pharga);

    -- Panggil prosedur untuk kurangi stok
    CALL kurangi_stok_dari_penjualan(pid_penjualan);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateStokProduk` (IN `p_id_produk` INT, IN `p_stok_baru` DECIMAL(10,2))   BEGIN
    UPDATE produk
    SET stok= p_stok_baru
    WHERE id_produk = p_id_produk;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_penjualan`
--

CREATE TABLE `detail_penjualan` (
  `id_detail` int(11) NOT NULL,
  `id_penjualan` int(11) DEFAULT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_penjualan`
--

INSERT INTO `detail_penjualan` (`id_detail`, `id_penjualan`, `id_produk`, `jumlah`, `subtotal`) VALUES
(2, 6, 10, 2, 123.00),
(3, 7, 9, 2, 123.00),
(4, 8, 9, 2, 123.00),
(5, 9, 9, 2, 123.00),
(6, 10, 10, 3, 0.00),
(7, 11, 10, 2, 453.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori_pakaian`
--

CREATE TABLE `kategori_pakaian` (
  `id_kategori_pakaian` int(11) NOT NULL,
  `nama_kategori_pakaian` enum('pria','wanita') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori_pakaian`
--

INSERT INTO `kategori_pakaian` (`id_kategori_pakaian`, `nama_kategori_pakaian`) VALUES
(1, 'pria'),
(2, 'wanita');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_hapus_produk`
--

CREATE TABLE `log_hapus_produk` (
  `id_log` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL,
  `waktu_hapus` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_hapus_produk`
--

INSERT INTO `log_hapus_produk` (`id_log`, `id_produk`, `nama_produk`, `harga`, `stok`, `waktu_hapus`) VALUES
(1, 1, 'KEMEJA DISTRO', 200.00, 20, '2025-05-22 19:14:07'),
(2, 2, 'KEMEJA DISTRO', 200.00, 20, '2025-05-22 19:14:07'),
(3, 3, 'KEMEJA DISTRO', 200.00, 20, '2025-05-22 19:14:07'),
(4, 4, 'KEMEJA DISTRO', 200.00, 20, '2025-05-22 19:14:07'),
(5, 5, 'KEMEJA DISTRO', 200.00, 20, '2025-05-22 19:14:07'),
(6, 6, 'KEMEJA DISTRO', 200.00, 20, '2025-05-22 19:14:07'),
(7, 7, 'ASD', 123.00, 123, '2025-05-22 19:15:55'),
(8, 8, 'ASDasd', 123.00, 123, '2025-05-22 19:28:26'),
(9, 11, 'BAJU WANITA 1', 200.00, 0, '2025-05-23 06:21:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_update_harga`
--

CREATE TABLE `log_update_harga` (
  `id_log` int(11) NOT NULL,
  `id_produk` int(11) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga_lama` decimal(10,2) DEFAULT NULL,
  `harga_baru` decimal(10,2) DEFAULT NULL,
  `waktu_update` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `log_update_harga`
--

INSERT INTO `log_update_harga` (`id_log`, `id_produk`, `nama_produk`, `harga_lama`, `harga_baru`, `waktu_update`) VALUES
(1, 10, 'sdf', 123.00, 453.00, '2025-05-23 01:10:40'),
(2, 10, 'kaos pria', 453.00, 450.00, '2025-05-23 06:21:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int(11) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `email`, `password`, `alamat`) VALUES
(1, 'Nur Muhammad', 'nurm@gmail.com', '123', 'asd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `id_pelanggan` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `total_harga` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_pelanggan`, `tanggal`, `total_harga`) VALUES
(6, 1, '2025-05-22', 246.00),
(7, 1, '2025-05-22', 246.00),
(8, 1, '2025-05-22', 246.00),
(9, 1, '2025-05-22', 246.00),
(10, 1, '2025-05-23', 1.00),
(11, 1, '2025-05-23', 906.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `stok` int(11) NOT NULL,
  `gambar_pakaian` varchar(255) NOT NULL,
  `id_kategori_pakaian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `harga`, `stok`, `gambar_pakaian`, `id_kategori_pakaian`) VALUES
(9, 'baju pria 1', 123.00, 0, 'img/24273198_756103011_602442513_baju pria 2.jpeg', 1),
(10, 'kaos pria', 450.00, 50, 'img/2039056913_187431692_baju pria.jpg', 1),
(12, 'BAJU WANITA 1', 200.00, 20, 'img/802165947_1934589457_baju wanita 2.jpg', 2);

--
-- Trigger `produk`
--
DELIMITER $$
CREATE TRIGGER `after_delete_produk` AFTER DELETE ON `produk` FOR EACH ROW BEGIN
  INSERT INTO log_hapus_produk (
    id_produk,
    nama_produk,
    harga,
    stok
  ) VALUES (
    OLD.id_produk,
    OLD.nama_produk,
    OLD.harga,
    OLD.stok
  );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_update_harga_produk` AFTER UPDATE ON `produk` FOR EACH ROW BEGIN
  -- Hanya catat jika harga memang berubah
  IF OLD.harga != NEW.harga THEN
    INSERT INTO log_update_harga (
      id_produk,
      nama_produk,
      harga_lama,
      harga_baru
    ) VALUES (
      NEW.id_produk,
      NEW.nama_produk,
      OLD.harga,
      NEW.harga
    );
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_capitalize_nama_produk` BEFORE INSERT ON `produk` FOR EACH ROW SET NEW.nama_produk = UPPER(NEW.nama_produk)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `view_data_transaksi`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `view_data_transaksi` (
`id_pelanggan` int(11)
,`nama` varchar(50)
,`email` varchar(255)
,`password` varchar(255)
,`alamat` text
,`tanggal` date
,`id_penjualan` int(11)
,`total_harga` decimal(10,2)
,`id_detail` int(11)
,`jumlah` int(11)
,`subtotal` decimal(10,2)
,`id_produk` int(11)
,`nama_produk` varchar(100)
,`harga` decimal(10,2)
,`stok` int(11)
,`gambar_pakaian` varchar(255)
,`id_kategori_pakaian` int(11)
,`nama_kategori_pakaian` enum('pria','wanita')
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vw_all_data_produk`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vw_all_data_produk` (
`id_produk` int(11)
,`nama_produk` varchar(100)
,`harga` decimal(10,2)
,`stok` int(11)
,`gambar_pakaian` varchar(255)
,`id_kategori_pakaian` int(11)
,`nama_kategori_pakaian` enum('pria','wanita')
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vw_produk_stok_banyak`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vw_produk_stok_banyak` (
`id_produk` int(11)
,`nama_produk` varchar(100)
,`harga` decimal(10,2)
,`stok` int(11)
,`gambar_pakaian` varchar(255)
,`id_kategori_pakaian` int(11)
,`nama_kategori_pakaian` enum('pria','wanita')
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vw_produk_terjual_dan_jumlah`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vw_produk_terjual_dan_jumlah` (
`id_produk` int(11)
,`nama_produk` varchar(100)
,`gambar_pakaian` varchar(255)
,`total_terjual` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `vw_produk_terlaris`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `vw_produk_terlaris` (
`id_produk` int(11)
,`nama_produk` varchar(100)
,`harga` decimal(10,2)
,`gambar_pakaian` varchar(255)
,`total_terjual` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `view_data_transaksi`
--
DROP TABLE IF EXISTS `view_data_transaksi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_data_transaksi`  AS SELECT `pl`.`id_pelanggan` AS `id_pelanggan`, `pl`.`nama` AS `nama`, `pl`.`email` AS `email`, `pl`.`password` AS `password`, `pl`.`alamat` AS `alamat`, `p`.`tanggal` AS `tanggal`, `p`.`id_penjualan` AS `id_penjualan`, `p`.`total_harga` AS `total_harga`, `dp`.`id_detail` AS `id_detail`, `dp`.`jumlah` AS `jumlah`, `dp`.`subtotal` AS `subtotal`, `pr`.`id_produk` AS `id_produk`, `pr`.`nama_produk` AS `nama_produk`, `pr`.`harga` AS `harga`, `pr`.`stok` AS `stok`, `pr`.`gambar_pakaian` AS `gambar_pakaian`, `pr`.`id_kategori_pakaian` AS `id_kategori_pakaian`, `kt`.`nama_kategori_pakaian` AS `nama_kategori_pakaian` FROM ((((`detail_penjualan` `dp` join `penjualan` `p` on(`dp`.`id_penjualan` = `p`.`id_penjualan`)) join `pelanggan` `pl` on(`p`.`id_pelanggan` = `pl`.`id_pelanggan`)) join `produk` `pr` on(`dp`.`id_produk` = `pr`.`id_produk`)) join `kategori_pakaian` `kt` on(`pr`.`id_kategori_pakaian` = `kt`.`id_kategori_pakaian`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vw_all_data_produk`
--
DROP TABLE IF EXISTS `vw_all_data_produk`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_all_data_produk`  AS SELECT `p`.`id_produk` AS `id_produk`, `p`.`nama_produk` AS `nama_produk`, `p`.`harga` AS `harga`, `p`.`stok` AS `stok`, `p`.`gambar_pakaian` AS `gambar_pakaian`, `p`.`id_kategori_pakaian` AS `id_kategori_pakaian`, `kt`.`nama_kategori_pakaian` AS `nama_kategori_pakaian` FROM (`produk` `p` join `kategori_pakaian` `kt` on(`p`.`id_kategori_pakaian` = `kt`.`id_kategori_pakaian`)) ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vw_produk_stok_banyak`
--
DROP TABLE IF EXISTS `vw_produk_stok_banyak`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_produk_stok_banyak`  AS SELECT `p`.`id_produk` AS `id_produk`, `p`.`nama_produk` AS `nama_produk`, `p`.`harga` AS `harga`, `p`.`stok` AS `stok`, `p`.`gambar_pakaian` AS `gambar_pakaian`, `p`.`id_kategori_pakaian` AS `id_kategori_pakaian`, `kt`.`nama_kategori_pakaian` AS `nama_kategori_pakaian` FROM (`produk` `p` join `kategori_pakaian` `kt` on(`p`.`id_kategori_pakaian` = `kt`.`id_kategori_pakaian`)) WHERE `p`.`stok` >= 50 ORDER BY `p`.`stok` DESC ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vw_produk_terjual_dan_jumlah`
--
DROP TABLE IF EXISTS `vw_produk_terjual_dan_jumlah`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_produk_terjual_dan_jumlah`  AS SELECT `p`.`id_produk` AS `id_produk`, `p`.`nama_produk` AS `nama_produk`, `p`.`gambar_pakaian` AS `gambar_pakaian`, sum(`dp`.`jumlah`) AS `total_terjual` FROM (`detail_penjualan` `dp` join `produk` `p` on(`dp`.`id_produk` = `p`.`id_produk`)) GROUP BY `p`.`id_produk` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `vw_produk_terlaris`
--
DROP TABLE IF EXISTS `vw_produk_terlaris`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_produk_terlaris`  AS SELECT `p`.`id_produk` AS `id_produk`, `p`.`nama_produk` AS `nama_produk`, `p`.`harga` AS `harga`, `p`.`gambar_pakaian` AS `gambar_pakaian`, sum(`dp`.`jumlah`) AS `total_terjual` FROM (`detail_penjualan` `dp` join `produk` `p` on(`dp`.`id_produk` = `p`.`id_produk`)) GROUP BY `dp`.`jumlah` ORDER BY sum(`dp`.`jumlah`) DESC ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_penjualan` (`id_penjualan`),
  ADD KEY `id_produk` (`id_produk`);

--
-- Indeks untuk tabel `kategori_pakaian`
--
ALTER TABLE `kategori_pakaian`
  ADD PRIMARY KEY (`id_kategori_pakaian`);

--
-- Indeks untuk tabel `log_hapus_produk`
--
ALTER TABLE `log_hapus_produk`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `log_update_harga`
--
ALTER TABLE `log_update_harga`
  ADD PRIMARY KEY (`id_log`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`),
  ADD KEY `id_kategori_pakaian` (`id_kategori_pakaian`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kategori_pakaian`
--
ALTER TABLE `kategori_pakaian`
  MODIFY `id_kategori_pakaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `log_hapus_produk`
--
ALTER TABLE `log_hapus_produk`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `log_update_harga`
--
ALTER TABLE `log_update_harga`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_penjualan`
--
ALTER TABLE `detail_penjualan`
  ADD CONSTRAINT `detail_penjualan_ibfk_1` FOREIGN KEY (`id_penjualan`) REFERENCES `penjualan` (`id_penjualan`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_penjualan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD CONSTRAINT `penjualan_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`id_kategori_pakaian`) REFERENCES `kategori_pakaian` (`id_kategori_pakaian`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
