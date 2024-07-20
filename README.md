[![Current version](https://img.shields.io/packagist/v/maatify/cron-sms)][pkg]
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/maatify/cron-sms)][pkg]
[![Monthly Downloads](https://img.shields.io/packagist/dm/maatify/cron-sms)][pkg-stats]
[![Total Downloads](https://img.shields.io/packagist/dt/maatify/cron-sms)][pkg-stats]
[![Stars](https://img.shields.io/packagist/stars/maatify/cron-sms)](https://github.com/maatify/CronSms/stargazers)

[pkg]: <https://packagist.org/packages/maatify/cron-sms>
[pkg-stats]: <https://packagist.org/packages/maatify/routee/cron-sms>
# Installation

```shell
composer require maatify/cron-sms
```


## Database Structure

## `cron_sms` Structure used in single-language and multi-language
### Table structure for table `cron_sms`
```MYSQL

--
-- Table structure for table `cron_sms`
--

CREATE TABLE `cron_sms` (
    `cron_id` int(11) NOT NULL,
    `type_id` int(11) NOT NULL DEFAULT '1' COMMENT '1=message; 2=confirm; 3=Password',
    `recipient_id` int(11) NOT NULL DEFAULT '0',
    `recipient_type` varchar(64) NOT NULL DEFAULT '',
    `phone` varchar(128) NOT NULL DEFAULT '',
    `message` mediumtext,
    `record_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
    `status` tinyint(1) NOT NULL DEFAULT '0',
    `sent_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `cron_sms`
--
ALTER TABLE `cron_sms`
  ADD PRIMARY KEY (`cron_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cron_sms`
--
ALTER TABLE `cron_sms`
  MODIFY `cron_id` int NOT NULL AUTO_INCREMENT;
COMMIT;
```

## Next Structures for multi-language
### Table structure for table `cron_sms_type`
```MYSQL

--
-- Table structure for table `cron_sms_type`
--

CREATE TABLE `cron_sms_type` (
                                 `type_id` int NOT NULL,
                                 `type_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Dumping data for table `cron_sms_type`
--

INSERT INTO `cron_sms_type` (`type_id`, `type_name`) VALUES
     (1, 'message'),
     (2, 'otp'),
     (3, 'forget-password');


--
-- Indexes for dumped tables
--

--
-- Indexes for table `cron_sms_type`
--
ALTER TABLE `cron_sms_type`
    ADD PRIMARY KEY (`type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cron_sms_type`
--
ALTER TABLE `cron_sms_type`
    MODIFY `type_id` int NOT NULL AUTO_INCREMENT;
COMMIT;
```

### Table structure for table `cron_sms_type_message`
```MYSQL

--
-- Table structure for table `cron_sms_type_message`
--

CREATE TABLE `cron_sms_type_message` (
     `type_id` int NOT NULL DEFAULT '1',
     `language_id` int NOT NULL DEFAULT '1',
     `message` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cron_sms_type_message`
--
ALTER TABLE `cron_sms_type_message`
    ADD UNIQUE KEY `type_id` (`type_id`,`language_id`) USING BTREE;
COMMIT;
```