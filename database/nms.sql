DROP DATABASE IF EXISTS `nms`;
CREATE DATABASE `nms`;
USE `nms`;

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin`
(
    `id`         int          NOT NULL AUTO_INCREMENT,
    `name`       varchar(255) NOT NULL,
    `image`      Longtext     NOT NULL DEFAULT '',
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
    `image`       LONGTEXT     NOT NULL DEFAULT '',
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
    `msg`   longtext     NOT NULL,
    `image` longtext     NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `offers`;
CREATE TABLE IF NOT EXISTS `offers`
(
    `id`             int          NOT NULL AUTO_INCREMENT,
    `name`           varchar(255) NOT NULL,
    `description`    varchar(255)          DEFAULT '',
    `image`          longtext     NOT NULL DEFAULT '',
    `price`          varchar(255) NULL,
    `discount`       varchar(255) NOT NULL,
    `discount_price` varchar(255) NOT NULL,
    `start_date`     varchar(255) NOT NULL,
    `end_date`       varchar(255) NOT NULL,
    `status`         varchar(255) NOT NULL DEFAULT 'active',
    `category_id`    int          NOT NULL,
    `brand_name`     varchar(255) NOT NULL DEFAULT 'Nilkanth Medical Store',
    `code`           varchar(255) NOT NULL,
    `created_at`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     timestamp    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `name`        text         NOT NULL,
    `description` text         NOT NULL,
    `price`       varchar(255) NOT NULL DEFAULT '0',
    `mrp`         varchar(100) NOT NULL DEFAULT '0',
    `discount`    varchar(100) NOT NULL DEFAULT '0',
    `brand_name`  varchar(500) NOT NULL DEFAULT 'Nilkanth Medical',
    `expiry_date` date         NOT NULL,
    `thumbnail`   longtext     NOT NULL DEFAULT '',
    `images`      json         NOT NULL DEFAULT '',
    `ingredients` text         NOT NULL,
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
    `street`     varchar(500) NOT NULL DEFAULT 'Nilkanth Medical',
    `area`       date         NOT NULL,
    `pincode`    longtext     NOT NULL,
    `pdf`        longtext     NOT NULL,
    `total`      text         NOT NULL,
    `status`     varchar(100) NOT NULL DEFAULT 'pending',
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
    `city`         date         NOT NULL,
    `state`        longtext     NOT NULL,
    `pincode`      longtext     NOT NULL,
    `product_name` text         NOT NULL,
    `brand_name`   text         NOT NULL,
    `quantity`     text         NOT NULL,
    `notes`        text         NOT NULL,
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
    `id`         int        NOT NULL AUTO_INCREMENT,
    `uid`        int        NOT NULL,
    `pid`        int        NOT NULL,
    `name`       text       NOT NULL,
    `msg`        mediumtext NOT NULL,
    `rating`     int                 DEFAULT '0',
    `created_at` timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user`
(
    `userid`            bigint UNSIGNED                                               NOT NULL AUTO_INCREMENT,
    `full_name`         varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `address`           varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `city`              varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `state`             varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `zip`               varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `phone`             varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `username`          varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `status`            varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
    `email`             varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `mail_hash`         varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `email_verified_at` timestamp                                                     NULL     DEFAULT NULL,
    `password`          varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `social_id`         varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci          DEFAULT NULL,
    `image`             LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci              DEFAULT NULL,
    `created_at`        timestamp                                                     NULL     DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        timestamp                                                     NULL     DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`userid`),
    UNIQUE KEY `users_username_unique` (`username`),
    UNIQUE KEY `users_email_unique` (`email`),
    UNIQUE KEY `users_mail_hash_unique` (`mail_hash`)
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
    `id`         int       NOT NULL AUTO_INCREMENT,
    `question`   text      NOT NULL,
    `answer`     text      NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);
