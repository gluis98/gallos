/*
Navicat MySQL Data Transfer

Source Server         : MISERVIDOR
Source Server Version : 80030
Source Host           : localhost:3306
Source Database       : gallos

Target Server Type    : MYSQL
Target Server Version : 80030
File Encoding         : 65001

Date: 2024-10-02 10:35:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for clientes
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `padre_id` int unsigned DEFAULT NULL,
  `madre_id` int unsigned DEFAULT NULL,
  `placa` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '',
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `marca_nacimiento` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `marca_federacion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color_alternativo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cresta` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nacimiento` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luna` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_general_ci,
  `estatus` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Activa',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallinas
-- ----------------------------
INSERT INTO `gallinas` VALUES ('4', null, null, '1311', 'GALLINA', 'PATA DERECHA 2 TELA', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', 'MADRE . PINTA ( PINTO HERMANO DE VENEZUELA X GALLINA PINTA LUIS RIVA PTO ORDAZ)                                    PADRE. MARAÑON PATA BLACA DE WOLFANG', 'Activa');
INSERT INTO `gallinas` VALUES ('5', null, null, '1312', 'GALLINA', 'PATA DERECHA 2 TELA PATA IZQUIERDA 1 MARCA DEL LADO ADENTRO', null, 'Marañon', 'MARAÑONA', 'Lisa', '2024-07-17', 'Nueva', 'MADRE. HERMANA DEL CIEGO ( LA DAVINCHY X VENEZUELA )                                                                           PADRE. MARAÑON PATAS BLANCAS DE WOLFANG', 'Activa');
INSERT INTO `gallinas` VALUES ('6', null, null, '1313', 'GALLINA', 'PATA IZQUIERDA 2 TELA', null, 'Marañon', 'MARAÑONA', 'Lisa', '2024-07-17', 'Nueva', 'MADRE . GALLINA ROSADA ( GIRO ABUELO PEPITO DE CESAR X GALLINA PATAS VERDES CHUITO VELA DE ADZAL )                                                                                                                                                                 PADRE . MARAÑON PATAS BLANCAS DE WOLFANG', 'Activa');
INSERT INTO `gallinas` VALUES ('7', null, null, '1314', null, 'PATA IZQUIERDA 1 MARCA TELA AFUERA MARCA NARIZ IZQUIERDA', null, 'Giro', 'GIRA', 'Lisa', '2024-07-17', 'Nueva', 'MADRE. GALLINA PATAS VERDES ( CIEGO DE ADZAL X 537 C. DELGADO MADRE DEL PATA ROJA )                               PADRE. GIRO PICO MOCHO DUDAMEL COLON POR MADRE MANDY NAVARRO', 'Activa');
INSERT INTO `gallinas` VALUES ('8', null, null, '1212', null, 'NO', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('9', null, null, '1316', null, 'PATA DERECHA 1 PRESINTO ROJO', null, 'Zambo', 'SAMBA', 'Roseta', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('10', null, null, '126', null, 'NO', null, 'Giro', 'GIRA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('11', null, null, '1376', null, 'TRINITARIA ALA DERECHA PRECINTO VERDE PATA DERECHA PRECINTO ROJO', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('12', null, null, '133', null, 'MARCA NARIZ DERECHA', null, 'Giro', 'GIRA', 'Roseta', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('13', null, null, '1317', null, 'NO', null, 'Marañon', 'SENISA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('14', null, null, '1218', null, 'MARCA NARIZ DERECHA', null, 'Zambo', 'NEGRA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('15', null, null, '1318', null, 'CANDADO 26', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('16', null, null, '1319', null, 'NO', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', 'HIJA. DEL GALLO BULICO', 'Activa');
INSERT INTO `gallinas` VALUES ('17', null, null, '172', null, 'NO', null, 'Camagüey', 'CANAGUEY', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('18', null, null, '114', null, 'MARGARITA MARCA NARIZ DERECHA', null, 'Camagüey', 'CANAGUEY', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('19', null, null, '158', null, 'NO', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('20', null, null, '1320', null, 'ALA DERECHA PRESINTO ROJO PATA DERECHA PRESINTO ROJO', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('21', null, null, '1321', null, 'TRUJILLO NARIZ DERECHA PATA DERECHA TELA AFUERA PARA IZQUIERDA TELA ADENTRO', null, 'Marañon', 'SENISA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('22', null, null, '1322', null, 'NO', null, 'Marañon', 'BULICA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('23', null, null, '125', null, 'NO', null, 'Marañon', 'MARAÑONA', 'Roseta', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('24', null, null, '129', null, 'NO', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');
INSERT INTO `gallinas` VALUES ('25', null, null, '1323', null, 'DE LA QUE TRAJO EL PROFESOR NARIZ IZQUIERDA PATA IZQUIERDA TELA ADENTRO', null, 'Zambo', 'SAMBA', 'Lisa', '2024-07-17', 'Nueva', null, 'Activa');

-- ----------------------------
-- Table structure for gallinas_imagenes
-- ----------------------------
DROP TABLE IF EXISTS `gallinas_imagenes`;
CREATE TABLE `gallinas_imagenes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `gallina_id` int unsigned NOT NULL,
  `imagen` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `gallo_id` (`gallina_id`),
  CONSTRAINT `gallinas_imagenes_ibfk_1` FOREIGN KEY (`gallina_id`) REFERENCES `gallinas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallinas_imagenes
-- ----------------------------
INSERT INTO `gallinas_imagenes` VALUES ('2', '4', 'POLLA SAMBA PLACA 1311.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('3', '5', 'POLLA MARAÑONA PLACA 1312.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('4', '6', 'POLLA MARAÑONA PLACA 1313.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('5', '7', 'POLLA GIRA PLACA 1314.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('6', '8', 'POLLA SAMBA PLACA 1212.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('7', '10', 'GALLINA GIRA PLACA 126.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('8', '9', 'GALLINA SAMBA PLACA 1316.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('9', '11', 'GALLINA SAMBA PLACA 1376.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('10', '12', 'GALLINA GIRA PLACA 133.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('11', '13', 'GALLINA SENISA PLACA 1317.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('12', '14', 'GALLINA NEGRA PLACA 1218.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('13', '15', 'GALLINA SAMBA PLACA 1318.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('14', '16', 'GALLINA SAMBA PLACA 1319.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('15', '17', 'GALLINA CANAGUEY PLACA 172.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('16', '18', 'GALLINA CANAGUEY PLACA 114.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('17', '19', 'GALLINA SAMBA PLACA 158.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('18', '20', 'GALLINA SAMBA PLACA 1320.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('19', '21', 'GALLINA SENISA PLACA 1321.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('20', '22', 'GALLINA BULICA PLACA 1322.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('21', '23', 'GALLINA MARAÑONA PLACA 125.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('22', '24', 'GALLINA SAMBA PLACA 129.jpg');
INSERT INTO `gallinas_imagenes` VALUES ('23', '25', 'GALLINA SAMBA PLACA 1323.jpg');

-- ----------------------------
-- Table structure for gallos
-- ----------------------------
DROP TABLE IF EXISTS `gallos`;
CREATE TABLE `gallos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `placa` varchar(255) COLLATE utf8mb4_general_ci DEFAULT '',
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `marca_nacimiento` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `marca_federacion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `color_alternativo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cresta` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nacimiento` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `luna` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `peleas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_general_ci,
  `estatus` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'Activo',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=87 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallos
-- ----------------------------
INSERT INTO `gallos` VALUES ('21', '132', null, 'PATA IZQUIERDA 1 MARCA DEL LADO AFUERA', '04', 'Zambo', 'SAMBO', 'Roseta', '2024-04-17', 'Nueva', '0', 'VERDE - M4- VO7589 MADRE. HERMANA DEL CIEGO ( DAVINCHY X VENEZUELA )                                                    PADRE. MARAÑON PATAS BLANCAS DE WOLFANG', 'Activo');
INSERT INTO `gallos` VALUES ('22', '145', null, 'PATA IZQUIERDA MARCA 1 TELA DE AFUERA', '04', 'Zambo', 'SAMBO', 'Lisa', '2024-04-17', 'Nueva', '0', 'VERDE -M4-VO 7584 MADRE. GALLINA PATAS VERDES HERMANA DEL PAVA                                                   PADRE. MARAÑON PATAS BLANCAS DE WOLFANG', 'Activo');
INSERT INTO `gallos` VALUES ('23', '134', null, 'PATA IZQUIERDA 2 TELA', '04', 'Zambo', 'SAMBO', 'Lisa', '2024-04-17', 'Nueva', '0', 'VERDE-M4-VO7581 MADRE. ROSADA                                                                                                                            PADRE. MARAÑON PATAS BLANCAS DE WOLFANG', 'Activo');
INSERT INTO `gallos` VALUES ('24', '140', null, 'PATA IZQUIERDA 1 MARCA TELA DEL LADO AFUERA NARIZ 2 MARCA', '04', 'Zambo', 'SAMBO', 'Roseta', '2024-04-17', 'Nueva', '0', 'VERDE -M4-VO 7574 MADRE. GALLINA PATAS VERDES HERMANA DEL PAVA                                                     PADRE. GIRO PICO MOCHO DUDAMEL COLON POR MADRE MANDY NAVARRO', 'Activo');
INSERT INTO `gallos` VALUES ('25', '144', null, 'PATA IZQUIERDA 1 MARCA DEL LADO ADENTRO 2 NARICES', '04', 'Zambo', 'SAMBO', 'Lisa', '2024-04-17', 'Nueva', '0', 'VERDE -M4-VO 7594 MADRE. GALLINA PATAS BLANCAS TERRORISTA DE ANDREW.                                                            PADRE. GIRO PICO MOCHO DUDAMEL COLON POR MADRE MANDY NAVARRO', 'Activo');
INSERT INTO `gallos` VALUES ('26', '136', null, 'PATA IZQUIERDA MARCA TELA ADENTRO', '04', 'Zambo', 'SAMBO', 'Roseta', '2024-04-17', 'Nueva', '0', 'VERDE -M4- VO7588 MADRE . MARAÑONA C. ROSA SUYA                                                                                     PADRE . MARAÑON PATAS BLANCAS DE WOLFANG', 'Activo');
INSERT INTO `gallos` VALUES ('27', '133', null, 'PATA IZQUIERDA 1 MARCA DEL LADO ADENTRO', '04', 'Marañon', 'MARAÑON', 'Lisa', '2024-04-17', 'Nueva', '0', 'VERDE-M4-VO7590 MADRE. MARAÑONA C. ROSA SUYA                                                                                   PADRE . MARAÑON PATAS BLANCAS DE WOLFANG', 'Activo');
INSERT INTO `gallos` VALUES ('28', '142', null, 'PATA DERECHA 2 TELA', '04', 'Zambo', 'SAMBO', 'Lisa', '2024-04-17', 'Nueva', '0', 'VERDE -M4-VO7585 MADRE. PINTA                                                                                                                                        PADRE. MARAÑON PATAS BLANCAS DE WOLFANG', 'Activo');
INSERT INTO `gallos` VALUES ('29', '123', null, 'PATA DERECHA 2 MARCA PATA IZQUIERDA 1 MARCA DEL LADO ADENTRO', 'NO', 'Marañon', 'MARAÑON', 'Roseta', '2024-04-17', 'Nueva', '0', 'VERDE MADRE. HERMANA DEL CIEGO                                                                                                                                  PADRE MARAÑON PATAS BLANCAS DE WOLFANG ( SIN MARCAR )', 'Activo');
INSERT INTO `gallos` VALUES ('30', '1315', null, 'PATA DERECHA 1 MARCA DEL LADO AFUERA PATA IZQUIERDA MARCA 1 TELA LADO AFUERA CANDADO 386', 'NO', 'Giro', 'GIRO', 'Roseta', '2024-04-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('31', '138', null, 'PAADENTRO TA IZQUIERDA 1 TELA LADO', '04', 'Camagüey', 'CANAGUEY', 'Lisa', '2024-04-17', 'Nueva', '0', 'VERDE -M4-VO7576 MADRE. MARAÑONA C.ROSA SUYA                                                                                   PADRE. MARAÑON PATAS BLANCAS DE WOLFANG', 'Activo');
INSERT INTO `gallos` VALUES ('32', '129', null, 'PATA DERECHA 1 MARCA DEL LADO ADENTRO', '6', 'Zambo', 'SAMBO', 'Roseta', '2024-01-17', 'Nueva', '0', 'VERDE MADRE. NEGRA MANDY NAVARRO                                                                                                                                    PADRE. MARAÑON PATAS BLANCAS DE WOLFANG ( ESTE NO ANOTE LA PLACA DEL MARCAJE )', 'Activo');
INSERT INTO `gallos` VALUES ('33', '1685', null, 'NO', 'NO', 'Marañon', 'MARAÑON', 'Lisa', '2024-07-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('34', '1807', null, 'NO', '01', 'Giro', null, 'Lisa', '2024-01-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('35', '1806', null, 'NO', '01', 'Giro', null, 'Lisa', '2024-01-14', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('36', '1811', null, 'TIENE MARCA 14 QUE ES DE DEDO DE LA PATA DERECHA Y LA PRIMERA TELA DE LA PATA IZQUIERDA', '01', 'Zambo', 'SAMBO', 'Lisa', '2024-01-17', 'Nueva', '0', 'MADRE. LA GALLINA CANDELA                                                                                                                              PADRE . COYANTE EL CHATARRERO', 'Activo');
INSERT INTO `gallos` VALUES ('37', '1805', null, 'NO', '01', 'Zambo', 'SAMBO', 'Lisa', '2024-01-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('38', '1255', null, 'NO', '01', 'Marañon', 'MARAÑON', 'Lisa', '2024-01-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('39', '41', null, 'NO', '12', 'Camagüey', 'CANAGUEY', 'Lisa', '2023-12-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('40', '42', null, 'NO', '12', 'Marañon', 'MARAÑON', 'Lisa', '2023-12-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('41', '199', null, 'NO', null, 'Marañon', 'MARAÑON', 'Lisa', '2024-07-17', 'Nueva', '1', null, 'Activo');
INSERT INTO `gallos` VALUES ('42', '146', null, 'NO', null, 'Zambo', 'SAMBO', 'Lisa', '2024-07-17', 'Nueva', '1', null, 'Activo');
INSERT INTO `gallos` VALUES ('43', '1240', null, 'PATA DERECHA TELA ADENTRO CANDADO 100', null, 'Zambo', 'SAMBO', 'Lisa', '2024-07-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('44', '1350', null, 'PATA DERECHA TELA ADENTRO CANDADO 65', null, 'Marañon', 'MARAÑON', 'Lisa', '2024-07-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('45', '1694', null, 'PATA IZQUIERDA TELA ADENTRO', '11', 'Giro', 'GIRO', 'Lisa', '2023-11-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('46', '150', null, 'PATA IZQUIERDA TELA AFUERA DOS NARICES', null, 'Zambo', 'SAMBO', 'Lisa', '2024-07-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('47', '1673', null, 'PATA IZQUIERDA TELA ADENTRO TELA AFUERA', '11', 'Zambo', 'SAMBO', 'Lisa', '2023-11-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('48', '1811', null, 'PATA IZQUIERDA TELA ADENTRO PATA DERECHA DEDO MOCHO', '01', 'Zambo', 'SAMBO', 'Lisa', '2024-01-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('49', '1806', null, 'PATA DERECHA TELA AFUERA NARIZ IZQUIERDA', '01', 'Giro', 'GIRO', 'Lisa', '2024-01-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('50', '1807', null, 'PATA IZQUIERDA TELA ADENTRO', '01', 'Giro', 'GIRO', 'Lisa', '2024-01-17', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('51', '1255', null, 'PATA IZQUIERDA TELA AFUERA', '01', 'Marañon', 'MARAÑON', 'Lisa', '2024-01-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('52', '1696', null, 'PATA IZQUIERDA TELA ADENTRO', '11', 'Giro', 'GIRO', 'Lisa', '2023-11-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('53', '1693', null, 'PATA DERECHA TELA AFUERA', '10', 'Zambo', 'SAMBO', 'Lisa', '2023-10-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('54', '1683', null, 'PATA DERECHA TELA ADENTRO PATA IZQUIERDA TELA AFUERA', '10', 'Zambo', 'SAMBO', 'Lisa', '2023-10-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('55', '(1659) (1271)', null, 'NO', '10', 'Pinto', 'PINTO', 'Lisa', '2024-07-18', 'Nueva', '3', null, 'Activo');
INSERT INTO `gallos` VALUES ('56', '1688', null, 'PATA IZQUIERDA TELA ADENTRO PATA DERECHA TELA AFUERA', '11', 'Camagüey', 'CANAGUEY', 'Lisa', '2023-11-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('57', '1680', null, 'PATA DERECHA TELA AFUERA', '10', 'Zambo', 'SAMBO', 'Lisa', '2023-10-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('58', '99', null, 'NO', null, 'Zambo', 'SAMBO', 'Lisa', '2024-07-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('59', '1687', null, 'PATA DERECHA TELA AFUERA PATA IZQUIERDA TELA ADENTRO', '11', 'Camagüey', 'CANAGUEY', 'Lisa', '2023-11-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('60', '141', null, 'PATA IZQUIERDA TELA AFUERA', '3', 'Zambo', 'SAMBO', 'Lisa', '2024-03-18', 'Nueva', '0', 'MADRE . ZAMBA GIRA DAVID NUÑEZ                                                                                                        \r\n        PADRE . CRESTON DAVINCHIN PATA ROJA', 'Activo');
INSERT INTO `gallos` VALUES ('61', '1699', null, 'PATA DERECHA TELA AFUERA NARIZ DERECHA', '11', 'Zambo', 'SAMBO', 'Lisa', '2023-11-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('62', '1677', null, 'PATA DERECHA TELA AFUERA', '10', 'Giro', 'GIRO', 'Lisa', '2023-10-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('63', '1202 CANDADO 242', null, 'NO', null, 'Giro', 'GIRO', 'Lisa', '2024-07-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('64', 'CANDADO 64', null, 'NO', '11', 'Marañon', 'MARAÑON', 'Lisa', '2024-07-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('65', '1257', null, 'PATA DERECHA TELA AFUERA', '01', 'Marañon', 'MARAÑON', 'Lisa', '2024-01-18', 'Nueva', '0', 'MADRE . CENIZA ESPAÑOLA ORIENTE PADRE. GALLO SAMBO DAVID NUÑEZ POR ESPAÑOL', 'Activo');
INSERT INTO `gallos` VALUES ('66', '118', null, 'PATA IZQUIERDA TELA ADENTRO TELA AFUERA', '3', 'Marañon', 'BULICO', 'Lisa', '2024-03-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('67', '1685', null, 'PATA DERECHA TELA AFUERA PATA IZQUIERDA TELA ADENTRO', '10', 'Marañon', 'MARAÑON', 'Lisa', '2023-10-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('68', '1281', null, 'PATA DERECHA TELA AFUERA PATA IZQUIERDA TELA AFUERA', '04', 'Zambo', 'SAMBO', 'Lisa', '2024-04-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('69', '1276', null, 'PATA DERECHA TELA ADENTRO PATA IZQUIERDA TELA AFUERA', '04', 'Zambo', 'SAMBO', 'Lisa', '2024-04-18', 'Nueva', '0', 'MADRE . LA MAQUINA ALBERTO URDANETA PADRE . CRESTON DAVINCHI PATA ROJA', 'Activo');
INSERT INTO `gallos` VALUES ('70', '1234', null, 'PATA IZQUIERDA TELA AFUERA', '04', 'Marañon', 'MARAÑON', 'Lisa', '2024-04-18', 'Nueva', '0', 'MADRE . CENIZA ESPAÑOLA ORIENTE  PADRE .  MARAÑON PATA QUEBRADA DAVID NUÑEZ POR DUDAMEL COLON', 'Activo');
INSERT INTO `gallos` VALUES ('71', '1254', null, 'PATA DERECHA TELA AFUERA', '01', 'Marañon', 'MARAÑON', 'Lisa', '2024-01-18', 'Nueva', '0', 'MADRE . POLLA MARAÑONA DE ORIENTE PARA PROBAR PADRE. SAMBO DAVID NUÑEZ POR ESPAÑOL', 'Activo');
INSERT INTO `gallos` VALUES ('72', '1378', null, 'NO', null, 'Zambo', 'SAMBO', 'Lisa', '2024-07-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('73', '1258', null, 'NO', '03', 'Marañon', 'MARAÑON', 'Lisa', '2024-03-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('74', 'CANDADO 80', null, 'NO', null, 'Zambo', 'SAMBO', 'Lisa', '2024-07-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('75', '1238', null, 'PATA IZQUIERDA TELA AFUERA', '04', 'Camagüey', 'CANAGUEY', 'Lisa', '2024-04-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('76', '1297', null, 'PATA DERECHA TELA ADENTRO NARIZ DERECHA', '04', 'Zambo', 'SAMBO', 'Lisa', '2024-04-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('77', '1805', null, 'PATA DERECHA TELA ADENTRO', '01', 'Zambo', 'SAMBO', 'Lisa', '2024-01-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('78', '1251', null, 'PATA DERECHA TELA ADENTRO', '03', 'Calica', 'CALICA', 'Lisa', '2024-03-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('79', '1803', null, 'PATA DERECHA TELA AFUERA', null, 'Zambo', 'SAMBO', 'Lisa', '2024-07-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('80', '1256', null, 'PATA DERECHA TELA ADENTRO', '01', 'Zambo', 'SAMBO', 'Lisa', '2024-01-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('81', '1377', null, 'PATA IZQUIERDA TELA AFUERA', null, 'Marañon', 'MARAÑON', 'Lisa', '2024-07-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('82', '1324', null, 'PATA IZQUIERDA TELA ADENTRO', null, 'Zambo', 'SAMBO', 'Lisa', '2024-07-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('83', '1252', null, 'PATA IZQUIERDA TELA ADENTRO', '03', 'Zambo', 'SAMBO', 'Lisa', '2024-03-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('84', '1279', null, 'PATA DERECHA TELA AFUERA PATA IZQUIERDA TELA AFUERA', '03', 'Zambo', 'SAMBO', 'Lisa', '2024-03-18', 'Nueva', '0', null, 'Activo');
INSERT INTO `gallos` VALUES ('85', '1817', null, 'NO', '05', 'Marañon', null, 'Roseta', '2024-04-01', 'Nueva', '0', 'ANGEL MARTINEZ POR GALLINA SALAZAR', 'Activo');

-- ----------------------------
-- Table structure for gallos_hijos
-- ----------------------------
DROP TABLE IF EXISTS `gallos_hijos`;
CREATE TABLE `gallos_hijos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `padre_id` int unsigned DEFAULT NULL,
  `madre_id` int unsigned DEFAULT NULL,
  `hijo_id` int unsigned DEFAULT NULL,
  `tipo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `padre_id` (`padre_id`),
  KEY `gallos_hijos_ibfk_2` (`hijo_id`),
  KEY `madre_id` (`madre_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallos_hijos
-- ----------------------------

-- ----------------------------
-- Table structure for gallos_imagenes
-- ----------------------------
DROP TABLE IF EXISTS `gallos_imagenes`;
CREATE TABLE `gallos_imagenes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `gallo_id` int unsigned NOT NULL,
  `imagen` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`),
  KEY `gallo_id` (`gallo_id`),
  CONSTRAINT `gallos_imagenes_ibfk_1` FOREIGN KEY (`gallo_id`) REFERENCES `gallos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of gallos_imagenes
-- ----------------------------
INSERT INTO `gallos_imagenes` VALUES ('10', '21', 'POLLO SAMBO PLACA 132.jpg');
INSERT INTO `gallos_imagenes` VALUES ('12', '23', 'POLLO SAMBO PLACA 134.jpg');
INSERT INTO `gallos_imagenes` VALUES ('13', '24', 'POLLO SAMBO PLACA 140.jpg');
INSERT INTO `gallos_imagenes` VALUES ('14', '25', 'POLLO SAMBO PLACA 144.jpg');
INSERT INTO `gallos_imagenes` VALUES ('15', '26', 'POLLO SAMBO PLACA 136.jpg');
INSERT INTO `gallos_imagenes` VALUES ('16', '27', 'POLLO MARAÑON PLACA 133.jpg');
INSERT INTO `gallos_imagenes` VALUES ('17', '28', 'POLLO SAMBO PLACA 142.jpg');
INSERT INTO `gallos_imagenes` VALUES ('18', '29', 'POLLO MARAÑON PLACA 123.jpg');
INSERT INTO `gallos_imagenes` VALUES ('19', '30', 'POLLO GIRO PLACA 1315.jpg');
INSERT INTO `gallos_imagenes` VALUES ('20', '31', 'POLLO CANAGUEY PLACA 138.jpg');
INSERT INTO `gallos_imagenes` VALUES ('21', '32', 'POLLO SAMBO PLACA 129.jpg');
INSERT INTO `gallos_imagenes` VALUES ('22', '33', 'POLLO MARAÑON 1685.jpg');
INSERT INTO `gallos_imagenes` VALUES ('23', '34', 'GALLO GIRO PLACA 1807.jpg');
INSERT INTO `gallos_imagenes` VALUES ('24', '35', 'GALLO GIRO PLACA 1806.jpg');
INSERT INTO `gallos_imagenes` VALUES ('25', '36', 'GALLO SAMBO PLACA 1811.jpg');
INSERT INTO `gallos_imagenes` VALUES ('26', '37', 'GALLO SAMBO PLACA 1805.jpg');
INSERT INTO `gallos_imagenes` VALUES ('27', '38', 'GALLO MARAÑON PLACA 1255.jpg');
INSERT INTO `gallos_imagenes` VALUES ('28', '39', 'GALLO CANAGUEY PLACA 41.jpg');
INSERT INTO `gallos_imagenes` VALUES ('29', '40', 'GALLO MARAÑON PLACA 42.jpg');
INSERT INTO `gallos_imagenes` VALUES ('30', '41', 'GALLO MARAÑON PLACA 199.jpg');
INSERT INTO `gallos_imagenes` VALUES ('31', '42', 'GALLO SAMBO PLACA146.jpg');
INSERT INTO `gallos_imagenes` VALUES ('32', '43', 'GALLO SAMBO PLACA 1240.jpg');
INSERT INTO `gallos_imagenes` VALUES ('33', '44', 'GALLO MARAÑON PLACA 1350.jpg');
INSERT INTO `gallos_imagenes` VALUES ('34', '45', 'GALLO GIRO PLACA 1694.jpg');
INSERT INTO `gallos_imagenes` VALUES ('35', '46', 'GALLO SAMBO PLACA 150.jpg');
INSERT INTO `gallos_imagenes` VALUES ('36', '47', 'GALLO SAMBO PLACA 1673.jpg');
INSERT INTO `gallos_imagenes` VALUES ('37', '48', 'GALLO SAMBO PLACA 1811.jpg');
INSERT INTO `gallos_imagenes` VALUES ('38', '49', 'GALLO GIRO PLACA 1806.jpg');
INSERT INTO `gallos_imagenes` VALUES ('39', '50', 'GALLO GIRO PLACA 1807.jpg');
INSERT INTO `gallos_imagenes` VALUES ('40', '51', 'GALLO MARAÑON PLACA 1255.jpg');
INSERT INTO `gallos_imagenes` VALUES ('41', '52', 'GALLO GIRO PLACA 1696.jpg');
INSERT INTO `gallos_imagenes` VALUES ('42', '53', 'GALLO SAMBO PLACA 1693.jpg');
INSERT INTO `gallos_imagenes` VALUES ('43', '54', 'GALLO SAMBO PLACA 1683.jpg');
INSERT INTO `gallos_imagenes` VALUES ('45', '56', 'GALLO CANAGUEY PLACA 1688.jpg');
INSERT INTO `gallos_imagenes` VALUES ('46', '57', 'GALLO SAMBO PLACA 1680.jpg');
INSERT INTO `gallos_imagenes` VALUES ('47', '55', 'GALLO PINTO PLACA (1659) (1271).jpg');
INSERT INTO `gallos_imagenes` VALUES ('48', '58', 'GALLO SAMBO PLACA 99.jpg');
INSERT INTO `gallos_imagenes` VALUES ('49', '59', 'GALLO CANAGUEY PLACA 1687.jpg');
INSERT INTO `gallos_imagenes` VALUES ('51', '61', 'GALLO SAMBO PLACA 1699.jpg');
INSERT INTO `gallos_imagenes` VALUES ('52', '62', 'GALLO GIRO PLACA 1677.jpg');
INSERT INTO `gallos_imagenes` VALUES ('53', '63', 'GALLO GIRO PLACA 1202 .jpg');
INSERT INTO `gallos_imagenes` VALUES ('56', '66', 'GALLO BULICO PLACA 118.jpg');
INSERT INTO `gallos_imagenes` VALUES ('57', '64', 'GALLO MARAÑON CANDADO 64.jpg');
INSERT INTO `gallos_imagenes` VALUES ('58', '67', 'GALLO MARAÑON PLACA 1685.jpg');
INSERT INTO `gallos_imagenes` VALUES ('59', '68', 'GALLO SAMBO PLACA 1281.jpg');
INSERT INTO `gallos_imagenes` VALUES ('63', '72', 'GALLO SAMBO PLACA 1378.jpg');
INSERT INTO `gallos_imagenes` VALUES ('64', '73', 'GALLO MARAÑON PLACA 1258.jpg');
INSERT INTO `gallos_imagenes` VALUES ('65', '74', 'GALLO SAMBO CANDADO 80.jpg');
INSERT INTO `gallos_imagenes` VALUES ('66', '75', 'GALLO CANAGUEY PLACA 1238.jpg');
INSERT INTO `gallos_imagenes` VALUES ('67', '76', 'GALLO SAMBO PLACA 1279.jpg');
INSERT INTO `gallos_imagenes` VALUES ('68', '77', 'GALLO SAMBO PLACA 1805.jpg');
INSERT INTO `gallos_imagenes` VALUES ('69', '78', 'GALLO CALICA PLACA 1251.jpg');
INSERT INTO `gallos_imagenes` VALUES ('70', '79', 'GALLO SAMBO PLACA 1803.jpg');
INSERT INTO `gallos_imagenes` VALUES ('71', '80', 'GALLO SAMBO PLACA 1256.jpg');
INSERT INTO `gallos_imagenes` VALUES ('72', '81', 'GALLO MARAÑON PLACA 1377.jpg');
INSERT INTO `gallos_imagenes` VALUES ('73', '82', 'GALLO SAMBO PLACA 1324.jpg');
INSERT INTO `gallos_imagenes` VALUES ('74', '83', 'GALLO SAMBO PLACA 1252.jpg');
INSERT INTO `gallos_imagenes` VALUES ('75', '84', 'GALLO SAMBO PLACA 1279.jpg');
INSERT INTO `gallos_imagenes` VALUES ('77', '85', 'PLACA 1817 VENDIDO Y ACTUALIZACION DE FOTO.jpg');
INSERT INTO `gallos_imagenes` VALUES ('78', '60', 'PLACA141 VENDIDO Y ACTUALIZACION DE FOTO.jpg');
INSERT INTO `gallos_imagenes` VALUES ('79', '70', 'PLACA 1234 VENDIDO Y ACTUALIZACION DE FOTO.jpg');
INSERT INTO `gallos_imagenes` VALUES ('80', '69', 'PLACA 1276 VENDIDO Y ACTUALIZACION DE FOTO.jpg');
INSERT INTO `gallos_imagenes` VALUES ('81', '22', 'PLACA 145 VENDIDO Y ACTUALIZACION DE FOTO .jpg');
INSERT INTO `gallos_imagenes` VALUES ('82', '71', 'PLACA 1254 VENDIDO Y ACTUALIZACION DE FOTO .jpg');
INSERT INTO `gallos_imagenes` VALUES ('83', '65', 'PLACA 1257 VENDIDO Y ACTUALIZACION DE FOTO.jpg');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
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
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
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
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'Luis Gómez', 'admin@mail.com', null, '$2y$12$bhirqdMp66KFmIQ3xzsUVOltDVKmV7sce9/zH1KJ96yKgfU/jIKkm', null, '2024-07-04 02:41:25', '2024-07-04 02:41:25');

-- ----------------------------
-- Table structure for ventas
-- ----------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `gallo_id` int unsigned DEFAULT NULL,
  `cliente_id` int unsigned DEFAULT NULL,
  `monto` decimal(10,0) DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_general_ci,
  `estatus` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `update_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  KEY `gallo_id` (`gallo_id`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`gallo_id`) REFERENCES `gallos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of ventas
-- ----------------------------
