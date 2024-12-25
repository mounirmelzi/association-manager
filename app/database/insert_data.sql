-- INSERT INTO `admins` (first_name, last_name, username, email, password)
-- VALUES
-- ('Admin', 'User', 'admin1', 'admin1@example.com', 'password123'),
-- ('Super', 'Admin', 'superadmin', 'superadmin@example.com', 'securepassword');


-- INSERT INTO `users` (username, email, phone, password)
-- VALUES
-- ('user1', 'user1@example.com', '1234567890', 'password123'),
-- ('user2', 'user2@example.com', '0987654321', 'password456');


-- INSERT INTO `card_types` (`type`, `fee`)
-- VALUES
-- ('Gold', 50.00),
-- ('Silver', 30.00),
-- ('Bronze', 15.00);


-- INSERT INTO `cards` (user_id, card_type_id, qrcode_image_url, expiration_date)
-- VALUES
-- (1, 1, 'https://example.com/qrcode1.png', '2025-12-31'),
-- (2, 2, 'https://example.com/qrcode2.png', '2024-06-30');


-- INSERT INTO `members` (first_name, last_name, birth_date, member_image_url, identity_image_url, is_active)
-- VALUES
-- ('John', 'Doe', '1990-01-01', 'https://example.com/john_image.png', 'https://example.com/john_identity.png', TRUE),
-- ('Jane', 'Smith', '1992-02-15', 'https://example.com/jane_image.png', 'https://example.com/jane_identity.png', FALSE);


-- INSERT INTO `partner_categories` (`category`)
-- VALUES
-- ('Hotel'),
-- ('Clinic'),
-- ('School');


-- INSERT INTO `partners` (name, description, partner_category_id, address)
-- VALUES
-- ('Grand Hotel', 'A luxury hotel in the city center.', 1, '123 Main Street'),
-- ('Health Plus Clinic', 'Providing quality health services.', 2, '456 Elm Street');


-- INSERT INTO `discount_offers` (partner_id, card_type_id, percentage)
-- VALUES
-- (1, 1, 20.00),
-- (2, 2, 15.00);


-- INSERT INTO `limited_discounts` (partner_id, card_type_id, percentage, start_date, end_date)
-- VALUES
-- (1, 1, 25.00, '2024-01-01', '2024-03-01'),
-- (2, 2, 10.00, '2024-06-01', '2024-06-30');


-- INSERT INTO `discounts` (partner_id, user_id, amount)
-- VALUES
-- (1, 1, 10.00),
-- (2, 2, 5.00);


-- INSERT INTO `news` (title, description, image_url)
-- VALUES
-- ('New Partnership Announced', 'We are excited to announce a new partnership.', 'https://example.com/news1.png'),
-- ('Membership Benefits Updated', 'Check out the latest membership benefits.', 'https://example.com/news2.png');


-- INSERT INTO `activities` (title, description, image_url)
-- VALUES
-- ('Community Cleanup', 'Join us for a community cleanup event.', 'https://example.com/activity1.png'),
-- ('Blood Donation Drive', 'Participate in our blood donation drive.', 'https://example.com/activity2.png');


-- INSERT INTO `volunteerings` (user_id, activity_id)
-- VALUES
-- (1, 1),
-- (2, 2);


-- INSERT INTO `payments` (user_id, receipt_image_url, amount, type)
-- VALUES
-- (1, 'https://example.com/receipt1.png', 50.00, 'registration_fee'),
-- (2, 'https://example.com/receipt2.png', 20.00, 'donations');


-- INSERT INTO `help_types` (`type`, `attachments_description`)
-- VALUES
-- ('Financial Assistance', 'Provide proof of income or bills.'),
-- ('Medical Assistance', 'Provide a doctor’s prescription or medical report.');


-- INSERT INTO `helps` (user_id, help_type_id, description, attachments_url)
-- VALUES
-- (1, 1, 'Request for financial aid for education.', 'https://example.com/attachment1.png'),
-- (2, 2, 'Request for medical assistance for surgery.', 'https://example.com/attachment2.png');


-- INSERT INTO `suggestions` (user_id, title, description)
-- VALUES
-- (1, 'Add More Benefits', 'Add more benefits for Gold card holders.'),
-- (2, 'Partner with Local Gyms', 'Partner with gyms to provide discounts.');


-- INSERT INTO `feedbacks` (user_id, partner_id, title, description)
-- VALUES
-- (1, 1, 'Great Experience', 'The hotel staff was very helpful.'),
-- (2, 2, 'Good Service', 'The clinic services were efficient.');


-- INSERT INTO `notifications` (user_id, title, description, url, reminder)
-- VALUES
-- (1, 'Membership Renewal', 'Your membership is expiring soon.', 'https://example.com/renewal', '2024-12-01'),
-- (2, 'Event Reminder', 'Don’t forget the blood donation drive.', 'https://example.com/event', '2024-06-15');
