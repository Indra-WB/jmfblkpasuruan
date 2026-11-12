-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: jmf_blk_pasuruan
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `desa`
--

DROP TABLE IF EXISTS `desa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `desa` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kecamatan_id` int(11) unsigned NOT NULL,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `kecamatan_id` (`kecamatan_id`),
  CONSTRAINT `desa_kecamatan_id_foreign` FOREIGN KEY (`kecamatan_id`) REFERENCES `kecamatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=359 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `desa`
--

LOCK TABLES `desa` WRITE;
/*!40000 ALTER TABLE `desa` DISABLE KEYS */;
INSERT INTO `desa` VALUES (1,1,'Bendo Mungal'),(2,1,'Dermo'),(3,1,'Gempeng'),(4,1,'Kalianyar'),(5,1,'Kalirejo'),(6,1,'Kauman'),(7,1,'Kersikan'),(8,1,'Kidul Dalem'),(9,1,'Kolursari'),(10,1,'Latek'),(11,1,'Manaruwi'),(12,1,'Masangan'),(13,1,'Pogar'),(14,1,'Raci'),(15,1,'Tambakan'),(16,2,'Baujeng'),(17,2,'Beji'),(18,2,'Cangkringmalang'),(19,2,'Gajahbendo'),(20,2,'Glanggang'),(21,2,'Gununggangsir'),(22,2,'Gunungsari'),(23,2,'Kedungboto'),(24,2,'Kedungringin'),(25,2,'Kenep'),(26,2,'Ngembe'),(27,2,'Pagak'),(28,2,'Sidowayah'),(29,2,'Wonokoyo'),(30,3,'BuluSari'),(31,3,'Carat'),(32,3,'Gempol'),(33,3,'Jerukpurut'),(34,3,'Karangrejo'),(35,3,'Kejapanan'),(36,3,'Kepulungan'),(37,3,'Legok'),(38,3,'Ngerong'),(39,3,'Randupitu'),(40,3,'Sumbersuko'),(41,3,'Watukosek'),(42,3,'Winong'),(43,3,'Wonosunyo'),(44,3,'Wonoasri'),(45,4,'Bajangan'),(46,4,'Bayeman'),(47,4,'Brambang'),(48,4,'Gayasari'),(49,4,'Gondangrejo'),(50,4,'Gondangwetan'),(51,4,'Kalirejo'),(52,4,'Karangsentul'),(53,4,'Kersikan'),(54,4,'Lajuk'),(55,4,'Pateguhan'),(56,4,'Pekangkungan'),(57,4,'Ranggeh'),(58,4,'Sekarputih'),(59,4,'Tenggilisrejo'),(60,4,'Wonojati'),(61,4,'Wonosari'),(62,5,'Cukurgondang'),(63,5,'Gratitunon'),(64,5,'Kalipang'),(65,5,'Kambinganrejo'),(66,5,'Karangkliwon'),(67,5,'Karanglo'),(68,5,'Kebonrejo'),(69,5,'Kedawungkulon'),(70,5,'Kedawungwetan'),(71,5,'Plososari'),(72,5,'Ranuklindungan'),(73,5,'Rebalas'),(74,5,'Sumberagung'),(75,5,'Sumberdawesari'),(76,5,'Trewung'),(77,6,'Ambal Ambil'),(78,6,'Benerwojo'),(79,6,'Cobanjoyo'),(80,6,'Kalisat'),(81,6,'Kedemungan'),(82,6,'Kejayan'),(83,6,'Kepuh'),(84,6,'Ketangirejo'),(85,6,'Klangrong'),(86,6,'Klinter'),(87,6,'Kurung'),(88,6,'Linggo'),(89,6,'Lorokan'),(90,6,'Luwuk'),(91,6,'Oro-oro Puleh'),(92,6,'Pacarkeling'),(93,6,'Patebon'),(94,6,'Randugong'),(95,6,'Sladi'),(96,6,'Sumberbanteng'),(97,6,'Sumbersuko'),(98,6,'Tanggulangin'),(99,6,'Tondosuru'),(100,6,'Wrati'),(101,7,'Asemkandang'),(102,7,'Bendungan'),(103,7,'Curahdukuh'),(104,7,'Dhompo'),(105,7,'Gambirkuning'),(106,7,'Gerongan'),(107,7,'Jeruk'),(108,7,'Kalirejo'),(109,7,'Karanganyar'),(110,7,'Kebotohan'),(111,7,'Klampisrejo'),(112,7,'Kraton'),(113,7,'Mulyorejo'),(114,7,'Ngabar'),(115,7,'Ngempit'),(116,7,'Plinggisan'),(117,7,'Pukul'),(118,7,'Pulokerto'),(119,7,'Rejosari'),(120,7,'Semare'),(121,7,'Sidogiri'),(122,7,'Slambrit'),(123,7,'Tambakrejo'),(124,7,'Tambaksari'),(125,7,'Ambyarz'),(126,8,'Alastlogo'),(127,8,'Balunganyar'),(128,8,'Branang'),(129,8,'Gejugjati'),(130,8,'Jatirejo'),(131,8,'Pasinan'),(132,8,'Rowogempol'),(133,8,'Semendung'),(134,8,'Tambaklekok'),(135,8,'Tampung'),(136,8,'Wates'),(137,9,'Banjarsari'),(138,9,'Bulukandang'),(139,9,'Cukurguling'),(140,9,'Karangarson'),(141,9,'Karangjati'),(142,9,'Kronto'),(143,9,'Lumbang'),(144,9,'Ancaran'),(145,9,'Panditan'),(146,9,'Watulumbung'),(147,9,'Welulang'),(148,9,'Wonorejo'),(149,10,'Dandang Gendis'),(150,10,'Kapasan'),(151,10,'Kedawang'),(152,10,'Mlaten'),(153,10,'Nguling'),(154,10,'Penunggul'),(155,10,'Randuati'),(156,10,'Sanganom'),(157,10,'Sebalong'),(158,10,'Sedarum'),(159,10,'Sumberejo'),(160,10,'Sudimulyo'),(161,10,'Watuprapat'),(162,10,'Wotgalih'),(163,11,'Banjarsari'),(164,11,'Banjarkejen'),(165,11,'Durensewu'),(166,11,'Jogosari'),(167,11,'Karangjati'),(168,11,'Kebonwaris'),(169,11,'Kemirisewu'),(170,11,'Kutorejo'),(171,11,'Nogosari'),(172,11,'Pandaan'),(173,11,'Petungasri'),(174,11,'Plintahan'),(175,11,'Sebani'),(176,11,'Sumbergedang'),(177,11,'Sumberrejo'),(178,11,'Tawangrejo'),(179,11,'Tunggulwulung'),(180,11,'Wedoro'),(181,12,'Ampelsari'),(182,12,'Cengkrong'),(183,12,'Galih'),(184,12,'Jogorepuh'),(185,12,'Karanglo'),(186,12,'Klakah'),(187,12,'Lemahbang'),(188,12,'Mangguan'),(189,12,'Ngantungan'),(190,12,'Pasrepan'),(191,12,'Petung'),(192,12,'Pohgedang'),(193,12,'Pohgading'),(194,12,'Rejosalam'),(195,12,'Sapulante'),(196,12,'Sibon'),(197,12,'Tambakrejo'),(198,13,'Logowok'),(199,13,'Parasrejo'),(200,13,'Pleret'),(201,13,'Sukorejo'),(202,13,'Sungi Wetan'),(203,13,'Sungi Kulon'),(204,13,'Susukanrejo'),(205,13,'Tidu'),(206,13,'Warungdowo'),(207,14,'Bulukandang'),(208,14,'Candiwates'),(209,14,'Dayurejo'),(210,14,'Gambiran'),(211,14,'Jatiarjo'),(212,14,'Ketangirejo'),(213,14,'Ledug'),(214,14,'Lumbangrejo'),(215,14,'Pecalukan'),(216,14,'Prigen'),(217,14,'Sekarjoho'),(218,14,'Sukolelo'),(219,14,'Sukoreno'),(220,14,'Watuagung'),(221,15,'Capang'),(222,15,'Cowek'),(223,15,'Dawuhan Sengon'),(224,15,'Gajahrejo'),(225,15,'Gerbo'),(226,15,'Jatisari'),(227,15,'Lebakrejo'),(228,15,'Parerejo'),(229,15,'Pucangsari'),(230,15,'Purwodadi'),(231,15,'Semut'),(232,15,'Sentul'),(233,15,'Tambaksari'),(234,16,'Bakalan'),(235,16,'Cendono'),(236,16,'Karangrejo'),(237,16,'Kayoman'),(238,16,'Kertosari'),(239,16,'Martopuro'),(240,16,'Pager'),(241,16,'Pucangsari'),(242,16,'Purwosari'),(243,16,'Sekarmojo'),(244,16,'Sengonagung'),(245,16,'Sukodermo'),(246,16,'Sumbersuko'),(247,16,'Tejowangi'),(248,17,'Janjangwulung'),(249,17,'Jimbaran'),(250,17,'Keduwung'),(251,17,'Kemiri'),(252,17,'Palangsari'),(253,17,'Puspo'),(254,17,'Puspobudo'),(255,18,'Arjosari'),(256,18,'Jarangan'),(257,18,'Karangpandan'),(258,18,'Kawisrejo'),(259,18,'Kedungbako'),(260,18,'Kemantrenrejo'),(261,18,'Ketegan'),(262,18,'Manikrejo'),(263,18,'Pandanrejo'),(264,18,'Patuguran'),(265,18,'Rejoso Kidul'),(266,18,'Rejoso Lor'),(267,18,'Sadengrejo'),(268,18,'Sambirejo'),(269,18,'Segoropuro'),(270,18,'Toyaning'),(271,19,'Genengwaru'),(272,19,'Kalisat'),(273,19,'Kanigoro'),(274,19,'Kedungbanteng'),(275,19,'Krengih'),(276,19,'Mojoparon'),(277,19,'Oro-oro Bulu'),(278,19,'Oro-oro Ombo Kulon'),(279,19,'Oro-oro Ombo Wetan'),(280,19,'Pajaran'),(281,19,'Pandean'),(282,19,'Pejangkungan'),(283,19,'Pekoren'),(284,19,'Rembang'),(285,19,'Sumberglagah'),(286,19,'Tampico'),(287,20,'Candibinangun'),(288,20,'Curahrejo'),(289,20,'Dukuhsari'),(290,20,'Glagahsari'),(291,20,'Gunting'),(292,20,'Kalirejo'),(293,20,'Karangsono'),(294,20,'Kenduruan'),(295,20,'Lecari'),(296,20,'Lemahbang'),(297,20,'Mojotengah'),(298,20,'Ngadimulyo'),(299,20,'Pakukerto'),(300,20,'Sebandung'),(301,20,'Sukorame'),(302,20,'Sukorejo'),(303,20,'Suwayuwo'),(304,20,'Tanjungarum'),(305,20,'Wonokerto'),(306,21,'Baledono'),(307,21,'Kandangan'),(308,21,'Mororejo'),(309,21,'Ngadiwono'),(310,21,'Podokoyo'),(311,21,'Sedaeng'),(312,21,'Tosari'),(313,21,'Wonokitri'),(314,22,'Andonosari'),(315,22,'Blarang'),(316,22,'Gendro'),(317,22,'Kaliombo'),(318,22,'Kayukebek'),(319,22,'Ngadirejo'),(320,22,'Ngembal'),(321,22,'Pungging'),(322,22,'Sumberpitu'),(323,22,'Tlogosari'),(324,22,'Tutur'),(325,22,'Wonosari'),(326,23,'Bandaran'),(327,23,'Gading'),(328,23,'Jeladri'),(329,23,'Kandung'),(330,23,'Karangtengah'),(331,23,'Kedungrejo'),(332,23,'Lebak'),(333,23,'Mendalan'),(334,23,'Menyarik'),(335,23,'Minggir'),(336,23,'Penataan'),(337,23,'Prodo'),(338,23,'Sidepan'),(339,23,'Sruwi'),(340,23,'Sumberrejo'),(341,23,'Umbulan'),(342,23,'Winongan Kidul'),(343,23,'Winongan Lor'),(344,24,'Cobanblimbing'),(345,24,'Jatigunting'),(346,24,'Karangasem'),(347,24,'Karangjatianyar'),(348,24,'Karangmenggah'),(349,24,'Karangsono'),(350,24,'Kendangdukuh'),(351,24,'Kluwut'),(352,24,'Lebaksari'),(353,24,'Pakijangan'),(354,24,'Rebono'),(355,24,'Sambisirah'),(356,24,'Tamansari'),(357,24,'Jati'),(358,24,'Wonorejo');
/*!40000 ALTER TABLE `desa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_queue`
--

DROP TABLE IF EXISTS `email_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_queue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tujuan` varchar(150) NOT NULL,
  `subjek` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `status` enum('antre','terkirim','gagal') NOT NULL DEFAULT 'antre',
  `percobaan` tinyint(2) NOT NULL DEFAULT 0,
  `error_msg` text DEFAULT NULL,
  `sent_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_queue`
--

LOCK TABLES `email_queue` WRITE;
/*!40000 ALTER TABLE `email_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_perusahaan`
--

DROP TABLE IF EXISTS `event_perusahaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_perusahaan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL,
  `perusahaan_id` int(11) unsigned NOT NULL,
  `no_booth` varchar(30) NOT NULL,
  `qr_token` varchar(64) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_token` (`qr_token`),
  UNIQUE KEY `event_id_perusahaan_id` (`event_id`,`perusahaan_id`),
  KEY `event_perusahaan_perusahaan_id_foreign` (`perusahaan_id`),
  CONSTRAINT `event_perusahaan_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `event_perusahaan_perusahaan_id_foreign` FOREIGN KEY (`perusahaan_id`) REFERENCES `perusahaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_perusahaan`
