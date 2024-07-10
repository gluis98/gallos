/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : gallos

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2024-07-10 16:43:02
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for clientes
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of clientes
-- ----------------------------

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for gallinas
-- ----------------------------
DROP TABLE IF EXISTS `gallinas`;
CREATE TABLE `gallinas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `padre_id` int(10) unsigned DEFAULT NULL,
  `madre_id` int(10) unsigned DEFAULT NULL,
  `placa` varchar(255) DEFAULT '',
  `marca_nacimiento` varchar(255) DEFAULT NULL,
  `marca_federacion` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `color_alternativo` varchar(255) DEFAULT '',
  `cresta` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` varchar(255) DEFAULT NULL,
  `luna` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estatus` varchar(255) DEFAULT 'Activa',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallinas
-- ----------------------------
INSERT INTO `gallinas` VALUES ('4', '27', '4', '1234', 'Marca', 'Marca', 'Calica', 'Negro', 'Pava', '2024-07-08', 'Llena', null, 'Activa');
INSERT INTO `gallinas` VALUES ('5', '27', '4', '5413', 'Marca', 'Marca', 'Zambo', 'Negro', 'Lisa', '2024-07-08', 'Llena', null, 'Activa');

-- ----------------------------
-- Table structure for gallinas_imagenes
-- ----------------------------
DROP TABLE IF EXISTS `gallinas_imagenes`;
CREATE TABLE `gallinas_imagenes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gallina_id` int(10) unsigned NOT NULL,
  `imagen` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gallo_id` (`gallina_id`),
  CONSTRAINT `gallinas_imagenes_ibfk_1` FOREIGN KEY (`gallina_id`) REFERENCES `gallinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallinas_imagenes
-- ----------------------------
INSERT INTO `gallinas_imagenes` VALUES ('3', '4', 'images.jpeg');
INSERT INTO `gallinas_imagenes` VALUES ('4', '5', 'images.jpeg');
INSERT INTO `gallinas_imagenes` VALUES ('5', '4', 'images.jpeg');
INSERT INTO `gallinas_imagenes` VALUES ('6', '5', 'images.jpeg');

-- ----------------------------
-- Table structure for gallos
-- ----------------------------
DROP TABLE IF EXISTS `gallos`;
CREATE TABLE `gallos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `placa` varchar(255) DEFAULT '',
  `marca_nacimiento` varchar(255) DEFAULT NULL,
  `marca_federacion` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `color_alternativo` varchar(255) DEFAULT NULL,
  `cresta` varchar(255) DEFAULT NULL,
  `fecha_nacimiento` varchar(255) DEFAULT NULL,
  `luna` varchar(255) DEFAULT NULL,
  `peleas` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estatus` varchar(255) DEFAULT 'Activo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallos
-- ----------------------------
INSERT INTO `gallos` VALUES ('27', '5412', 'Marca', 'Marca', 'Calica', 'Negro', 'Roseta', '2024-07-08', 'Menguante gibosa', '3', null, 'Activo');
INSERT INTO `gallos` VALUES ('28', '5413', 'Marca', 'Marca', 'Zambo', 'Negro', 'Lisa', '2024-07-10', 'Nueva', '3', null, 'Activo');

-- ----------------------------
-- Table structure for gallos_hijos
-- ----------------------------
DROP TABLE IF EXISTS `gallos_hijos`;
CREATE TABLE `gallos_hijos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `padre_id` int(10) unsigned DEFAULT NULL,
  `madre_id` int(10) unsigned DEFAULT NULL,
  `hijo_id` int(10) unsigned DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `padre_id` (`padre_id`),
  KEY `gallos_hijos_ibfk_2` (`hijo_id`),
  KEY `madre_id` (`madre_id`),
  CONSTRAINT `gallos_hijos_ibfk_1` FOREIGN KEY (`padre_id`) REFERENCES `gallos` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `gallos_hijos_ibfk_3` FOREIGN KEY (`madre_id`) REFERENCES `gallinas` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallos_hijos
-- ----------------------------
INSERT INTO `gallos_hijos` VALUES ('51', '27', '4', '4', 'Gallina');
INSERT INTO `gallos_hijos` VALUES ('52', '27', '4', '5', 'Gallina');
INSERT INTO `gallos_hijos` VALUES ('54', null, '5', '27', 'Gallo');
INSERT INTO `gallos_hijos` VALUES ('55', '27', '5', '28', 'Gallo');

-- ----------------------------
-- Table structure for gallos_imagenes
-- ----------------------------
DROP TABLE IF EXISTS `gallos_imagenes`;
CREATE TABLE `gallos_imagenes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `gallo_id` int(10) unsigned NOT NULL,
  `imagen` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gallo_id` (`gallo_id`),
  CONSTRAINT `gallos_imagenes_ibfk_1` FOREIGN KEY (`gallo_id`) REFERENCES `gallos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallos_imagenes
-- ----------------------------
INSERT INTO `gallos_imagenes` VALUES ('17', '27', 'complemento2.png');
INSERT INTO `gallos_imagenes` VALUES ('19', '28', 'Gallo-combate.jpeg');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES ('1', '2014_10_12_000000_create_users_table', '1');
INSERT INTO `migrations` VALUES ('2', '2014_10_12_100000_create_password_reset_tokens_table', '1');
INSERT INTO `migrations` VALUES ('3', '2019_08_19_000000_create_failed_jobs_table', '1');
INSERT INTO `migrations` VALUES ('4', '2019_12_14_000001_create_personal_access_tokens_table', '1');

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Diego', 'admin@mail.com', null, '$2y$12$bhirqdMp66KFmIQ3xzsUVOltDVKmV7sce9/zH1KJ96yKgfU/jIKkm', null, '2024-07-04 02:41:25', '2024-07-04 02:41:25');

-- ----------------------------
-- Table structure for ventas
-- ----------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gallo_id` int(10) unsigned DEFAULT NULL,
  `nombre_cliente` varchar(255) DEFAULT '',
  `telefono` varchar(255) DEFAULT '',
  `monto` decimal(10,0) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estatus` varchar(255) DEFAULT 'Finalizada',
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `gallo_id` (`gallo_id`),
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`gallo_id`) REFERENCES `gallos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of ventas
-- ----------------------------
