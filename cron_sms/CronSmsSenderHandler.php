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

use App\Assist\AppFunctions;
use Maatify\QueueManager\QueueManager;

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

    protected function InitiateListToSend(): void
    {
        QueueManager::obj()->Sms();
        if(!($this->list_to_send = $this->NotSentOtp())){
            if(!($this->list_to_send = $this->NotSentPasswords())){
                $this->list_to_send = $this->NotSentMessage();
            }
        }
    }

    protected function ReplaceTemplateCode(string $template, string $code): string
    {
        return str_replace("{replaced_code}", $code, $template);
    }

    public function OTPText(): string
    {

        return 'your OTP code is {replaced_code}. For your account security, don\'t share this code with anyone.';
    }

    public function TempPasswordText(): string
    {
        return 'your temp password is {replaced_code}. For your account security, don\'t share this password with anyone.';
    }
}