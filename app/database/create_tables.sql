CREATE TABLE `admins` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`first_name`						VARCHAR(255)			NOT NULL,
	`last_name`							VARCHAR(255)			NOT NULL,
	`username`							VARCHAR(255)			NOT NULL UNIQUE,
	`email`								VARCHAR(255)			NOT NULL UNIQUE,
	`password`							VARCHAR(255)			NOT NULL,
	`created_at`						DATETIME				NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `users` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`username`							VARCHAR(255)			NOT NULL UNIQUE,
	`email`								VARCHAR(255)			NOT NULL UNIQUE,
	`phone`								VARCHAR(20)				NOT NULL UNIQUE,
	`password`							VARCHAR(255)			NOT NULL,
	`created_at`						DATETIME				NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `card_types` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`type`								VARCHAR(255)			NOT NULL UNIQUE,
	`fee`								DECIMAL(10, 2)			NOT NULL
);


CREATE TABLE `cards` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`user_id`							BIGINT					NOT NULL,
	`card_type_id`						BIGINT					NOT NULL,
	`qrcode_image_url`					VARCHAR(255)			NOT NULL,
	`expiration_date`					DATETIME,

	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`card_type_id`) REFERENCES `card_types` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `members` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`first_name`						VARCHAR(255)			NOT NULL,
	`last_name`							VARCHAR(255)			NOT NULL,
	`birth_date`						DATE					NOT NULL,
	`member_image_url`					VARCHAR(255)			NOT NULL,
	`identity_image_url`				VARCHAR(255)			NOT NULL,
	`is_active`							BOOLEAN					NOT NULL DEFAULT FALSE
);


CREATE TABLE `partner_categories` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`category`							VARCHAR(255)			NOT NULL UNIQUE
);


CREATE TABLE `partners` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`name`								VARCHAR(255)			NOT NULL,
	`description`						LONGTEXT,
	`partner_category_id`				BIGINT					NOT NULL,
	`address`							VARCHAR(255)			NOT NULL,

	FOREIGN KEY (`partner_category_id`) REFERENCES `partner_categories` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `discount_offers` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`partner_id`						BIGINT					NOT NULL,
	`card_type_id`						BIGINT					NOT NULL,
	`percentage`						DECIMAL(10, 2)			NOT NULL,

	FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`card_type_id`) REFERENCES `card_types` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `limited_discounts` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`partner_id`						BIGINT					NOT NULL,
	`card_type_id`						BIGINT					NOT NULL,
	`percentage`						DECIMAL(10, 2)			NOT NULL,
	`start_date`						DATETIME				NOT NULL,
	`end_date`							DATETIME				NOT NULL,

	FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`card_type_id`) REFERENCES `card_types` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `discounts` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`partner_id`						BIGINT					NOT NULL,
	`user_id`							BIGINT					NOT NULL,
	`amount`							DECIMAL(10, 2)			NOT NULL,

	FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `news` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`title`								VARCHAR(255)			NOT NULL,
	`description`						LONGTEXT				NOT NULL,
	`image_url`							VARCHAR(255),
	`date`								DATETIME				NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `activities` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`title`								VARCHAR(255)			NOT NULL,
	`description`						LONGTEXT				NOT NULL,
	`image_url`							VARCHAR(255),
	`date`								DATETIME				NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE `volunteerings` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`user_id`							BIGINT					NOT NULL,
	`activity_id`						BIGINT					NOT NULL,

	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `payments` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`user_id`							BIGINT					NOT NULL,
	`receipt_image_url`					VARCHAR(255)			NOT NULL,
	`date`								DATETIME				NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`amount`							DECIMAL(10, 2)			NOT NULL,
	`type`								ENUM('donations', 'registration_fee'),

	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `help_types` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`type`								VARCHAR(255)			NOT NULL UNIQUE,
	`attachments_description`			LONGTEXT				NOT NULL
);


CREATE TABLE `helps` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`user_id`							BIGINT					NOT NULL,
	`help_type_id`						BIGINT					NOT NULL,
	`description`						LONGTEXT				NOT NULL,
	`attachments_url`					VARCHAR(255)			NOT NULL,
	`date`								DATETIME				NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`is_valid`							BOOLEAN					NOT NULL DEFAULT FALSE,

	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`help_type_id`) REFERENCES `help_types` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `suggestions` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`user_id`							BIGINT					NOT NULL,
	`title`								VARCHAR(255)			NOT NULL,
	`description`						LONGTEXT,

	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `feedbacks` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`user_id`							BIGINT					NOT NULL,
	`partner_id`						BIGINT					NOT NULL,
	`title`								VARCHAR(255)			NOT NULL,
	`description`						LONGTEXT,

	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);


CREATE TABLE `notifications` (
	`id`								BIGINT					PRIMARY KEY AUTO_INCREMENT,
	`user_id`							BIGINT					NOT NULL,
	`title`								VARCHAR(255)			NOT NULL,
	`description`						LONGTEXT,
	`url`								VARCHAR(255),
	`date`								DATETIME				NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`reminder`							DATE,

	FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);
