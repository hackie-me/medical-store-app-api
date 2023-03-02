DROP DATABASE IF EXISTS `nms`;
CREATE DATABASE `nms`;
USE `nms`;

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) NOT NULL,
    `image`      varchar(255) NOT NULL DEFAULT '',
    `phone`      varchar(11)  NOT NULL,
    `email`      varchar(255) NOT NULL,
    `password`   varchar(255) NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `name`        varchar(255) NOT NULL,
    `image`       varchar(255) NOT NULL DEFAULT '',
    `description` varchar(255) NULL,
    `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);


DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact`
(
    `id`    int          NOT NULL AUTO_INCREMENT,
    `name`  varchar(255) NOT NULL,
    `phone` varchar(11)  NOT NULL,
    `msg`   varchar(500) NOT NULL DEFAULT '',
    `image` varchar(255) NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `offers`;
CREATE TABLE IF NOT EXISTS `offers`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `name`        varchar(255) NOT NULL,
    `description` varchar(255)          DEFAULT '',
    `image`       varchar(255) NOT NULL DEFAULT '',
    `price`       varchar(255) NULL,
    `discount`    varchar(255) NOT NULL,
    `start_date`  varchar(255) NOT NULL,
    `end_date`    varchar(255) NOT NULL,
    `status`      varchar(255) NOT NULL DEFAULT 'active',
    `code`        varchar(255) NOT NULL,
    `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `name`        varchar(255) NOT NULL DEFAULT '',
    `description` varchar(255) NOT NULL DEFAULT '',
    `price`       varchar(255) NOT NULL DEFAULT '0',
    `mrp`         varchar(100) NOT NULL DEFAULT '0',
    `discount`    varchar(100) NOT NULL DEFAULT '0',
    `brand_name`  varchar(500) NOT NULL DEFAULT 'Nilkanth Medical',
    `expiry_date` date         NOT NULL,
    `thumbnail`   varchar(255) NOT NULL DEFAULT '',
    `images`      json         NOT NULL,
    `ingredients` varchar(255) NOT NULL DEFAULT '',
    `status`      varchar(255) NOT NULL DEFAULT 'active',
    `unit`        varchar(255) NOT NULL DEFAULT 'Piece',
    `stock`       varchar(255) NOT NULL DEFAULT '1',
    `category_id` int          NOT NULL,
    `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `uid`        varchar(255) NOT NULL DEFAULT '0',
    `pid`        varchar(100) NOT NULL DEFAULT '0',
    `note`       varchar(100) NOT NULL DEFAULT '0',
    `quantity`   varchar(100) NOT NULL,
    `address`    varchar(500) NOT NULL DEFAULT 'Nilkanth Medical',
    `pdf`        varchar(255) NOT NULL DEFAULT '',
    `total`      varchar(100) NOT NULL DEFAULT '',
    `status`     varchar(100) NOT NULL DEFAULT 'pending', # pending, processing, delivered, cancelled
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `custom_order`;
CREATE TABLE IF NOT EXISTS `custom_order`
(
    `id`           int          NOT NULL AUTO_INCREMENT,
    `uid`          varchar(255) NOT NULL DEFAULT '0',
    `name`         varchar(100) NOT NULL DEFAULT '0',
    `phone`        varchar(100) NOT NULL DEFAULT '0',
    `email`        varchar(100) NOT NULL,
    `address`      varchar(500) NOT NULL DEFAULT 'Nilkanth Medical',
    `city`         varchar(100) NOT NULL,
    `state`        varchar(100) NOT NULL DEFAULT '',
    `pincode`      varchar(100) NOT NULL DEFAULT '',
    `product_name` varchar(100) NOT NULL DEFAULT '',
    `brand_name`   varchar(100) NOT NULL DEFAULT '',
    `quantity`     varchar(100) NOT NULL DEFAULT '',
    `notes`        varchar(999) NOT NULL DEFAULT '',
    `status`       varchar(100) NOT NULL DEFAULT 'pending',
    `created_at`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`   timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

# Cart Table
DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `uid`        varchar(255) NOT NULL,
    `pid`        varchar(100) NOT NULL,
    `quantity`   varchar(100) NOT NULL DEFAULT '1',
    `price`      varchar(100) NOT NULL DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `review`;
CREATE TABLE IF NOT EXISTS `review`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `uid`        int          NOT NULL,
    `pid`        int          NOT NULL,
    `name`       varchar(255) NOT NULL DEFAULT '',
    `msg`        varchar(255) NOT NULL DEFAULT '',
    `rating`     int                   DEFAULT '0',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user`
(
    `userid`     bigint UNSIGNED NOT NULL AUTO_INCREMENT,
    `full_name`  varchar(191)    NOT NULL,
    `address`    varchar(191)             DEFAULT NULL,
    `phone`      varchar(191)    NOT NULL,
    `status`     varchar(191)    NOT NULL DEFAULT 'pending',
    `email`      varchar(191)             DEFAULT NULL,
    `password`   varchar(191)             DEFAULT NULL,
    `image`      varchar(255)    NOT NULL DEFAULT '',
    `created_at` timestamp       NULL     DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp       NULL     DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`userid`),
    UNIQUE KEY `users_email_unique` (`email`)
);

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `uid`        int          NOT NULL,
    `address`    varchar(255) NOT NULL,
    `city`       varchar(255) NOT NULL,
    `state`      varchar(255) NOT NULL,
    `pincode`    varchar(255) NOT NULL,
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `faq`;
CREATE TABLE IF NOT EXISTS `faq`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `question`   varchar(255) NOT NULL DEFAULT '',
    `answer`     varchar(255) NOT NULL DEFAULT '',
    `created_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

# Notification Table
DROP TABLE IF EXISTS `notification`;
CREATE TABLE IF NOT EXISTS `notification`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `title`       varchar(100) NOT NULL,
    `description` varchar(100) NOT NULL,
    `created_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);
