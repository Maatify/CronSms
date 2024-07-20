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

use App\Assist\Encryptions\CronSMSEncryption;
use App\Services\Providers\Sms\SmsSender;

class CronSmsSingleLanguageSenderHandler extends CronSmsSenderHandler
{
    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    protected function NotSentByType(int $type_id): array
    {
        return $this->RowsThisTable('*', "`status` = ? AND `type_id` = ? ORDER BY `$this->tableName`.`$this->identify_table_id_col_name` ASC LIMIT 10", [0, $type_id]);
    }

    public function CronSend(): void
    {
        // prepare sms sender
        $this->InitiateListToSend();
    }

    protected function Send(): void
    {
        if(!empty($this->list_to_send)){
            foreach ($this->list_to_send as $item){
                $message = match ($item['type_id']) {
                    self::TYPE_OTP =>
                        $this->ReplaceTemplateCode(
                            $this->OTPText(),
                            (new CronSMSEncryption())->DeHashed($item['message'])
                        ),
                    self::TYPE_TEMP_PASSWORD =>
                        $this->ReplaceTemplateCode(
                            $this->TempPasswordText(),
                            (new CronSMSEncryption())->DeHashed($item['message'])
                        ),
                };
                if(SmsSender::obj()->SendSms($item['phone'], $message)){
                    $this->SentMarker($item[$this->identify_table_id_col_name]);
                }
            }
            $this->InitiateListToSend();
        }
    }
}