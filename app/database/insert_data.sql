-- Insert card types first
INSERT INTO `card_types` (`id`, `type`, `fee`) VALUES
(1, 'Basic', 50.00),
(2, 'Silver', 100.00),
(3, 'Gold', 200.00),
(4, 'Platinum', 500.00);

-- Insert base users and their corresponding roles
-- Admin users
INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `created_at`) VALUES
(1, 'admin1', 'admin1@example.com', '+1234567890', 'admin1', '2024-01-01 10:00:00'),
(2, 'admin2', 'admin2@example.com', '+1234567891', 'admin2', '2024-01-02 11:00:00');

INSERT INTO `admins` (`id`, `first_name`, `last_name`) VALUES
(1, 'John', 'Doe'),
(2, 'Jane', 'Smith');

-- Partner users
INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `created_at`) VALUES
(3, 'partner1', 'partner1@restaurant.com', '+1234567892', 'partner1', '2024-01-03 12:00:00'),
(4, 'partner2', 'partner2@hotel.com', '+1234567893', 'partner2', '2024-01-04 13:00:00'),
(5, 'partner3', 'partner3@store.com', '+1234567894', 'partner3', '2024-01-05 14:00:00');

INSERT INTO `partners` (`id`, `name`, `description`, `category`, `address`) VALUES
(3, 'Gourmet Restaurant', 'Fine dining establishment', 'Restaurant', '123 Food St, Cuisine City'),
(4, 'Luxury Hotel', 'Five-star accommodation', 'Hotel', '456 Stay Ave, Resort Town'),
(5, 'Fashion Boutique', 'High-end fashion retail', 'Retail', '789 Shop Blvd, Fashion District');

-- Member users
INSERT INTO `users` (`id`, `username`, `email`, `phone`, `password`, `created_at`) VALUES
(6, 'member1', 'member1@example.com', '+1234567895', 'member1', '2024-01-06 15:00:00'),
(7, 'member2', 'member2@example.com', '+1234567896', 'member2', '2024-01-07 16:00:00'),
(8, 'member3', 'member3@example.com', '+1234567897', 'member3', '2024-01-08 17:00:00');

INSERT INTO `members` (`id`, `first_name`, `last_name`, `birth_date`, `member_image_url`, `identity_image_url`, `is_active`) VALUES
(6, 'Alice', 'Johnson', '1990-05-15', 'https://example.com/members/alice.jpg', 'https://example.com/ids/alice.jpg', TRUE),
(7, 'Bob', 'Williams', '1985-08-22', 'https://example.com/members/bob.jpg', 'https://example.com/ids/bob.jpg', TRUE),
(8, 'Carol', 'Brown', '1992-03-30', 'https://example.com/members/carol.jpg', 'https://example.com/ids/carol.jpg', FALSE);

-- Create cards for members
INSERT INTO `cards` (`id`, `user_id`, `card_type_id`, `qrcode_image_url`, `expiration_date`) VALUES
(1, 6, 1, 'https://example.com/qrcodes/member1.png', '2025-01-06 15:00:00'),
(2, 7, 2, 'https://example.com/qrcodes/member2.png', '2025-01-07 16:00:00'),
(3, 8, 3, 'https://example.com/qrcodes/member3.png', '2025-01-08 17:00:00');

-- Create discount offers for partners
INSERT INTO `discount_offers` (`id`, `partner_id`, `card_type_id`, `percentage`) VALUES
(1, 3, 1, 5.00),
(2, 3, 2, 10.00),
(3, 4, 2, 15.00),
(4, 5, 3, 20.00);

-- Create limited time discounts
INSERT INTO `limited_discounts` (`id`, `partner_id`, `card_type_id`, `percentage`, `start_date`, `end_date`) VALUES
(1, 3, 1, 15.00, '2024-12-01 00:00:00', '2024-12-31 23:59:59'),
(2, 4, 2, 25.00, '2024-12-15 00:00:00', '2025-01-15 23:59:59');