--

LOCK TABLES `event_perusahaan` WRITE;
/*!40000 ALTER TABLE `event_perusahaan` DISABLE KEYS */;
INSERT INTO `event_perusahaan` VALUES (1,1,1,'A-01','BOOTH-PIER-2026-A01','2026-11-12 21:06:30','2026-11-12 21:06:30'),(2,1,2,'A-02','BOOTH-ICBP-2026-A02','2026-11-12 21:06:30','2026-11-12 21:06:30'),(3,1,3,'A-03','BOOTH-YMPA-2026-A03','2026-11-12 21:06:30','2026-11-12 21:06:30');
/*!40000 ALTER TABLE `event_perusahaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_peserta`
--

DROP TABLE IF EXISTS `event_peserta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_peserta` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL,
  `pencaker_id` int(11) unsigned NOT NULL,
  `sesi_id` int(11) unsigned DEFAULT NULL,
  `no_registrasi` varchar(30) NOT NULL,
  `qr_token` varchar(64) NOT NULL,
  `checkin_at` datetime DEFAULT NULL,
  `checkin_by` int(11) unsigned DEFAULT NULL,
  `checkin_luar_sesi` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `qr_token` (`qr_token`),
  UNIQUE KEY `no_registrasi` (`no_registrasi`),
  UNIQUE KEY `event_id_pencaker_id` (`event_id`,`pencaker_id`),
  KEY `event_peserta_pencaker_id_foreign` (`pencaker_id`),
  KEY `sesi_id` (`sesi_id`),
  CONSTRAINT `event_peserta_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `event_peserta_pencaker_id_foreign` FOREIGN KEY (`pencaker_id`) REFERENCES `pencaker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `event_peserta_sesi_id_foreign` FOREIGN KEY (`sesi_id`) REFERENCES `event_sesi` (`id`) ON DELETE CASCADE ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_peserta`
--

LOCK TABLES `event_peserta` WRITE;
/*!40000 ALTER TABLE `event_peserta` DISABLE KEYS */;
INSERT INTO `event_peserta` VALUES (1,1,1,1,'JMF2026-0001','PESERTA-2026-0001','2026-11-12 21:06:30',2,0,'2026-11-12 21:06:30','2026-11-12 21:06:30'),(2,1,2,1,'JMF2026-0002','PESERTA-2026-0002','2026-11-12 21:06:30',2,0,'2026-11-12 21:06:30','2026-11-12 21:06:30'),(3,1,3,2,'JMF2026-0003','PESERTA-2026-0003',NULL,NULL,0,'2026-11-12 21:06:30','2026-11-12 21:06:30');
/*!40000 ALTER TABLE `event_peserta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `event_sesi`
--

DROP TABLE IF EXISTS `event_sesi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_sesi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL,
  `nama` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL,
  `jam_selesai` time NOT NULL,
  `kuota` int(11) NOT NULL DEFAULT 500,
  `urutan` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `event_sesi_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `event_sesi`
--

LOCK TABLES `event_sesi` WRITE;
/*!40000 ALTER TABLE `event_sesi` DISABLE KEYS */;
INSERT INTO `event_sesi` VALUES (1,1,'Sesi 1 (Pagi)','2026-11-23','08:00:00','10:00:00',100,1,'2026-11-12 21:06:30','2026-11-12 21:58:32'),(2,1,'Sesi 2 (Siang)','2026-11-23','10:00:00','12:00:00',100,2,'2026-11-12 21:06:30','2026-11-12 21:58:50'),(3,1,'Sesi 3 (Sore)','2026-11-23','12:00:00','15:00:00',100,3,'2026-11-12 21:06:30','2026-11-12 21:59:03');
/*!40000 ALTER TABLE `event_sesi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `tahun` year(4) NOT NULL,
  `tanggal` date NOT NULL,
  `jam_mulai` time NOT NULL DEFAULT '08:00:00',
  `jam_selesai` time NOT NULL DEFAULT '15:00:00',
  `lokasi` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `pendaftaran_dibuka` tinyint(1) NOT NULL DEFAULT 1,
  `lamaran_dibuka` tinyint(1) NOT NULL DEFAULT 1,
  `batas_seleksi` date DEFAULT NULL,
  `sesi_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `aturan_luar_sesi` enum('peringatan','tolak') NOT NULL DEFAULT 'peringatan',
  `status` enum('draf','aktif','selesai') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,'JMF UPT BLK Pasuruan 2026',2026,'2026-11-23','08:00:00','15:00:00','Aula UPT BLK Pasuruan, Jl. Pahlawan Sunaryo No.96s, Pandaan','Kegiatan Job Market Fair UPT Balai Latihan Kerja Pasuruan yang mempertemukan perusahaan industri di wilayah Kab. Pasuruan dengan pencari kerja dan alumni pelatihan di UPT BLK Pasuruan.',NULL,1,1,'2026-12-12',1,'peringatan','aktif','2026-11-12 21:06:30','2026-11-12 22:05:12');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keahlian`
--

DROP TABLE IF EXISTS `keahlian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keahlian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pencaker_id` int(11) unsigned NOT NULL,
  `nama` varchar(150) NOT NULL,
  `penerbit` varchar(150) NOT NULL,
  `tahun` year(4) NOT NULL,
  `alumni_blk` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pencaker_id` (`pencaker_id`),
  CONSTRAINT `keahlian_pencaker_id_foreign` FOREIGN KEY (`pencaker_id`) REFERENCES `pencaker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keahlian`
--

LOCK TABLES `keahlian` WRITE;
/*!40000 ALTER TABLE `keahlian` DISABLE KEYS */;
INSERT INTO `keahlian` VALUES (1,1,'Otomasi Listrik Industri (PLC)','UPT BLK Pasuruan',2025,1,'2026-11-12 21:06:30'),(2,2,'Practical Office Advance','UPT BLK Pasuruan',2024,1,'2026-11-12 21:06:30'),(3,3,'Junior Web Developer (BNSP)','UPT BLK Pasuruan',2025,1,'2026-11-12 21:06:30');
/*!40000 ALTER TABLE `keahlian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kecamatan`
--

DROP TABLE IF EXISTS `kecamatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kecamatan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kecamatan`
--

LOCK TABLES `kecamatan` WRITE;
/*!40000 ALTER TABLE `kecamatan` DISABLE KEYS */;
INSERT INTO `kecamatan` VALUES (1,'Bangil'),(2,'Beji'),(3,'Gempol'),(4,'Gondangwetan'),(5,'Grati'),(6,'Kejayan'),(7,'Kraton'),(8,'Lekok'),(9,'Lumbang'),(10,'Nguling'),(11,'Pandaan'),(12,'Pasrepan'),(13,'Pohjentrek'),(14,'Prigen'),(15,'Purwodadi'),(16,'Purwosari'),(17,'Puspo'),(18,'Rejoso'),(19,'Rembang'),(20,'Sukorejo'),(21,'Tosari'),(22,'Tutur'),(23,'Winongan'),(24,'Wonorejo');
/*!40000 ALTER TABLE `kecamatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lamaran`
--

DROP TABLE IF EXISTS `lamaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lamaran` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_peserta_id` int(11) unsigned NOT NULL,
  `lowongan_id` int(11) unsigned NOT NULL,
  `event_perusahaan_id` int(11) unsigned NOT NULL,
  `status` enum('Dikirim','LolosBerkas','PanggilTes','Diterima','Ditolak') NOT NULL DEFAULT 'Dikirim',
  `jadwal_tes` datetime DEFAULT NULL,
  `lokasi_tes` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_peserta_id_event_perusahaan_id` (`event_peserta_id`,`event_perusahaan_id`),
  KEY `lamaran_event_perusahaan_id_foreign` (`event_perusahaan_id`),
  KEY `lowongan_id` (`lowongan_id`),
  KEY `status` (`status`),
  CONSTRAINT `lamaran_event_perusahaan_id_foreign` FOREIGN KEY (`event_perusahaan_id`) REFERENCES `event_perusahaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lamaran_event_peserta_id_foreign` FOREIGN KEY (`event_peserta_id`) REFERENCES `event_peserta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `lamaran_lowongan_id_foreign` FOREIGN KEY (`lowongan_id`) REFERENCES `lowongan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lamaran`
--

LOCK TABLES `lamaran` WRITE;
/*!40000 ALTER TABLE `lamaran` DISABLE KEYS */;
INSERT INTO `lamaran` VALUES (1,1,1,1,'LolosBerkas',NULL,NULL,'Kualifikasi sertifikat BLK sesuai.','2026-11-12 21:06:30','2026-11-12 21:06:30'),(2,2,2,1,'Diterima',NULL,NULL,'Diterima sebagai Staf Administrasi mulai bulan depan.','2026-11-12 21:06:30','2026-11-12 21:06:30'),(3,1,3,2,'Dikirim',NULL,NULL,NULL,'2026-11-12 21:36:15','2026-11-12 21:36:15');
/*!40000 ALTER TABLE `lamaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lamaran_log`
--

DROP TABLE IF EXISTS `lamaran_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lamaran_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lamaran_id` int(11) unsigned NOT NULL,
  `status_lama` varchar(30) DEFAULT NULL,
  `status_baru` varchar(30) NOT NULL,
  `catatan` text DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `lamaran_id` (`lamaran_id`),
  CONSTRAINT `lamaran_log_lamaran_id_foreign` FOREIGN KEY (`lamaran_id`) REFERENCES `lamaran` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lamaran_log`
--

LOCK TABLES `lamaran_log` WRITE;
/*!40000 ALTER TABLE `lamaran_log` DISABLE KEYS */;
INSERT INTO `lamaran_log` VALUES (1,1,NULL,'Dikirim','Melamar melalui scan QR Booth',NULL,'2026-11-12 21:06:30'),(2,1,'Dikirim','LolosBerkas','Kualifikasi sertifikat BLK sesuai.',NULL,'2026-11-12 21:06:30'),(3,2,'PanggilTes','Diterima','Diterima sebagai Staf Administrasi mulai bulan depan.',NULL,'2026-11-12 21:06:30'),(4,3,NULL,'Dikirim','Melamar melalui scan QR Booth A-02',7,'2026-11-12 21:36:15');
/*!40000 ALTER TABLE `lamaran_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lowongan`
--

DROP TABLE IF EXISTS `lowongan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lowongan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_perusahaan_id` int(11) unsigned NOT NULL,
  `jabatan` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  `kualifikasi` text NOT NULL,
  `pendidikan_min` enum('SD','SMP','SMA','SMK','D1','D2','D3','D4/S1','S2') NOT NULL DEFAULT 'SMA',
  `jenis_kelamin` enum('L/P','L','P') NOT NULL DEFAULT 'L/P',
  `usia_max` int(3) DEFAULT NULL,
  `kuota` int(11) NOT NULL DEFAULT 1,
  `gaji_min` bigint(20) DEFAULT NULL,
  `gaji_max` bigint(20) DEFAULT NULL,
  `lokasi_kerja` varchar(150) NOT NULL DEFAULT 'Pasuruan',
  `ramah_disabilitas` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_perusahaan_id` (`event_perusahaan_id`),
  KEY `pendidikan_min` (`pendidikan_min`),
  CONSTRAINT `lowongan_event_perusahaan_id_foreign` FOREIGN KEY (`event_perusahaan_id`) REFERENCES `event_perusahaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lowongan`
--

LOCK TABLES `lowongan` WRITE;
/*!40000 ALTER TABLE `lowongan` DISABLE KEYS */;
INSERT INTO `lowongan` VALUES (1,1,'Teknisi Listrik & Otomasi Industri','Bertanggung jawab atas perawatan instalasi kelistrikan gedung, panel kontrol PLC, dan gardu distribusi kawasan industri.','1. Pendidikan minimal SMK Teknik Listrik / Elektronika atau D3 Elektro\n2. Menguasai pembacaan wiring diagram dan dasar PLC\n3. Diutamakan memiliki sertifikat pelatihan kejuruan Listrik/Otomasi BLK\n4. Bersedia bekerja sistem shift','SMK','L',28,10,4600000,5500000,'Rembang, Kab. Pasuruan',0,1,'2026-11-12 21:06:30','2026-11-12 21:06:30'),(2,1,'Staf Administrasi & Layanan Tenant','Melayani administrasi kebutuhan perusahaan tenant di kawasan industri dan pengelolaan dokumen operasional.','1. Pendidikan minimal D3/S1 Administrasi Bisnis, Manajemen, atau Akuntansi\n2. Mahir Microsoft Office & pengolahan data\n3. Komunikatif dan rapi dalam pengarsipan','D3','L/P',30,5,4500000,5200000,'Rembang, Kab. Pasuruan',1,1,'2026-11-12 21:06:30','2026-11-12 21:06:30'),(3,2,'Operator Produksi & Pengemasan','Mengoperasikan mesin lini produksi makanan dan memastikan standar mutu kebersihan GMP/HACCP terpenuhi.','1. Pendidikan minimal SMA/SMK semua jurusan (diutamakan Teknik Mesin / Pengolahan Pangan)\r\n2. Sehat jasmani dan tidak buta warna\r\n3. Disiplin, cekatan, dan siap kerja 3 shift','SMA','L/P',25,35,NULL,NULL,'Sukorejo, Kab. Pasuruan',0,1,'2026-11-12 21:06:30','2026-11-12 22:12:12'),(4,2,'Quality Control (QC) Inspector','Melakukan pengujian sampel bahan baku dan produk jadi di laboratorium pabrik.','1. Pendidikan minimal SMK Kimia Analis / D3 Teknologi Pangan / Kimia\r\n2. Teliti dan memahami prosedur laboratorium','D3','L/P',27,8,NULL,NULL,'Sukorejo, Kab. Pasuruan',0,1,'2026-11-12 21:06:30','2026-11-12 22:12:03'),(5,3,'Welder & Operator Mesin CNC','Melakukan proses pengelasan presisi dan pengoperasian mesin bubut/milling CNC untuk komponen logam.','1. Pendidikan minimal SMK Teknik Pemesinan / Pengelasan\n2. Memiliki sertifikat pelatihan Las (SMAW/GMAW) atau CNC (diutamakan Alumni BLK)\n3. Mampu membaca gambar teknik dengan akurat','SMK','L',27,20,4700000,5600000,'Kawasan PIER, Kab. Pasuruan',0,1,'2026-11-12 21:06:30','2026-11-12 21:06:30'),(6,3,'IT Support & Jaringan','Memelihara infrastruktur jaringan LAN/WiFi pabrik, server lokal, dan troubleshooting perangkat komputer pengguna.','1. Pendidikan minimal SMK TKJ / D3/S1 Teknik Informatika\n2. Memahami jaringan TCP/IP, Mikrotik, dan hardware PC\n3. Terbuka untuk penyandang disabilitas daksa ringan','D3','L/P',30,3,4900000,6200000,'Kawasan PIER, Kab. Pasuruan',1,1,'2026-11-12 21:06:30','2026-11-12 21:06:30');
/*!40000 ALTER TABLE `lowongan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-11-12-000001','App\\Database\\Migrations\\CreateAllJmfTables','default','App',1794492256,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pencaker`
--

DROP TABLE IF EXISTS `pencaker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pencaker` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `nik` char(16) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `jk` enum('L','P') NOT NULL DEFAULT 'L',
  `alamat` text DEFAULT NULL,
  `domisili` enum('kab_pasuruan','luar') NOT NULL DEFAULT 'kab_pasuruan',
  `desa_id` int(11) unsigned DEFAULT NULL,
  `kota_luar` varchar(100) DEFAULT NULL,
  `hp` varchar(30) DEFAULT NULL,
  `pendidikan` enum('SD','SMP','SMA','SMK','D1','D2','D3','D4/S1','S2') NOT NULL DEFAULT 'SMK',
  `jurusan` varchar(150) DEFAULT NULL,
  `disabilitas` tinyint(1) NOT NULL DEFAULT 0,
  `jenis_disabilitas` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `link_berkas` varchar(500) DEFAULT NULL,
  `profil_lengkap` tinyint(1) NOT NULL DEFAULT 0,
  `setuju_pdp` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `nik` (`nik`),
  KEY `desa_id` (`desa_id`),
  CONSTRAINT `pencaker_desa_id_foreign` FOREIGN KEY (`desa_id`) REFERENCES `desa` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  CONSTRAINT `pencaker_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pencaker`
--

LOCK TABLES `pencaker` WRITE;
/*!40000 ALTER TABLE `pencaker` DISABLE KEYS */;
INSERT INTO `pencaker` VALUES (1,7,'3514111505010001','Ahmad Fauzi Pratama','Pasuruan','2001-05-15','L','Jl. Kartini RT 02 RW 05, Pandaan','kab_pasuruan',172,NULL,'085712345601','SMK','Teknik Instalasi Tenaga Listrik',0,NULL,NULL,'https://drive.google.com/file/d/1SampleAhmadFauziBerkasGabunganPDF/view?usp=sharing',1,1,'2026-11-12 21:06:30','2026-11-12 21:06:30'),(2,8,'3514015208000002','Dewi Lestari Ningrum','Pasuruan','2000-08-12','P','Perum Bangil Indah Blok C-12, Bangil','kab_pasuruan',13,NULL,'085712345602','D3','Administrasi Bisnis',0,NULL,NULL,'https://drive.google.com/file/d/1SampleDewiLestariBerkasGabunganPDF/view?usp=sharing',1,1,'2026-11-12 21:06:30','2026-11-12 21:06:30'),(3,9,'3575011001990003','Rizky Akbar Maulana','Kota Pasuruan','1999-01-10','L','Jl. Panglima Sudirman No. 45, Purworejo','luar',NULL,'Kota Pasuruan','085712345603','D4/S1','Teknik Informatika',1,'Disabilitas Daksa Ringan',NULL,'https://drive.google.com/file/d/1SampleRizkyAkbarBerkasGabunganPDF/view?usp=sharing',1,1,'2026-11-12 21:06:30','2026-11-12 21:06:30');
/*!40000 ALTER TABLE `pencaker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengalaman_kerja`
--

DROP TABLE IF EXISTS `pengalaman_kerja`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pengalaman_kerja` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pencaker_id` int(11) unsigned NOT NULL,
  `nama_perusahaan` varchar(150) NOT NULL,
  `jabatan` varchar(150) NOT NULL,
  `tahun_mulai` year(4) NOT NULL,
  `tahun_selesai` varchar(10) NOT NULL DEFAULT 'Sekarang',
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pencaker_id` (`pencaker_id`),
  CONSTRAINT `pengalaman_kerja_pencaker_id_foreign` FOREIGN KEY (`pencaker_id`) REFERENCES `pencaker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengalaman_kerja`
--

LOCK TABLES `pengalaman_kerja` WRITE;
/*!40000 ALTER TABLE `pengalaman_kerja` DISABLE KEYS */;
INSERT INTO `pengalaman_kerja` VALUES (1,1,'CV Karya Teknik Mandiri','Teknisi Magang',2024,'2025',NULL,'2026-11-12 21:06:30'),(2,2,'PT Mitra Niaga Jaya','Admin Gudang',2023,'2025',NULL,'2026-11-12 21:06:30'),(3,3,'Freelance Web Studio','IT Support & Web Dev',2023,'Sekarang',NULL,'2026-11-12 21:06:30');
/*!40000 ALTER TABLE `pengalaman_kerja` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengaturan`
--

DROP TABLE IF EXISTS `pengaturan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pengaturan` (
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengaturan`
--

LOCK TABLES `pengaturan` WRITE;
/*!40000 ALTER TABLE `pengaturan` DISABLE KEYS */;
INSERT INTO `pengaturan` VALUES ('alamat_instansi','Jl. Pahlawan Sunaryo No.96s, Pandaan, Kab. Pasuruan, Jawa Timur 67156','2026-11-12 21:55:18'),('email_instansi','jmf@uptblkpasuruan.com','2026-11-12 21:06:30'),('kepala_blk','ERWIN INDRA WIDJAJA, S.E, M.A.P','2026-11-12 21:55:18'),('nama_instansi','UPT Balai Latihan Kerja (BLK) Pasuruan','2026-11-12 21:55:18'),('nip_kepala_blk','196908231990031008','2026-11-12 21:55:18'),('smtp_crypto','ssl','2026-11-12 21:55:18'),('smtp_from_name','Panitia Job Market Fair BLK Pasuruan','2026-11-12 21:55:18'),('smtp_host','mail.uptblkpasuruan.com','2026-11-12 21:55:18'),('smtp_pass','Ln]*WvR,ULUh+DQ(','2026-11-12 21:55:18'),('smtp_port','465','2026-11-12 21:55:18'),('smtp_user','jmf@uptblkpasuruan.com','2026-11-12 21:55:18'),('sub_instansi','Dinas Tenaga Kerja dan Transmigrasi Provinsi Jawa Timur','2026-11-12 21:55:18'),('telp_instansi','(0343) 631696','2026-11-12 21:55:18'),('website_instansi','https://uptblkpasuruan.com','2026-11-12 21:06:30');
/*!40000 ALTER TABLE `pengaturan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perusahaan`
--

DROP TABLE IF EXISTS `perusahaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perusahaan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `nama` varchar(200) NOT NULL,
  `bidang_usaha` varchar(150) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `telp` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `nama_hrd` varchar(150) DEFAULT NULL,
  `hp_hrd` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  CONSTRAINT `perusahaan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perusahaan`
--

LOCK TABLES `perusahaan` WRITE;
/*!40000 ALTER TABLE `perusahaan` DISABLE KEYS */;
INSERT INTO `perusahaan` VALUES (1,4,'PT Pasuruan Industrial Estate Rembang (PIER)','Kawasan Industri & Manufaktur','Jl. Raya Surabaya - Probolinggo KM 50, Rembang, Pasuruan','Kab. Pasuruan','(0343) 741613','hrd@pier-industri.co.id','https://pier.co.id',NULL,'Pengelola kawasan industri terpadu di Kabupaten Pasuruan bagian dari grup SIER.','Budi Santoso, S.Psi.','081234567801','2026-11-12 21:06:30','2026-11-12 21:06:30'),(2,5,'PT Indofood CBP Sukses Makmur Tbk (Plant Pandaan)','Industri Makanan & Minuman (FMCG)','Jl. Raya Surabaya - Malang KM 48, Suwayuwo, Sukorejo, Pasuruan','Kab. Pasuruan','(0343) 631455','recruitment@indofood-pandaan.co.id','https://indofoodcbp.com',NULL,'Perusahaan produsen produk konsumen bermerek terkemuka di Indonesia dengan fasilitas pabrik modern di Pandaan/Sukorejo.','Siti Aminah, M.M.','081234567802','2026-11-12 21:06:30','2026-11-12 21:06:30'),(3,6,'PT Yamaha Musical Products Asia','Manufaktur Alat Musik & Elektronik','Kawasan Industri PIER Jl. Rembang Industri I No.36, Pasuruan','Kab. Pasuruan','(0343) 740255','karir@yamaha-electronics-pasuruan.co.id','https://id.yamaha.com',NULL,'Pabrik manufaktur instrumen musik berstandar global yang berorientasi ekspor.','Hendra Wijaya, S.T.','081234567803','2026-11-12 21:06:30','2026-11-12 21:06:30');
/*!40000 ALTER TABLE `perusahaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survei_jawaban`
--

DROP TABLE IF EXISTS `survei_jawaban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survei_jawaban` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pertanyaan_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `nilai` tinyint(1) DEFAULT NULL,
  `jawaban_teks` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pertanyaan_id_user_id` (`pertanyaan_id`,`user_id`),
  KEY `survei_jawaban_user_id_foreign` (`user_id`),
  CONSTRAINT `survei_jawaban_pertanyaan_id_foreign` FOREIGN KEY (`pertanyaan_id`) REFERENCES `survei_pertanyaan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `survei_jawaban_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survei_jawaban`
--

LOCK TABLES `survei_jawaban` WRITE;
/*!40000 ALTER TABLE `survei_jawaban` DISABLE KEYS */;
/*!40000 ALTER TABLE `survei_jawaban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `survei_pertanyaan`
--

DROP TABLE IF EXISTS `survei_pertanyaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `survei_pertanyaan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL,
  `target` enum('pencaker','perusahaan') NOT NULL,
  `pertanyaan` varchar(300) NOT NULL,
  `tipe` enum('skala','teks') NOT NULL DEFAULT 'skala',
  `urutan` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id_target` (`event_id`,`target`),
  CONSTRAINT `survei_pertanyaan_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `survei_pertanyaan`
--

LOCK TABLES `survei_pertanyaan` WRITE;
/*!40000 ALTER TABLE `survei_pertanyaan` DISABLE KEYS */;
INSERT INTO `survei_pertanyaan` VALUES (1,1,'pencaker','Kemudahan proses pendaftaran online','skala',1,'2026-11-12 21:06:30'),(2,1,'pencaker','Kecepatan & keramahan layanan check-in','skala',2,'2026-11-12 21:06:30'),(3,1,'pencaker','Kemudahan melamar lewat scan QR booth','skala',3,'2026-11-12 21:06:30'),(4,1,'pencaker','Kesesuaian lowongan dengan pendidikan/keahlian Anda','skala',4,'2026-11-12 21:06:30'),(5,1,'pencaker','Kenyamanan lokasi & penataan acara','skala',5,'2026-11-12 21:06:30'),(6,1,'pencaker','Kepuasan keseluruhan terhadap Job Market Fair','skala',6,'2026-11-12 21:06:30'),(7,1,'pencaker','Saran dan masukan untuk pelaksanaan berikutnya','teks',7,'2026-11-12 21:06:30'),(8,1,'perusahaan','Kemudahan penggunaan aplikasi (kelola lowongan & pelamar)','skala',1,'2026-11-12 21:06:30'),(9,1,'perusahaan','Kualitas/kesesuaian pelamar dengan kebutuhan perusahaan','skala',2,'2026-11-12 21:06:30'),(10,1,'perusahaan','Pelayanan panitia BLK Pasuruan','skala',3,'2026-11-12 21:06:30'),(11,1,'perusahaan','Fasilitas booth & lokasi acara','skala',4,'2026-11-12 21:06:30'),(12,1,'perusahaan','Minat perusahaan untuk ikut kembali tahun depan','skala',5,'2026-11-12 21:06:30'),(13,1,'perusahaan','Kepuasan keseluruhan terhadap pelaksanaan Job Market Fair','skala',6,'2026-11-12 21:06:30'),(14,1,'perusahaan','Saran dan masukan untuk panitia BLK Pasuruan','teks',7,'2026-11-12 21:06:30');
/*!40000 ALTER TABLE `survei_pertanyaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','petugas','pimpinan','perusahaan','pencaker') NOT NULL DEFAULT 'pencaker',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `role` (`role`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin BLK Pasuruan','admin@uptblkpasuruan.com','$2y$10$uKd8vOeQ2sD12IGP/uhoruPa0DGfYyaKnicdsA1QHtOa.FM6OGihy','admin',1,'2026-11-12 22:09:00','2026-11-12 21:06:30','2026-11-12 22:10:22'),(2,'Petugas Check-In','petugas@uptblkpasuruan.com','$2y$10$mwvsCWUGrcYJkyBbRkDRQ.rJvhqKhWlQ8/PqDohD2HfWAx9SH1FvG','petugas',1,'2026-11-12 21:59:59','2026-11-12 21:06:30','2026-11-12 22:10:41'),(3,'Kepala BLK Pasuruan','pimpinan@uptblkpasuruan.com','$2y$10$KRKgjJXdzrKhDKiHe0JdQOdNMjEVfd.3VsTHYwvAPMmYvPhw86q22','pimpinan',1,'2026-11-12 22:17:17','2026-11-12 21:06:30','2026-11-12 22:11:08'),(4,'PT Pasuruan Industrial Estate Rembang (PIER)','hrd@pier-industri.co.id','$2y$10$xDGvriKEVAbx7XOzCmg2Mug8TZoSC1dOKLSfnXiI.M8llKdshvdEm','perusahaan',1,'2026-11-12 22:15:51','2026-11-12 21:06:30','2026-11-12 21:06:30'),(5,'PT Indofood CBP Sukses Makmur Tbk (Plant Pandaan)','recruitment@indofood-pandaan.co.id','$2y$10$11YOHChu8xSSro4dZ616iO0VMbAkRkgoY8X8cT1bcZEGzKeI2OuIS','perusahaan',1,'2026-11-12 22:11:29','2026-11-12 21:06:30','2026-11-12 21:06:30'),(6,'PT Yamaha Musical Products Asia','karir@yamaha-electronics-pasuruan.co.id','$2y$10$brEPsXHqbTygqt./hut49.aQK1hOFx3lFm56TYuWVOhVHm7cPXzWC','perusahaan',1,'2026-11-12 22:15:35','2026-11-12 21:06:30','2026-11-12 21:06:30'),(7,'Ahmad Fauzi Pratama','ahmad.fauzi@gmail.com','$2y$10$sKK1fx2btteZu/yHrzB4WObW2wyDPx/IMiJBK3Xz8udcLspQqr28S','pencaker',1,'2026-11-12 22:06:47','2026-11-12 21:06:30','2026-11-12 21:06:30'),(8,'Dewi Lestari Ningrum','dewi.lestari@gmail.com','$2y$10$T2MCw4SJQLe692Ot0kE4R.gtgGjoduekYOXZvh3TWsZmvCjO6VvSu','pencaker',1,NULL,'2026-11-12 21:06:30','2026-11-12 21:06:30'),(9,'Rizky Akbar Maulana','rizky.akbar@gmail.com','$2y$10$6gE2GtIoO7Mj9.arndclA.Evp7Y/fDIGJFnx3m.3zBHJ48dQCjFxq','pencaker',1,NULL,'2026-11-12 21:06:30','2026-11-12 21:06:30');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-12 22:45:05
