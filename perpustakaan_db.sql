-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Bulan Mei 2026 pada 13.53
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
-- Database: `perpustakaan_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggotas`
--

CREATE TABLE `anggotas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nis` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kelas` varchar(20) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `anggotas`
--

INSERT INTO `anggotas` (`id`, `user_id`, `nis`, `nama`, `kelas`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(1, 2, '123456', 'User Anggota', 'X RPL 1', NULL, NULL, '2026-05-10 17:50:40', '2026-05-10 17:50:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `judul` varchar(200) NOT NULL,
  `pengarang` varchar(100) NOT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` smallint(6) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `stok` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `cover` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `isbn`, `stok`, `cover`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', 'Bloomsbury', 1997, '9780747532699', 10, 'https://covers.openlibrary.org/b/isbn/9780747532699-L.jpg', 'Novel fantasi tentang perjalanan Harry Potter di Hogwarts.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(2, 'The Hobbit', 'J.R.R. Tolkien', 'Allen & Unwin', 1937, '9780547928227', 8, 'https://covers.openlibrary.org/b/isbn/9780547928227-L.jpg', 'Petualangan Bilbo Baggins di dunia fantasi Middle-earth.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(3, 'Dune', 'Frank Herbert', 'Chilton Books', 1965, '9780441172719', 6, 'https://covers.openlibrary.org/b/isbn/9780441172719-L.jpg', 'Kisah fiksi ilmiah tentang perebutan kekuasaan di planet Arrakis.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(4, 'Dracula', 'Bram Stoker', 'Archibald Constable', 1897, '9780486411095', 5, 'https://covers.openlibrary.org/b/isbn/9780486411095-L.jpg', 'Novel horor klasik tentang vampir Count Dracula.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(5, 'Sherlock Holmes: A Study in Scarlet', 'Arthur Conan Doyle', 'Ward Lock & Co', 1887, '9780486474915', 7, 'https://covers.openlibrary.org/b/isbn/9780486474915-L.jpg', 'Kasus pertama detektif Sherlock Holmes.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(6, 'The Girl with the Dragon Tattoo', 'Stieg Larsson', 'Norstedts Förlag', 2005, '9780307454546', 9, 'https://covers.openlibrary.org/b/isbn/9780307454546-L.jpg', 'Thriller misteri investigasi penuh intrik.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(7, 'Murder on the Orient Express', 'Agatha Christie', 'Collins Crime Club', 1934, '9780062693662', 5, 'https://covers.openlibrary.org/b/isbn/9780062693662-L.jpg', 'Detektif Hercule Poirot memecahkan kasus pembunuhan di kereta.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(8, 'Treasure Island', 'Robert Louis Stevenson', 'Cassell & Company', 1883, '9780486404257', 4, 'https://covers.openlibrary.org/b/isbn/9780486404257-L.jpg', 'Petualangan mencari harta karun bajak laut.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(9, 'Pride and Prejudice', 'Jane Austen', 'T. Egerton', 1813, '9780141439518', 6, 'https://covers.openlibrary.org/b/isbn/9780141439518-L.jpg', 'Kisah romantis Elizabeth Bennet dan Mr. Darcy.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(10, 'Romeo and Juliet', 'William Shakespeare', 'Simon & Schuster', 1597, '9780743477116', 5, 'https://covers.openlibrary.org/b/isbn/9780743477116-L.jpg', 'Drama tragedi cinta klasik Shakespeare.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(11, 'Diary of a Wimpy Kid', 'Jeff Kinney', 'Amulet Books', 2007, '9780810993136', 8, 'https://covers.openlibrary.org/b/isbn/9780810993136-L.jpg', 'Komedi kehidupan sekolah Greg Heffley.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(12, 'The Hunger Games', 'Suzanne Collins', 'Scholastic Press', 2008, '9780439023481', 12, 'https://covers.openlibrary.org/b/isbn/9780439023481-L.jpg', 'Aksi survival dalam kompetisi mematikan.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(13, 'The Book Thief', 'Markus Zusak', 'Picador', 2005, '9780375842207', 5, 'https://covers.openlibrary.org/b/isbn/9780375842207-L.jpg', 'Fiksi sejarah berlatar Perang Dunia II.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(14, 'The Fault in Our Stars', 'John Green', 'Dutton Books', 2012, '9780525478812', 9, 'https://covers.openlibrary.org/b/isbn/9780525478812-L.jpg', 'Novel remaja tentang cinta dan perjuangan hidup.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(15, 'Charlotte\'s Web', 'E.B. White', 'Harper & Brothers', 1952, '9780061124952', 10, 'https://covers.openlibrary.org/b/isbn/9780061124952-L.jpg', 'Cerita anak tentang persahabatan babi dan laba-laba.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(16, 'Grimm\'s Fairy Tales', 'Brothers Grimm', 'Penguin Classics', 1812, '9780140449426', 5, 'https://covers.openlibrary.org/b/isbn/9780140449426-L.jpg', 'Kumpulan dongeng klasik penuh pesan moral.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(17, 'The Shining', 'Stephen King', 'Doubleday', 1977, '9780307743657', 7, 'https://covers.openlibrary.org/b/isbn/9780307743657-L.jpg', 'Novel supernatural horor psikologis.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(18, '1984', 'George Orwell', 'Secker & Warburg', 1949, '9780451524935', 11, 'https://covers.openlibrary.org/b/isbn/9780451524935-L.jpg', 'Distopia tentang pemerintahan totaliter.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(19, 'Neuromancer', 'William Gibson', 'Ace Books', 1984, '9780441569595', 6, 'https://covers.openlibrary.org/b/isbn/9780441569595-L.jpg', 'Novel cyberpunk klasik tentang hacker masa depan.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(20, 'The Difference Engine', 'William Gibson & Bruce Sterling', 'Bantam Spectra', 1990, '9780553380965', 4, 'https://covers.openlibrary.org/b/isbn/9780553380965-L.jpg', 'Novel steampunk dengan teknologi mesin uap.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(21, 'Norwegian Wood', 'Haruki Murakami', 'Kodansha', 1987, '9780375704024', 5, 'https://covers.openlibrary.org/b/isbn/9780375704024-L.jpg', 'Slice of life tentang cinta dan kehilangan.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(22, 'Crime and Punishment', 'Fyodor Dostoevsky', 'The Russian Messenger', 1866, '9780140449136', 6, 'https://covers.openlibrary.org/b/isbn/9780140449136-L.jpg', 'Novel psikologis tentang rasa bersalah dan moralitas.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(23, 'The Godfather', 'Mario Puzo', 'G.P. Putnam\'s Sons', 1969, '9780451167712', 5, 'https://covers.openlibrary.org/b/isbn/9780451167712-L.jpg', 'Kisah kriminal keluarga mafia Corleone.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(24, 'All Quiet on the Western Front', 'Erich Maria Remarque', 'Little, Brown and Company', 1929, '9780449213940', 5, 'https://covers.openlibrary.org/b/isbn/9780449213940-L.jpg', 'Novel perang tentang penderitaan tentara muda.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(25, 'True Grit', 'Charles Portis', 'Simon & Schuster', 1968, '9781590204597', 3, 'https://covers.openlibrary.org/b/isbn/9781590204597-L.jpg', 'Novel western klasik bertema koboi.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(26, 'Animal Farm', 'George Orwell', 'Secker & Warburg', 1945, '9780451526342', 10, 'https://covers.openlibrary.org/b/isbn/9780451526342-L.jpg', 'Satire politik melalui kisah hewan peternakan.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(27, 'Hamlet', 'William Shakespeare', 'Nicolas Ling', 1603, '9780743477123', 5, 'https://covers.openlibrary.org/b/isbn/9780743477123-L.jpg', 'Tragedi klasik tentang balas dendam pangeran Denmark.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(28, 'Maus', 'Art Spiegelman', 'Pantheon Books', 1986, '9780679406419', 4, 'https://covers.openlibrary.org/b/isbn/9780679406419-L.jpg', 'Novel grafis tentang Holocaust.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(29, 'Sword Art Online', 'Reki Kawahara', 'ASCII Media Works', 2009, '9780316371247', 7, 'https://covers.openlibrary.org/b/isbn/9780316371247-L.jpg', 'Light novel tentang dunia game virtual.', '2026-05-11 04:47:55', '2026-05-11 04:47:55'),
