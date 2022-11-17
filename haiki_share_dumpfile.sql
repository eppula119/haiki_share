-- MySQL dump 10.13  Distrib 5.7.37, for Linux (x86_64)
--
-- Host: localhost    Database: haiki_share
-- ------------------------------------------------------
-- Server version	5.7.37

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bought_products`
--

DROP TABLE IF EXISTS `bought_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bought_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bought_products_product_id_foreign` (`product_id`),
  KEY `bought_products_user_id_foreign` (`user_id`),
  CONSTRAINT `bought_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  CONSTRAINT `bought_products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bought_products`
--

LOCK TABLES `bought_products` WRITE;
/*!40000 ALTER TABLE `bought_products` DISABLE KEYS */;
INSERT INTO `bought_products` VALUES (1,'bhIrwZJ-IFf4JnsX0YxR',7,NULL,'2022-11-18 08:32:53','2022-11-18 08:32:53'),(2,'bhIrwZJ-IFf4JnsX0YxR',7,'2022-11-18 08:33:45','2022-11-18 08:33:45','2022-11-18 08:33:45'),(3,'w1_3kp7-ku6gUFd7yqXI',7,NULL,'2022-11-18 08:34:20','2022-11-18 08:34:20'),(4,'wwoMClRH_2hr192c7UG6',7,NULL,'2022-11-18 08:34:31','2022-11-18 08:34:31'),(5,'CwQn9zfCMoD1pXkrF-52',7,NULL,'2022-11-18 08:34:41','2022-11-18 08:34:41'),(6,'K28q3kBAlHsrMfLkDzyl',7,NULL,'2022-11-18 08:34:51','2022-11-18 08:34:51'),(7,'CiFB3BIaX0M_DxANoZLQ',7,NULL,'2022-11-18 08:35:06','2022-11-18 08:35:06'),(8,'o-Zt3yX-AgGZe1EmmHTA',7,NULL,'2022-11-18 08:35:23','2022-11-18 08:35:23'),(9,'aHefoBcxqXgzJEOE6cPE',7,NULL,'2022-11-18 08:36:30','2022-11-18 08:36:30'),(10,'EK6UScUvqYhlxXN-sxwI',7,NULL,'2022-11-18 08:36:45','2022-11-18 08:36:45'),(11,'Y1YxmB2WivznqeIB9_ju',7,NULL,'2022-11-18 08:37:11','2022-11-18 08:37:11'),(12,'oiGqcJSHdqyp7Wdi9aGo',7,NULL,'2022-11-18 08:37:25','2022-11-18 08:37:25'),(13,'sdNvtDLnRt0CeVEwQqcG',7,NULL,'2022-11-18 08:37:37','2022-11-18 08:37:37'),(14,'P2gdOIQ18T5sgGpEsgCE',7,NULL,'2022-11-18 08:37:52','2022-11-18 08:37:52'),(15,'bhIrwZJ-IFf4JnsX0YxR',8,NULL,'2022-11-18 08:38:54','2022-11-18 08:38:54');
/*!40000 ALTER TABLE `bought_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (47,'2022_09_20_000000_create_users_table',1),(48,'2022_09_20_100000_create_password_resets_table',1),(49,'2022_09_20_234423_create_prefectures_table',1),(50,'2022_09_20_235723_create_shops_table',1),(51,'2022_09_21_000402_create_products_table',1),(52,'2022_09_21_000658_create_bought_products_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prefectures`
--

DROP TABLE IF EXISTS `prefectures`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prefectures` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prefectures`
--

LOCK TABLES `prefectures` WRITE;
/*!40000 ALTER TABLE `prefectures` DISABLE KEYS */;
INSERT INTO `prefectures` VALUES (1,'北海道','2022-11-17 23:41:41','2022-11-17 23:41:41'),(2,'青森県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(3,'岩手県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(4,'宮城県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(5,'秋田県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(6,'山形県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(7,'福島県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(8,'茨城県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(9,'栃木県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(10,'群馬県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(11,'埼玉県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(12,'千葉県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(13,'東京都','2022-11-17 23:41:41','2022-11-17 23:41:41'),(14,'神奈川県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(15,'福井県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(16,'富山県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(17,'石川県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(18,'新潟県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(19,'山梨県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(20,'長野県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(21,'岐阜県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(22,'静岡県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(23,'愛知県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(24,'三重県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(25,'滋賀県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(26,'京都府','2022-11-17 23:41:41','2022-11-17 23:41:41'),(27,'大阪府','2022-11-17 23:41:41','2022-11-17 23:41:41'),(28,'兵庫県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(29,'奈良県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(30,'和歌山県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(31,'鳥取県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(32,'島根県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(33,'岡山県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(34,'広島県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(35,'山口県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(36,'徳島県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(37,'香川県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(38,'愛媛県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(39,'高知県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(40,'福岡県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(41,'佐賀県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(42,'長崎県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(43,'熊本県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(44,'大分県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(45,'宮崎県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(46,'鹿児島県','2022-11-17 23:41:41','2022-11-17 23:41:41'),(47,'沖縄県','2022-11-17 23:41:41','2022-11-17 23:41:41');
/*!40000 ALTER TABLE `prefectures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `best_before` datetime NOT NULL,
  `image_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_5` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shop_id` int(10) unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_shop_id_foreign` (`shop_id`),
  CONSTRAINT `products_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES ('0e_iZYeHXt2uo-n9tt_G','たまご海老カツサンド',230,'2022-11-18 00:00:00','0e_iZYeHXt2uo-n9tt_G_たまご＆海老カツサンド_1.jpeg','0e_iZYeHXt2uo-n9tt_G_たまご＆海老カツサンド_2.jpeg','0e_iZYeHXt2uo-n9tt_G_たまご＆海老カツサンド_3.jpeg','0e_iZYeHXt2uo-n9tt_G_たまご＆海老カツサンド_4.jpeg','0e_iZYeHXt2uo-n9tt_G_たまご＆海老カツサンド_5.jpeg',31,NULL,'2022-11-17 23:53:42','2022-11-17 23:53:42'),('5xWzud5OCqXXHu41wp0q','大きなおむすび　チキンライス',60,'2022-11-23 00:00:00','5xWzud5OCqXXHu41wp0q_大きなおむすび　チキンライス.jpeg',NULL,NULL,NULL,NULL,31,NULL,'2022-11-17 23:55:59','2022-11-17 23:55:59'),('9tDKGj2wxFY73EvI24WL','こだわりたまごのカスタード＆ホイップシュー',100,'2022-11-17 00:00:00','9tDKGj2wxFY73EvI24WL_こだわりたまごのカスタード＆ホイップシュー_1.jpeg','9tDKGj2wxFY73EvI24WL_こだわりたまごのカスタード＆ホイップシュー_2.jpeg','9tDKGj2wxFY73EvI24WL_こだわりたまごのカスタード＆ホイップシュー_3.jpeg','9tDKGj2wxFY73EvI24WL_こだわりたまごのカスタード＆ホイップシュー_4.jpeg','9tDKGj2wxFY73EvI24WL_こだわりたまごのカスタード＆ホイップシュー_5.jpeg',31,NULL,'2022-11-17 23:51:23','2022-11-17 23:51:23'),('aHefoBcxqXgzJEOE6cPE','クッキークリーム&チョコロールパン',200,'2022-11-19 00:00:00','aHefoBcxqXgzJEOE6cPE_クッキークリーム&チョコロールパン_1.jpeg','aHefoBcxqXgzJEOE6cPE_クッキークリーム&チョコロールパン_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:14:29','2022-11-18 08:14:29'),('AO0apefE4xV3L9nSUPS-','かけ算おにぎり ラー油×牛焼肉',80,'2022-11-20 11:00:00','AO0apefE4xV3L9nSUPS-_かけ算おにぎり ラー油×牛焼肉_1.jpeg','AO0apefE4xV3L9nSUPS-_かけ算おにぎり ラー油×牛焼肉_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:14:58','2022-11-18 08:14:58'),('bhIrwZJ-IFf4JnsX0YxR','タレ弁 牛カルビ焼肉重',300,'2022-11-19 00:45:00','bhIrwZJ-IFf4JnsX0YxR_タレ弁 牛カルビ焼肉重_1.jpeg','bhIrwZJ-IFf4JnsX0YxR_タレ弁 牛カルビ焼肉重_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:17:46','2022-11-18 08:17:46'),('CiFB3BIaX0M_DxANoZLQ','根菜チキンのサラダラップ',220,'2022-11-20 04:00:00','CiFB3BIaX0M_DxANoZLQ_根菜チキンのサラダラップ_1.jpeg','CiFB3BIaX0M_DxANoZLQ_根菜チキンのサラダラップ_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:22:15','2022-11-18 08:22:15'),('CwQn9zfCMoD1pXkrF-52','フレッシュフルーツサンド',210,'2022-11-19 11:00:00','CwQn9zfCMoD1pXkrF-52_フレッシュフルーツサンド_1.jpeg','CwQn9zfCMoD1pXkrF-52_フレッシュフルーツサンド_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:22:57','2022-11-18 08:22:57'),('dYijasYSgsTnSJqpcy_e','薄削りチーズのシーザーサラダ',100,'2022-11-23 12:00:00','dYijasYSgsTnSJqpcy_e_薄削りチーズのシーザーサラダ_1.jpeg','dYijasYSgsTnSJqpcy_e_薄削りチーズのシーザーサラダ_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:19:42','2022-11-18 08:19:42'),('Ebusii2yyDGq9ivpOpSQ','まんぷく！ハムステーキ＆豚焼肉弁当',500,'2022-11-26 22:30:00','Ebusii2yyDGq9ivpOpSQ_まんぷく！ハムステーキ＆豚焼肉弁当_1.jpeg','Ebusii2yyDGq9ivpOpSQ_まんぷく！ハムステーキ＆豚焼肉弁当_2.jpeg','Ebusii2yyDGq9ivpOpSQ_まんぷく！ハムステーキ＆豚焼肉弁当_3.jpeg','Ebusii2yyDGq9ivpOpSQ_まんぷく！ハムステーキ＆豚焼肉弁当_4.jpeg','Ebusii2yyDGq9ivpOpSQ_まんぷく！ハムステーキ＆豚焼肉弁当_5.jpeg',31,NULL,'2022-11-17 23:55:10','2022-11-17 23:55:10'),('EK6UScUvqYhlxXN-sxwI','寿司おにぎり Wサーモン',100,'2022-11-19 21:45:00','EK6UScUvqYhlxXN-sxwI_寿司おにぎり Wサーモン_1.jpeg','EK6UScUvqYhlxXN-sxwI_寿司おにぎり Wサーモン_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:14:04','2022-11-18 08:14:04'),('fdY5e7dEsvCGz5kqgrYr','マロンチーズケーキ',440,'2022-11-24 22:00:00','fdY5e7dEsvCGz5kqgrYr_マロンチーズケーキ_1.jpeg','fdY5e7dEsvCGz5kqgrYr_マロンチーズケーキ_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:20:47','2022-11-18 08:20:47'),('fDzEmYnmMezGkDuEmLPH','生とろプリン　キャラメル',210,'2022-11-19 02:00:00','fDzEmYnmMezGkDuEmLPH_生とろプリン　キャラメル_1.jpeg','fDzEmYnmMezGkDuEmLPH_生とろプリン　キャラメル_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:21:09','2022-11-18 08:21:09'),('K28q3kBAlHsrMfLkDzyl','ベーコンレタスチーズバーガー',500,'2022-11-25 23:00:00','K28q3kBAlHsrMfLkDzyl_ベーコンレタスチーズバーガー_1.jpeg','K28q3kBAlHsrMfLkDzyl_ベーコンレタスチーズバーガー_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:22:36','2022-11-18 08:22:36'),('Lz-6z5syl8MDjuruU7qe','炒飯＆チキン南蛮弁当',600,'2022-11-18 00:00:00','Lz-6z5syl8MDjuruU7qe_炒飯＆チキン南蛮弁当_1.jpeg','Lz-6z5syl8MDjuruU7qe_炒飯＆チキン南蛮弁当_2.jpeg','Lz-6z5syl8MDjuruU7qe_炒飯＆チキン南蛮弁当_3.jpeg',NULL,NULL,31,NULL,'2022-11-17 23:56:39','2022-11-17 23:56:39'),('N_384tIqIujHS9JJ83zW','とろーりチーズとトマトのピザまん',100,'2022-11-18 00:00:00','N_384tIqIujHS9JJ83zW_とろーりチーズとトマトのピザまん  .png','N_384tIqIujHS9JJ83zW_とろーりチーズとトマトのピザまん_2.jpeg','N_384tIqIujHS9JJ83zW_とろーりチーズとトマトのピザまん_3.jpeg',NULL,NULL,32,NULL,'2022-11-18 00:00:06','2022-11-18 00:00:06'),('nrJPCEKjQvfuNbssF0MJ','ピスタチオプリン',400,'2022-12-01 04:00:00','nrJPCEKjQvfuNbssF0MJ_ピスタチオプリン_1.jpeg','nrJPCEKjQvfuNbssF0MJ_ピスタチオプリン_2.jpeg','nrJPCEKjQvfuNbssF0MJ_ピスタチオプリン_3.jpeg',NULL,NULL,31,NULL,'2022-11-17 23:54:21','2022-11-17 23:54:21'),('o-Zt3yX-AgGZe1EmmHTA','トルティーヤ　ハム&とろ～り4種チーズ',220,'2022-11-26 00:00:00','o-Zt3yX-AgGZe1EmmHTA_トルティーヤ　ハム&とろ～り4種チーズ_1.jpeg','o-Zt3yX-AgGZe1EmmHTA_トルティーヤ　ハム&とろ～り4種チーズ_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:21:53','2022-11-18 08:21:53'),('oiGqcJSHdqyp7Wdi9aGo','ホイコーロー丼',330,'2022-11-19 05:00:00','oiGqcJSHdqyp7Wdi9aGo_ホイコーロー丼_1.jpeg','oiGqcJSHdqyp7Wdi9aGo_ホイコーロー丼_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:13:01','2022-11-18 08:13:01'),('P2gdOIQ18T5sgGpEsgCE','これがのり弁当',420,'2022-11-19 00:00:00','P2gdOIQ18T5sgGpEsgCE_これがのり弁当_1.jpeg','P2gdOIQ18T5sgGpEsgCE_これがのり弁当_2.jpeg','P2gdOIQ18T5sgGpEsgCE_これがのり弁当_3.jpeg','P2gdOIQ18T5sgGpEsgCE_これがのり弁当_4.jpeg','P2gdOIQ18T5sgGpEsgCE_これがのり弁当_5.jpeg',32,NULL,'2022-11-18 00:01:01','2022-11-18 00:01:01'),('p7YhTjwUvfCB1m8FGki6','ソースたっぷりタルタルフィッシュバーガー',100,'2022-11-26 17:00:00','p7YhTjwUvfCB1m8FGki6_ソースたっぷりタルタルフィッシュバーガー_1.jpeg','p7YhTjwUvfCB1m8FGki6_ソースたっぷりタルタルフィッシュバーガー_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:21:32','2022-11-18 08:21:32'),('RbxbDhSwixRGmK8kiaI3','純白のチーズケーキ',100,'2022-11-19 04:00:00','RbxbDhSwixRGmK8kiaI3_純白のチーズケーキ_1.jpeg','RbxbDhSwixRGmK8kiaI3_純白のチーズケーキ_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:16:07','2022-11-18 08:16:07'),('sdNvtDLnRt0CeVEwQqcG','ベトナム風ポークステーキ弁当',320,'2022-11-19 00:00:00','sdNvtDLnRt0CeVEwQqcG_ベトナム風ポークステーキ弁当_1.jpeg','sdNvtDLnRt0CeVEwQqcG_ベトナム風ポークステーキ弁当_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 00:02:33','2022-11-18 00:02:33'),('uCh7QnXgy5mpgewm-vBf','チーズフランスブール',320,'2022-11-30 04:00:00','uCh7QnXgy5mpgewm-vBf_チーズフランスブール_1.jpeg','uCh7QnXgy5mpgewm-vBf_チーズフランスブール_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:20:07','2022-11-18 08:20:07'),('VRGWr-J2p4eE_IevPSM8','ファミマ・ザ・クレープ　チーズ',400,'2022-11-19 00:00:00','VRGWr-J2p4eE_IevPSM8_ファミマ・ザ・クレープ　チーズ_1.jpeg','VRGWr-J2p4eE_IevPSM8_ファミマ・ザ・クレープ　チーズ_2.jpeg',NULL,NULL,NULL,34,NULL,'2022-11-18 08:20:27','2022-11-18 08:20:27'),('w1_3kp7-ku6gUFd7yqXI','生クリームパンケーキ',600,'2022-11-21 22:45:00','w1_3kp7-ku6gUFd7yqXI_生クリームパンケーキ.jpeg','w1_3kp7-ku6gUFd7yqXI_生クリームパンケーキ_2.jpeg','w1_3kp7-ku6gUFd7yqXI_生クリームパンケーキ_3.png',NULL,NULL,34,NULL,'2022-11-18 08:23:54','2022-11-18 08:23:54'),('wwoMClRH_2hr192c7UG6','宇治抹茶の生チョコタルト',220,'2022-11-25 00:00:00','wwoMClRH_2hr192c7UG6_宇治抹茶の生チョコタルト_1.jpeg','wwoMClRH_2hr192c7UG6_宇治抹茶の生チョコタルト_2.jpeg','wwoMClRH_2hr192c7UG6_宇治抹茶の生チョコタルト_3.jpeg',NULL,NULL,34,NULL,'2022-11-18 08:23:24','2022-11-18 08:23:24'),('Y1YxmB2WivznqeIB9_ju','洋梨のタルト',550,'2022-11-21 12:00:00','Y1YxmB2WivznqeIB9_ju_洋梨のタルト_1.jpeg',NULL,NULL,NULL,NULL,40,NULL,'2022-11-18 08:13:37','2022-11-18 08:13:37'),('Y41yTDmip3XVE1UQDNn7','からあげクン 日本一しょうゆ味',120,'2022-11-22 00:00:00','Y41yTDmip3XVE1UQDNn7_からあげクン 日本一しょうゆ味_1.jpeg','Y41yTDmip3XVE1UQDNn7_からあげクン 日本一しょうゆ味_2.jpeg','Y41yTDmip3XVE1UQDNn7_からあげクン 日本一しょうゆ味_3.jpeg',NULL,NULL,32,NULL,'2022-11-17 23:59:32','2022-11-17 23:59:32'),('YjtE4U3R85fDr34FJul1','ベトナムカカオチョコプリン',120,'2022-11-30 00:00:00','YjtE4U3R85fDr34FJul1_ベトナムカカオチョコプリン_1.jpeg','YjtE4U3R85fDr34FJul1_ベトナムカカオチョコプリン_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:17:19','2022-11-18 08:17:19'),('yMw4mwOLQ9Dd1jv_KJju','かけ算おにぎり 赤しそ×明太子',120,'2022-11-19 12:45:00','yMw4mwOLQ9Dd1jv_KJju_かけ算おにぎり 赤しそ×明太子_1.jpeg','yMw4mwOLQ9Dd1jv_KJju_かけ算おにぎり 赤しそ×明太子_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:16:53','2022-11-18 08:16:53'),('ZOSkm3MGaUxw3Th6Jt9s','ソフトクリーム_ベトナムカカオチョコ',200,'2022-11-21 06:00:00','ZOSkm3MGaUxw3Th6Jt9s_ソフトクリームみたいなクッキーシュー ベトナムカカオチョコ_1.jpeg','ZOSkm3MGaUxw3Th6Jt9s_ソフトクリームみたいなクッキーシュー ベトナムカカオチョコ_2.jpeg',NULL,NULL,NULL,40,NULL,'2022-11-18 08:15:27','2022-11-18 08:15:27');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shops`
--

DROP TABLE IF EXISTS `shops`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shops` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefecture_id` int(10) unsigned NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `shops_email_unique` (`email`),
  KEY `shops_prefecture_id_foreign` (`prefecture_id`),
  CONSTRAINT `shops_prefecture_id_foreign` FOREIGN KEY (`prefecture_id`) REFERENCES `prefectures` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shops`
--

LOCK TABLES `shops` WRITE;
/*!40000 ALTER TABLE `shops` DISABLE KEYS */;
INSERT INTO `shops` VALUES (31,'セブンイレブン','島田支店',32,'津田町喜嶋2-10-6','山中デパート1F','んだんゆるやかないていたのです。きっぷを拝見はいつかないわない天の川のなかって、また飛とんどんなのだ。天の川のまっすぐ眼めを細ほそくになって心配しんごのことの丘おかの光はなしに行くの野原のはずさびし。','uno.hideki@example.net','2022-11-17 23:41:41','$2y$10$xqU6831Zqe6Vv0qiOX6kkeKZAv206lbOOM4i5VhDrjo0aXXUUdS7q','luwqIasPQDCkT9PoefLz763iNx4rIbPCRNk6eqeg8qzczEXFrCdP2DbQC3KA',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(32,'ミニストップ','柳岡本店',24,'井高町江古田2-10-7','山田マンション1F','でしまいます。「そんなよろしてこわさな船に乗のっけんでちりとりは思われたときれいに間（一時間半はんした。「あらゆるやかなという、泉水せん」もうだんだよ」カムパネルラもあるけるように横目よこへ行ったり。','tanaka.shuhei@example.net','2022-11-17 23:41:41','$2y$10$gjpczLiSpGRfbIeX7bNfsueQR7/B6YVF1sNsRfnM6ElgLrHVUC6r.','wt8jk2Tdm73hsZ17GFfzcsuB8zmZoqyWEAtW7K4SP6T8NvU8MOAoql2VP6Fa',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(33,'セブンイレブン','山㟢支店',6,'原田町伊藤9-8-5','山中デパート1F','とのたったひとりは、どころもちらちらこっちを見ました。けれども、ぼくのおのようになり汽車はよした。「ぼく、さびしいのはてんだのお母さんでしたのようなもみんなが、かおる子はそのとなくせわしく立派りっぱ。','zkijima@example.net','2022-11-17 23:41:41','$2y$10$D9zLgbcgYAp46dzZ5XK0qe6Ri976WJDdQnudVUKUHtiHLolKlGEZu','FdtWGuz3Lg',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(34,'ファミリーマート','島田支店',37,'山田町加納3-2-9','田中デパート1F','きれと同じようとうしてそれかがひどくそれから流ながら、いっぱな地層ちそう。まっすぐに立ってその孔雀くじゃりの字を印刷いんで来、まもなくなったろう」腰掛こしだったところなあ」「あれは通らないとは思われ。','xkijima@example.com','2022-11-17 23:41:42','$2y$10$hrBCUjXF/bPTPsMiUL8E9uIbh.cCmL/Z6Ku2dUcd9gkn.31YzBUoS','y06VxicuSNMuwc9rF8LvwP557WWcHvd9kj2oG2S4aOFFrpgZKGVdNGU82Ept',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(35,'ファミリーマート','山㟢支店',23,'工藤町中津川10-3-1','田中デパート1F','もえて川へながらたいのりの中からね、ちらこの汽車はもう、すすきの解とけむるよ。ぐあいて立っていていたむき直なおりたくさんいました。ジョバンニはにわかになっておいようにお話しかけましたけれどもおまえた。','nakajima.akemi@example.org','2022-11-17 23:41:42','$2y$10$86w2iIzbH1i6upVYLHEOquO1Z/iJwwn21J97C8078/OCLjQEOhtsm','7aIazI3VUf',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(36,'ミニストップ','佐藤支店',9,'井高町廣川6-7-5','山中デパート1F','んと硫黄いろな形になっておりて、すぐに歩いているようにもいっしゃが走りだして私たちは思わずかになって、もうじから頭を引っ込こまっ黒にかほんに化石かせありましたら、ぱっところどらせて、何べんも幾組いく。','naoko.hirokawa@example.net','2022-11-17 23:41:42','$2y$10$xto73uLoULAdj9tLygNAg./.oXa5cPJ0nvWJbKacy.PUEKhPck4A.','Oe5ZZORIjp',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(37,'ミニストップ','柳岡本店',13,'津田町若松2-6-9','田中デパート1F','らね」「お母さん見えるように両手りょうさな黄いろが、もうずん沈しずかの道具どうだ」「いやだいどこのときに本国へお帰りに笑わらい」黒服くろをしずをかけ、たあうの方へ押おさえてせいの高い、僕ぼくがなぐさ。','wakamatsu.nanami@example.net','2022-11-17 23:41:42','$2y$10$fmsz7jEg7hP8NUiQrHQwmub/g8DlCmwF8c8/5SjvMK9sK5Haj.Y3.','MrC6QcIMSh',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(38,'ミニストップ','田中本店',16,'宮沢町山本2-1-3','山田マンション1F','わたしはまっすぐ北を指さしずかのような気がしましたが、お母さんがんだってしました。カムパネルラが川下の方へ近よって、一ぺんにいるらしいんです」泣ないの位置いちめんの上に小さな平ひらでも涙なみだな。そ。','uogaki@example.com','2022-11-17 23:41:42','$2y$10$TnHNBWj.sPk3cciI0FJ5MenHd5aoYbT3qycfIIBMvWANPESSB3Eq.','1tDpnGUbCj',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(39,'ファミリーマート','山㟢支店',7,'木村町田中8-6-9','田中デパート1F','まどの正面しょに行こうに言いいましたら、「ここのようになりませんでいろいろのが、そうでした。下流かりの口笛くちぶえを吹ふいた、せきに本国へお帰りませんです。くじゃない深ふかんして教室じゅうじゃなかか。','sakamoto.rika@example.net','2022-11-17 23:41:42','$2y$10$ZQhEoyDahu9Mjdr60LyF2.ouW7DlU1gmEKy94MuLrkSgPYsuy5rum','jY8uLOqxfd',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(40,'ローソン','島田支店',45,'吉田町宮沢9-1-6','山中デパート1F','は、だん数を増ました。「お母さんにまるで千の蛍烏賊ほたるために、夢ゆめをこわされ、その紙をジョバンニがこんな地平線ちへ、「よろこしらもうありました。先生は早く鳥がたくさんの考えを吹ふいた地図をどこま。','esuzuki@example.com','2022-11-17 23:41:42','$2y$10$Q6fL.zXEXu1EssLREtoJW.KG.B0klbh9gAWyPpZ9A9J0LUqRz0d6K','1S7Qlr4Ks8McMEyy1vQL8OwQyY3ChcURohN2tp3Fl3qqZdE5D6kSh3BbkHQe',NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42');
/*!40000 ALTER TABLE `shops` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (7,'test1','test1@test.com',NULL,'$2y$10$9nFFCd9VDMx4.U9wuxspHu6b/54dlt1mHzLN19O/Cr5LlrCFWnNTO',NULL,NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(8,'test2','test2@test.com',NULL,'$2y$10$3HAuubgh69AtK9hLzY96duhzSz9uWKUQ4uGVO4mfPgDjTseCDf2Ni',NULL,NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42'),(9,'test3','test3@test.com',NULL,'$2y$10$TrttWatNLp6rUCzQRvhvVOQJdxKiiVj0yRj8lxZQBCcfdw.Rdo.tK',NULL,NULL,'2022-11-17 23:41:42','2022-11-17 23:41:42');
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

-- Dump completed on 2022-11-17 23:49:01
