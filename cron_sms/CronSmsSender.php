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

class CronSmsSender extends CronSms
{
    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function SentMarker(int $id): void
    {
        $this->Edit([
            'is_sent'     => 1,
            'sent_time'   => AppFunctions::CurrentDateTime(),
        ], "`$this->identify_table_id_col_name` = ? ", [$id]);
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

    private function NotSentByType(int $type_id): array
    {
        return $this->RowsThisTable('*', "`is_sent` = ? AND `type_id` = ? ORDER BY `$this->tableName`.`$this->identify_table_id_col_name` ASC LIMIT 10", [0, $type_id]);
    }

    public function CronSend(): void
    {
        // prepare sms sender
        if(!($all = $this->NotSentOtp())){

            if(!($all = $this->NotSentPasswords())){

                $all = $this->NotSentMessage();
            }
        }

        if(!empty($all)){
            foreach ($all as $item){
                $message = match ($item['type_id']) {
                    self::TYPE_OTP => AppFunctions::OTPText() . (new CronSMSEncryption())->DeHashed($item['message']),
                    self::TYPE_TEMP_PASSWORD => AppFunctions::TempPasswordText() . (new CronSMSEncryption())->DeHashed($item['message']),
                    default => $item['message'],
                };
                if(SmsSender::obj()->SendSms($item['phone'], $message)){
                    $this->SentMarker($item[$this->identify_table_id_col_name]);
                }
            }
        }
    }
}