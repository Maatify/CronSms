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

use App\Assist\Encryptions\CronSMSEncryption;

abstract class CronSmsRecord extends CronSms
{
    public function RecordMessage(int $ct_id, string $phone, string $message): void
    {
        $this->AddCron($ct_id, $phone, $message, self::TYPE_MESSAGE);
    }

    public function RecordOTP(int $ct_id, string $phone, string $otp_code): void
    {
        $this->AddCron($ct_id, $phone, (new CronSMSEncryption())->Hash($otp_code), self::TYPE_OTP);
    }

    public function RecordPassword(int $ct_id, string $phone, string $otp_code): void
    {
        $this->AddCron($ct_id, $phone, (new CronSMSEncryption())->Hash($otp_code), self::TYPE_TEMP_PASSWORD);
    }

}