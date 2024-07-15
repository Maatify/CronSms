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


# Database Structure

```MYSQL

--
-- Table structure for table `cron_sms`
--

CREATE TABLE `cron_sms` (
  `cron_id` int NOT NULL,
  `type_id` int NOT NULL DEFAULT '0' COMMENT '1=message; 2=confirm; 3=Password',
  `ct_id` int NOT NULL DEFAULT '0',
  `phone` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  `message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `record_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `sent_time` datetime NOT NULL DEFAULT '1900-01-01 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
