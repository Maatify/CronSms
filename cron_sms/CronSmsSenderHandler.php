<?php
/**
 * @PHP       Version >= 8.0
 * @copyright ©2024 Maatify.dev
 * @author    Mohamed Abdulalim (megyptm) <mohamed@maatify.dev>
 * @since     2024-07-15 10:31 AM
 * @link      https://www.maatify.dev Maatify.com
 * @link      https://github.com/Maatify/CronSms  view project on GitHub
 * @Maatify   DB :: CronSms
 */

namespace Maatify\CronSms;

use App\Assist\AppFunctions;
use App\Assist\Encryptions\CronSMSEncryption;
use App\Services\Providers\Sms\SmsSender;

abstract class CronSmsSenderHandler extends CronSms
{

    protected array $list_to_send = [];

    protected function SentMarker(int $cron_id): void
    {
        $this->Edit([
            'status'     => 1,
            'sent_time'   => AppFunctions::CurrentDateTime(),
        ], "`$this->identify_table_id_col_name` = ? ", [$cron_id]);
    }

    private function NotSentOtp(): array
    {
        return $this->NotSentByType(self::TYPE_OTP);
    }

    private function NotSentPasswords(): array
    {
        return $this->NotSentByType(self::TYPE_TEMP_PASSWORD);
    }

    private function NotSentMessage(): array
    {
        return $this->NotSentByType(self::TYPE_MESSAGE);
    }

    private int $trying = 0;

    protected function InitiateListToSend(): void
    {
        if($this->trying < 5) {
            if ($this->list_to_send = $this->NotSentOtp()) {
                $this->trying++;
                $this->Send();
            } else {
                if ($this->list_to_send = $this->NotSentPasswords()) {
                    $this->trying++;
                    $this->Send();
                } else {
                    if ($this->list_to_send = $this->NotSentMessage()) {
                        if($this->trying < 5){
                            $this->trying = 5;
                        }
                        $this->Send();
                    }
                }
            }
        }
    }
}