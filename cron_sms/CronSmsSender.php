<?php
/**
 * @PHP       Version >= 8.2
 * @copyright ©2024 Maatify.dev
 * @author    Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since     2024-07-15 10:31 AM
 * @link      https://www.maatify.dev Maatify.com
 * @link      https://github.com/Maatify/CronSms  view project on GitHub
 * @Maatify   DB :: CronSms
 */

namespace Maatify\CronSms;

class CronSmsSender
{
    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private CronSmsMultiLanguageSenderHandler|CronSmsSingleLanguageSenderHandler $sender;

    public function __construct()
    {
        if(empty($_ENV['IS_SMS_MULTI_LANGUAGE'])) {
            $this->sender = new CronSmsSingleLanguageSenderHandler();
        }else{
            $this->sender = new CronSmsMultiLanguageSenderHandler();
        }
    }

    public function CronSend(): void
    {
        $this->sender->CronSend();
    }
}