(30, 'Fifty Shades of Grey', 'E.L. James', 'Vintage Books', 2011, '9780345803481', 8, 'https://covers.openlibrary.org/b/isbn/9780345803481-L.jpg', 'Novel populer hasil adaptasi fanfiction.', '2026-05-11 04:47:55', '2026-05-11 04:47:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku_kategori`
--

CREATE TABLE `buku_kategori` (
  `buku_id` bigint(20) UNSIGNED NOT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategoris`
--

CREATE TABLE `kategoris` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategoris`
--

INSERT INTO `kategoris` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Fiksi', 'Karya sastra yang bersifat imajinatif dan tidak berdasarkan fakta nyata', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(2, 'Fantasi', 'Genre yang menampilkan elemen magis dan dunia imajinatif', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(3, 'Fiksi Ilmiah (Sci-Fi)', 'Genre yang mengeksplorasi konsep ilmu pengetahuan dan teknologi masa depan', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(4, 'Horor', 'Genre yang dirancang untuk menakutkan dan mencekam pembaca', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(5, 'Misteri', 'Genre yang berpusat pada teka-teki atau kejadian misterius', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(6, 'Thriller', 'Genre yang penuh ketegangan dan suspense tinggi', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(7, 'Detektif', 'Genre yang berfokus pada investigasi dan pemecahan kasus', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(8, 'Petualangan', 'Genre yang menampilkan perjalanan dan petualangan seru', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(9, 'Romantis', 'Genre yang berfokus pada kisah cinta dan hubungan asmara', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(10, 'Drama', 'Genre yang mengeksplorasi konflik emosional dan kehidupan manusia', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(11, 'Komedi', 'Genre yang bertujuan menghibur dan membuat pembaca tertawa', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(12, 'Action / Aksi', 'Genre yang penuh dengan adegan aksi dan pertarungan', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(13, 'Historical Fiction (Fiksi Sejarah)', 'Fiksi yang berlatar belakang peristiwa atau era sejarah nyata', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(14, 'Young Adult (Remaja)', 'Genre yang ditujukan untuk pembaca remaja hingga dewasa muda', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(15, 'Children / Anak', 'Genre yang ditujukan untuk pembaca anak-anak', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(16, 'Dongeng', 'Cerita rakyat atau fabel dengan pesan moral', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(17, 'Supernatural', 'Genre yang menampilkan elemen di luar hukum alam', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(18, 'Distopia', 'Genre yang menggambarkan masyarakat masa depan yang suram dan represif', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(19, 'Cyberpunk', 'Genre fiksi ilmiah bertema teknologi tinggi dan budaya bawah tanah', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(20, 'Steampunk', 'Genre yang memadukan teknologi uap dengan estetika Victorian', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(21, 'Slice of Life', 'Genre yang menggambarkan kehidupan sehari-hari secara realistis', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(22, 'Psikologis', 'Genre yang mengeksplorasi kondisi dan proses psikologi karakter', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(23, 'Kriminal', 'Genre yang berfokus pada dunia kejahatan dan kriminalitas', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(24, 'Perang', 'Genre yang menggambarkan suasana dan dampak perang', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(25, 'Barat / Western', 'Genre berlatar era koboi dan perbatasan Amerika', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(26, 'Satire', 'Genre yang menggunakan ironi untuk mengkritik masyarakat', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(27, 'Tragedi', 'Genre yang menampilkan kisah berakhir menyedihkan atau penuh penderitaan', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(28, 'Novel Grafis', 'Novel berbentuk komik dengan narasi panjang', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(29, 'Light Novel', 'Novel ringan bergaya anime/manga asal Jepang', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(30, 'Fanfiction', 'Karya fiksi yang dibuat berdasarkan karya populer yang sudah ada', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(31, 'Biografi', 'Kisah hidup seseorang yang ditulis oleh orang lain', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(32, 'Autobiografi', 'Kisah hidup seseorang yang ditulis oleh dirinya sendiri', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(33, 'Memoar', 'Catatan pengalaman pribadi berdasarkan ingatan penulis', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(34, 'Sejarah', 'Karya yang membahas peristiwa dan fakta sejarah', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(35, 'Pendidikan', 'Buku yang berkaitan dengan ilmu dan proses pendidikan', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(36, 'Agama', 'Buku yang membahas ajaran dan spiritualitas keagamaan', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(37, 'Filsafat', 'Buku yang mengkaji pemikiran dan kebijaksanaan hidup', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(38, 'Psikologi', 'Buku yang membahas perilaku dan proses mental manusia', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(39, 'Motivasi', 'Buku yang memberikan semangat dan dorongan untuk berkembang', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(40, 'Pengembangan Diri', 'Buku yang membantu pembaca meningkatkan kualitas diri', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(41, 'Bisnis', 'Buku yang membahas strategi dan dunia bisnis', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(42, 'Ekonomi', 'Buku yang mengkaji sistem dan teori ekonomi', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(43, 'Politik', 'Buku yang membahas sistem dan dinamika politik', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(44, 'Hukum', 'Buku yang mengkaji peraturan dan sistem hukum', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(45, 'Sains', 'Buku yang membahas ilmu pengetahuan alam', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(46, 'Teknologi', 'Buku yang membahas perkembangan dan inovasi teknologi', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(47, 'Kesehatan', 'Buku yang memberikan informasi seputar kesehatan', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(48, 'Kedokteran', 'Buku yang membahas ilmu dan praktik kedokteran', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(49, 'Kuliner', 'Buku yang membahas masakan, resep, dan budaya kuliner', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(50, 'Traveling', 'Buku panduan atau kisah perjalanan wisata', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(51, 'Seni & Budaya', 'Buku yang mengkaji karya seni dan nilai budaya', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(52, 'Musik', 'Buku yang membahas teori, sejarah, dan dunia musik', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(53, 'Fotografi', 'Buku yang membahas teknik dan seni fotografi', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(54, 'Parenting', 'Buku panduan pengasuhan dan pendidikan anak', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(55, 'Bahasa', 'Buku yang membahas linguistik dan pembelajaran bahasa', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(56, 'Ensiklopedia', 'Kumpulan pengetahuan umum yang disusun secara sistematis', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(57, 'Jurnal Akademik', 'Publikasi ilmiah berisi hasil penelitian dan kajian akademis', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(58, 'Penelitian', 'Buku yang memaparkan hasil dan metodologi penelitian', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(59, 'Komputer & Pemrograman', 'Buku yang membahas ilmu komputer dan coding', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(60, 'Puisi', 'Karya sastra dalam bentuk bait dan rima', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(61, 'Cerpen', 'Karya fiksi pendek yang berfokus pada satu konflik utama', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(62, 'Novel', 'Karya fiksi panjang dengan plot dan karakter yang kompleks', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(63, 'Naskah Drama', 'Teks yang ditulis untuk dipentaskan di atas panggung', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(64, 'Esai', 'Karangan prosa yang mengeksplorasi sudut pandang penulis', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(65, 'Antologi', 'Kumpulan karya sastra dari satu atau beberapa penulis', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(66, 'Manga', 'Komik bergaya Jepang dengan alur cerita beragam', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(67, 'Komik Superhero', 'Komik yang menampilkan karakter-karakter superhero', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(68, 'Webtoon', 'Komik digital yang dibaca secara vertikal', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(69, 'Graphic Novel', 'Novel dalam format visual komik dengan cerita mendalam', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(70, 'Anak-anak', 'Buku yang dirancang khusus untuk pembaca usia anak-anak', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(71, 'Remaja', 'Buku yang ditujukan untuk pembaca usia remaja', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(72, 'Dewasa', 'Buku yang ditujukan untuk pembaca dewasa', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(73, 'Semua Umur', 'Buku yang dapat dinikmati oleh semua kalangan usia', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(74, 'LitRPG', 'Genre fiksi yang menggabungkan elemen permainan RPG ke dalam cerita', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(75, 'Web Novel', 'Novel yang dipublikasikan secara online secara berseri', '2026-05-11 04:20:58', '2026-05-11 04:20:58'),
(76, 'Interactive Fiction', 'Fiksi interaktif di mana pembaca menentukan alur cerita', '2026-05-11 04:20:58', '2026-05-11 04:20:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_27_071219_create_kategoris_table', 1),
(5, '2026_04_27_071250_create_buku_table', 1),
(6, '2026_04_27_071316_create_peminjamans_table', 1),
(7, '2026_04_28_012518_create_anggotas_table', 1),
(8, '2026_04_28_012630_create_buku_kategori_table', 1),
(9, '2026_04_28_012813_add_role_to_users_table', 1),
(10, '2026_04_28_120000_add_approval_workflow_to_peminjamans_table', 2),
(11, '2026_05_11_033500_fix_peminjamans_anggota_foreign_key', 3),
(12, '2026_05_11_040000_create_struks_table', 4),
(13, '2026_05_11_045500_add_missing_auth_columns_to_users_table', 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjamans`
--

CREATE TABLE `peminjamans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `anggota_id` bigint(20) UNSIGNED NOT NULL,
  `buku_id` bigint(20) UNSIGNED NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_kembali_rencana` date NOT NULL,
  `tgl_kembali_aktual` date DEFAULT NULL,
  `status` enum('dipinjam','dikembalikan','terlambat') NOT NULL DEFAULT 'dipinjam',
  `approval_status` varchar(255) NOT NULL DEFAULT 'approved',
  `approval_note` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `return_status` varchar(255) NOT NULL DEFAULT 'none',
  `return_note` text DEFAULT NULL,
  `return_requested_at` timestamp NULL DEFAULT NULL,
  `return_processed_at` timestamp NULL DEFAULT NULL,
  `denda` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('lOIoWAt7D6JmnvSOR2FzJE9gUt6dluwJZgN8Wo6j', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidlFYcDV0SHRrTnBnVjJJVFNSRlVTRFh4dzlGcVp5NVVsWlF0VVV5TCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL3Byb2ZpbGUiO3M6NToicm91dGUiO3M6MTI6InVzZXIucHJvZmlsZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1778482129);

-- --------------------------------------------------------

--
-- Struktur dari tabel `struks`
--

CREATE TABLE `struks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `anggota_id` bigint(20) UNSIGNED NOT NULL,
  `peminjaman_id` bigint(20) UNSIGNED NOT NULL,
  `jenis` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `nominal` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','anggota') NOT NULL DEFAULT 'anggota',
  `mobile` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role_id` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `role`, `mobile`, `password`, `remember_token`, `role_id`, `created_at`, `updated_at`) VALUES
(2, 'User Anggota', 'user@gmail.com', NULL, 'anggota', NULL, '$2y$12$zmZro/g7tAGULFyM8LR3HeEDbgaM0.KEqgQ2ZBB4othTVAWXhrH3e', NULL, 2, '2026-04-27 18:59:44', '2026-05-10 17:50:40'),
(6, 'Admin', 'admin@gmail.com', NULL, 'admin', NULL, '$2y$12$LGhzXJgFcOW3cwC0IBxJR.8c.E9.ywiPmM0MEA9ESDoMNHhpGuAS6', NULL, 1, '2026-04-27 19:22:53', '2026-05-10 17:51:33');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggotas`
--
ALTER TABLE `anggotas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `anggotas_nis_unique` (`nis`),
  ADD KEY `anggotas_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `buku_isbn_unique` (`isbn`);

--
-- Indeks untuk tabel `buku_kategori`
--
ALTER TABLE `buku_kategori`
  ADD PRIMARY KEY (`buku_id`,`kategori_id`),
  ADD KEY `buku_kategori_kategori_id_foreign` (`kategori_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategoris`
--
ALTER TABLE `kategoris`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kategoris_nama_kategori_unique` (`nama_kategori`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjamans_buku_id_foreign` (`buku_id`),
  ADD KEY `peminjamans_anggota_id_foreign` (`anggota_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `struks`
--
ALTER TABLE `struks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `struks_peminjaman_id_jenis_unique` (`peminjaman_id`,`jenis`),
  ADD UNIQUE KEY `struks_kode_unique` (`kode`),
  ADD KEY `struks_anggota_id_foreign` (`anggota_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggotas`
--
ALTER TABLE `anggotas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategoris`
--
ALTER TABLE `kategoris`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `peminjamans`
--
ALTER TABLE `peminjamans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `struks`
--
ALTER TABLE `struks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `anggotas`
--
ALTER TABLE `anggotas`
  ADD CONSTRAINT `anggotas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `buku_kategori`
--
ALTER TABLE `buku_kategori`
  ADD CONSTRAINT `buku_kategori_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `buku_kategori_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategoris` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD CONSTRAINT `peminjamans_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggotas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjamans_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `struks`
--
ALTER TABLE `struks`
  ADD CONSTRAINT `struks_anggota_id_foreign` FOREIGN KEY (`anggota_id`) REFERENCES `anggotas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `struks_peminjaman_id_foreign` FOREIGN KEY (`peminjaman_id`) REFERENCES `peminjamans` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
