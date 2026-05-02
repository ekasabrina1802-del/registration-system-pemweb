-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Bulan Mei 2026 pada 15.19
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
-- Database: `db_pendaftaran_mahasiswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftaran`
--

CREATE TABLE `pendaftaran` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nik` char(16) NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(30) DEFAULT NULL,
  `kota_asal` varchar(50) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `asal_sekolah` varchar(100) DEFAULT NULL,
  `tahun_lulus` year(4) DEFAULT NULL,
  `program_studi` varchar(100) NOT NULL,
  `jalur_masuk` varchar(100) NOT NULL,
  `nomor_pendaftaran` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftaran`
--

INSERT INTO `pendaftaran` (`id`, `nama_lengkap`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `kota_asal`, `alamat`, `no_hp`, `email`, `asal_sekolah`, `tahun_lulus`, `program_studi`, `jalur_masuk`, `nomor_pendaftaran`, `created_at`) VALUES
(1, 'Budi Santoso', '3578123412341234', 'Surabaya', '2005-08-12', 'Laki-laki', 'Islam', 'Kota Surabaya', 'Jl. Mawar No 10', '08123456789', 'budi@gmail.com', 'SMA Negeri 1 Surabaya', '2024', 'Teknik Informatika', 'Jalur Mandiri', 'UN-2025-ABC12345', '2026-05-01 13:02:09'),
(2, 'Eka Sabrina', '3522105802007000', 'Bojonegoro', '2007-02-18', 'Perempuan', 'Islam', 'Kab. Bojonegoro', 'Pondok Indah Karah Blok B No.4', '081528969002', 'ekasabrina1802@gmail.com', 'SMKN 1 Baureno', '2024', 'Pendidikan Teknologi Informasi', 'Seleksi Tulis (SBMPTN)', 'UN-2026-70C25597', '2026-05-01 13:07:15'),
(5, 'Eom Sean', '3522675534908761', 'Surabaya', '2009-01-13', 'Laki-laki', 'Kristen Protestan', 'Kota Surabaya', 'Jl. Jambangan Baru No. 2/3, Kelurahan Karah, Kecamatan Jambangan, Kota Surabaya, Jawa Timur 60232', '081528962000', 'eomsean@gmail.com', 'SMK Negeri 3 Surabaya', '2025', 'Teknik Informatika', 'Beasiswa Unggulan', 'UN-2026-F2EB18C2', '2026-05-01 13:11:51'),
(6, 'Anh Keonho', '3544287008643287', 'Jakarta', '2009-02-14', 'Laki-laki', 'Kristen Protestan', 'Kota Malang', 'Batu kota, malang', '0899999999', 'anhkeonho@gmail.com', 'SMAN 1 Malang', '2024', 'Sistem Informasi', 'Seleksi Prestasi (SNMPTN)', 'UN-2026-9DCB5CD1', '2026-05-01 13:15:37'),
(7, 'Zhao Yufan', '3544287008643223', 'Jakarta', '2005-10-26', 'Laki-laki', 'Katolik', 'Kota Surabaya', 'Royal Regency, surabaya no4', '08999999111', 'zhaoyufan@gmail.com', 'SMAN 1 Surabaya', '2022', 'Teknik Komputer', 'Jalur Mandiri', 'UN-2026-FBD98821', '2026-05-01 13:18:14');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pendaftaran`
--
ALTER TABLE `pendaftaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