-- Record some discounts used by members
INSERT INTO `discounts` (`id`, `partner_id`, `user_id`, `amount`, `date`) VALUES
(1, 3, 6, 25.00, '2024-01-10 14:30:00'),
(2, 4, 7, 50.00, '2024-01-11 18:45:00'),
(3, 5, 8, 75.00, '2024-01-12 20:15:00');

-- Create some news
INSERT INTO `news` (`id`, `title`, `description`, `image_url`, `date`) VALUES
(1, 'New Partnership Announcement', 'We are excited to announce our new partnership with Luxury Hotel', 'https://example.com/news/partnership.jpg', '2024-01-15 09:00:00'),
(2, 'Annual Member Meeting', 'Join us for our annual member meeting next month', 'https://example.com/news/meeting.jpg', '2024-01-20 10:00:00');

-- Create some activities
INSERT INTO `activities` (`id`, `title`, `description`, `image_url`, `date`) VALUES
(1, 'Beach Cleanup', 'Join us for our monthly beach cleanup activity', 'https://example.com/activities/beach.jpg', '2024-02-01 08:00:00'),
(2, 'Food Drive', 'Help us collect food for local food banks', 'https://example.com/activities/food.jpg', '2024-02-15 09:00:00');

-- Record some volunteering
INSERT INTO `volunteerings` (`id`, `user_id`, `activity_id`) VALUES
(1, 6, 1),
(2, 7, 1),
(3, 8, 2);

-- Record some payments
INSERT INTO `payments` (`id`, `user_id`, `receipt_image_url`, `date`, `amount`, `type`) VALUES
(1, 6, 'https://example.com/receipts/payment1.jpg', '2024-01-06 15:00:00', 50.00, 'registration_fee'),
(2, 7, 'https://example.com/receipts/payment2.jpg', '2024-01-07 16:00:00', 100.00, 'donation'),
(3, 8, 'https://example.com/receipts/payment3.jpg', '2024-01-08 17:00:00', 200.00, 'registration_fee');

-- Create help types
INSERT INTO `help_types` (`id`, `type`, `attachments_description`) VALUES
(1, 'Financial Aid', 'Please attach proof of income and expenses'),
(2, 'Medical Assistance', 'Please attach medical records and bills'),
(3, 'Educational Support', 'Please attach academic records and cost estimates');

-- Record some help requests
INSERT INTO `helps` (`id`, `user_id`, `help_type_id`, `description`, `attachments_url`, `date`, `is_valid`) VALUES
(1, 6, 1, 'Requesting financial assistance for medical bills', 'https://example.com/helps/docs1.zip', '2024-01-20 13:00:00', TRUE),
(2, 7, 2, 'Seeking support for surgery costs', 'https://example.com/helps/docs2.zip', '2024-01-21 14:00:00', FALSE),
(3, 8, 3, 'Need help with university tuition', 'https://example.com/helps/docs3.zip', '2024-01-22 15:00:00', TRUE);

-- Add some suggestions
INSERT INTO `suggestions` (`id`, `user_id`, `title`, `description`) VALUES
(1, 6, 'More Sports Activities', 'Would love to see more sports-related events'),
(2, 7, 'Extended Partner Network', 'Suggest adding more retail partners');

-- Add some feedback
INSERT INTO `feedbacks` (`id`, `user_id`, `partner_id`, `title`, `description`) VALUES
(1, 6, 3, 'Great Service', 'Excellent experience at the restaurant'),
(2, 7, 4, 'Room for Improvement', 'Hotel service could be better');

-- Add some notifications
INSERT INTO `notifications` (`id`, `user_id`, `title`, `description`, `url`, `date`, `reminder`) VALUES
(1, 6, 'Card Expiring Soon', 'Your membership card will expire in 30 days', '/cards', '2024-01-06 15:00:00', '2024-12-06 15:00:00'),
(2, 7, 'New Discount Available', 'Check out new partner discounts', '/discounts', '2024-01-07 16:00:00', NULL),
(3, 8, 'Activity Registration', 'Don''t forget to register for the beach cleanup', '/activities/1', '2024-01-08 17:00:00', '2024-01-31 08:00:00');
