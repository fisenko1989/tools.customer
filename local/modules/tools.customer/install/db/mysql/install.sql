CREATE TABLE IF NOT EXISTS `c_info_customer`
(
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `order_id` int NOT NULL,
    `data` text NOT NULL
);