CREATE TABLE IF NOT EXISTS `migan_recette` (
	`recette_id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`description` TEXT NULL DEFAULT NULL,
	`meta_title` VARCHAR(50) NOT NULL DEFAULT '0',
	`meta_description` TEXT NULL DEFAULT NULL,
	`meta_keyword` VARCHAR(50) NOT NULL DEFAULT '0',
	`image` VARCHAR(250) NOT NULL DEFAULT '0',
	`date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_modified` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
	`difficulte` INT NOT NULL,
	`preparation` INT NOT NULL,
	`cuisson` INT NOT NULL,
	`nb_personne` INT NOT NULL,
	`enavant` INT NOT NULL,
	`status` INT NOT NULL,
	`sort_order` INT NOT NULL,
	PRIMARY KEY (`recette_id`)
)
COLLATE='latin1_swedish_ci'
;
CREATE TABLE IF NOT EXISTS `miganoc`.`migan_recette_image` (
	`product_image_id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11) NOT NULL,
	`image` VARCHAR(255) NULL DEFAULT NULL,
	`sort_order` INT(3) NOT NULL DEFAULT '0',
	PRIMARY KEY (`product_image_id`),
	INDEX `product_id` (`product_id`)
)
 COLLATE 'utf8_general_ci' ENGINE=MyISAM ROW_FORMAT=Dynamic AUTO_INCREMENT=2352;
ALTER TABLE `migan_recette_image`
	ALTER `product_id` DROP DEFAULT;
ALTER TABLE `migan_recette_image`
	CHANGE COLUMN `product_image_id` `recette_image_id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `product_id` `recette_id` INT(11) NOT NULL AFTER `recette_image_id`;

CREATE TABLE IF NOT EXISTS `migan_recette_product` (
	`recette_id` INT NOT NULL,
	`product_id` INT NOT NULL,
	PRIMARY KEY (`recette_id`, `product_id`)
)
COLLATE='latin1_swedish_ci'
;

ALTER TABLE `migan_product` CHANGE `date_available` `date_available` DATE NULL DEFAULT NULL;
ALTER TABLE `migan_product` CHANGE `price` `price` DECIMAL(15,2) NOT NULL DEFAULT '0.0000';
ALTER TABLE `migan_product`
ADD COLUMN `price_pro` DECIMAL(15,2) NOT NULL DEFAULT '0.00' AFTER `price`;

CREATE TABLE IF NOT EXISTS `migan_order_goodie` (
	`goodie_id` int(11) NOT NULL,
	`order_id` int(11) NOT NULL,
	KEY `goodie_id` (`goodie_id`),
	KEY `order_id` (`order_id`)
)

CREATE TABLE IF NOT EXISTS `migan_customer_stock` (
	`customer_id` INT(11) NOT NULL ,
	`product_id` INT(11) NOT NULL ,
	`stock_client` INT(2) NOT NULL ,
	`stock_reference` INT(2) NOT NULL,
	KEY `customer_id` (`customer_id`),
	KEY `product_id` (`product_id`)
)


/* 10012018 FORTUNE Steffy */

CREATE TABLE IF NOT EXISTS `migan_prestation` (
	`prestation_id` INT NOT NULL AUTO_INCREMENT,
	`prix_horaire` DECIMAL(10,2) NOT NULL,
	`duree_de_base` TIME NOT NULL,
	`titre` VARCHAR(50) NOT NULL,
	`description` TEXT NULL,
	PRIMARY KEY (`prestation_id`)
)
COLLATE='latin1_swedish_ci'
;

CREATE TABLE IF NOT EXISTS `migan_prestation_realisee` (
	`prestation_id` INT NULL,
	`manufacturer_id` INT NULL,
	`customer_id` INT NULL,
	`duree_reelle` TIME NULL,
	`date` DATE NULL
)
COLLATE='latin1_swedish_ci'
;

ALTER TABLE `migan_manufacturer`
	ADD COLUMN `type` INT(3) NOT NULL AFTER `sort_order`,
	ADD COLUMN `adresse1` VARCHAR(255) NOT NULL AFTER `type`,
	ADD COLUMN `adresse2` VARCHAR(255) NOT NULL AFTER `adresse1`,
	ADD COLUMN `code_postal` VARCHAR(50) NOT NULL AFTER `adresse2`,
	ADD COLUMN `country_id` INT NOT NULL AFTER `code_postal`,
	ADD COLUMN `zone_id` INT NOT NULL AFTER `country_id`,
	ADD COLUMN `firstname` VARCHAR(50) NOT NULL AFTER `zone_id`,
	ADD COLUMN `lastname` VARCHAR(50) NOT NULL AFTER `firstname`,
	ADD COLUMN `SIRET` VARCHAR(50) NOT NULL AFTER `lastname`,
	ADD COLUMN `email` VARCHAR(50) NOT NULL AFTER `SIRET`,
	ADD COLUMN `telephone` VARCHAR(50) NOT NULL AFTER `email`;


-- REGNA Lovely 10/01/2018 --
-- Permet de faire des tests pour les commandes de produits --
UPDATE migan_product SET quantity = 50

-- REGNA Lovely 11/01/2018 --
-- Liste les différents types de commande --
CREATE TABLE IF NOT EXISTS `migan_order_type` (
	`order_type_id` INT(11) NOT NULL AUTO_INCREMENT,
	`order_type_name` VARCHAR(255) NOT NULL ,
	PRIMARY KEY (`order_type_id`)
);

INSERT INTO `migan_order_type` (`order_type_id`, `order_type_name`) VALUES (1, 'Cliente');
INSERT INTO `migan_order_type` (`order_type_id`, `order_type_name`) VALUES (2, 'Grande et Moyenne surface');

-- Permet de distinguer les commandes clientes des commandes grandes et moyennes surfaces --
ALTER TABLE `migan_order` ADD `order_type_id` INT NOT NULL AFTER `date_modified`;

-- affecte la valeur 1 [commande de type cliente] aux commandes passées --
UPDATE `migan_order` SET `order_type_id` = 1 ;

ALTER TABLE `migan_order_total` CHANGE `value` `value` DECIMAL(15,2) NOT NULL DEFAULT '0.00';