-- Insert data into `users`
INSERT INTO `users` (`username`, `email`, `phone`, `password`)
VALUES 
    ('user1', 'user1@example.com', '1234567890', 'hashed_password1'),
    ('user2', 'user2@example.com', '0987654321', 'hashed_password2'),
    ('admin1', 'admin1@example.com', '1122334455', 'hashed_password3'),
    ('partner1', 'partner1@example.com', '6677889900', 'hashed_password4'),
    ('member1', 'member1@example.com', '4455667788', 'hashed_password5');

-- Insert data into `admins`
INSERT INTO `admins` (`id`, `first_name`, `last_name`)
VALUES 
    (3, 'Admin', 'One');

-- Insert data into `card_types`
INSERT INTO `card_types` (`type`, `fee`)
VALUES 
    ('Gold', 50.00),
    ('Silver', 30.00),
    ('Bronze', 20.00);

-- Insert data into `cards`
INSERT INTO `cards` (`user_id`, `card_type_id`, `qrcode_image_url`, `expiration_date`)
VALUES 
    (1, 1, 'https://example.com/qrcode1.png', '2025-12-31 23:59:59'),
    (2, 2, 'https://example.com/qrcode2.png', '2025-12-31 23:59:59');

-- Insert data into `members`
INSERT INTO `members` (`id`, `first_name`, `last_name`, `birth_date`, `member_image_url`, `identity_image_url`, `is_active`)
VALUES 
    (5, 'Member', 'One', '2000-01-01', 'https://example.com/member1.png', 'https://example.com/identity1.png', TRUE);

-- Insert data into `partner_categories`
INSERT INTO `partner_categories` (`category`)
VALUES 
    ('Hotels'),
    ('Clinics'),
    ('Schools');

-- Insert data into `partners`
INSERT INTO `partners` (`id`, `name`, `description`, `partner_category_id`, `address`)
VALUES 
    (4, 'Partner One', 'Partner Description', 1, '123 Main St');

-- Insert data into `discount_offers`
INSERT INTO `discount_offers` (`partner_id`, `card_type_id`, `percentage`)
VALUES 
    (4, 1, 15.00),
    (4, 2, 10.00);

-- Insert data into `limited_discounts`
INSERT INTO `limited_discounts` (`partner_id`, `card_type_id`, `percentage`, `start_date`, `end_date`)
VALUES 
    (4, 3, 5.00, '2024-01-01 00:00:00', '2024-01-31 23:59:59');

-- Insert data into `discounts`
INSERT INTO `discounts` (`partner_id`, `user_id`, `amount`, `date`)
VALUES 
    (4, 1, 10.00, '2024-01-15 12:00:00');

-- Insert data into `news`
INSERT INTO `news` (`title`, `description`, `image_url`)
VALUES 
    ('Exciting News', 'Details about the news.', 'https://example.com/news1.png');

-- Insert data into `activities`
INSERT INTO `activities` (`title`, `description`, `image_url`)
VALUES 
    ('Community Event', 'Details about the event.', 'https://example.com/activity1.png');

-- Insert data into `volunteerings`
INSERT INTO `volunteerings` (`user_id`, `activity_id`)
VALUES 
    (1, 1);

-- Insert data into `payments`
INSERT INTO `payments` (`user_id`, `receipt_image_url`, `amount`, `type`)
VALUES 
    (1, 'https://example.com/receipt1.png', 100.00, 'donation');

-- Insert data into `help_types`
INSERT INTO `help_types` (`type`, `attachments_description`)
VALUES 
    ('Technical Support', 'Attach screenshots of the issue.');

-- Insert data into `helps`
INSERT INTO `helps` (`user_id`, `help_type_id`, `description`, `attachments_url`, `is_valid`)
VALUES 
    (1, 1, 'I need help with my account.', 'https://example.com/help_attachment1.png', TRUE);

-- Insert data into `suggestions`
INSERT INTO `suggestions` (`user_id`, `title`, `description`)
VALUES 
    (1, 'New Feature Suggestion', 'Details about the feature.');

-- Insert data into `feedbacks`
INSERT INTO `feedbacks` (`user_id`, `partner_id`, `title`, `description`)
VALUES 
    (1, 4, 'Great Partner', 'Details about the feedback.');

-- Insert data into `notifications`
INSERT INTO `notifications` (`user_id`, `title`, `description`, `url`, `reminder`)
VALUES 
    (1, 'Reminder', 'Details about the reminder.', 'https://example.com/reminder1', '2024-01-20 09:00:00');
