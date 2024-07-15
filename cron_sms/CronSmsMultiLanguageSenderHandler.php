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
use App\DB\Tables\Cron\CronSmsTypeMessage;
use App\DB\Tables\Customer\Customer;
use App\DB\Tables\DbLanguage;
use App\Services\Providers\Sms\SmsSender;

class CronSmsMultiLanguageSenderHandler extends CronSmsSenderHandler
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
        [$t, $c] = Customer::obj()->InnerJoinThisTableWithUniqueCols($this->tableName, ['msg_pref_lang_id' => 1]);
        return $this->Rows("$this->tableName $t",
            "`$this->tableName`.*, $c",
            "`$this->tableName`.`status` = ? AND `$this->tableName`.`type_id` = ? ORDER BY `$this->tableName`.`$this->identify_table_id_col_name` ASC ",
            [0, $type_id]);
    }

    public function CronSend(): void
    {
        // prepare sms sender
        $this->InitiateListToSend();

        if(!empty($this->list_to_send)){
            foreach ($this->list_to_send as $item){
                $message = match ($item['type_id']) {
                    self::TYPE_OTP
                    => (CronSmsTypeMessage::obj()->
                        MessageByTypeAndLanguage($item['type_id'], $item[DbLanguage::IDENTIFY_TABLE_ID_COL_NAME])  ? : AppFunctions::OTPText())
                       . PHP_EOL
                       . (new CronSMSEncryption())->DeHashed($item['message']),
                    self::TYPE_TEMP_PASSWORD
                    => (CronSmsTypeMessage::obj()->
                        MessageByTypeAndLanguage($item['type_id'], $item[DbLanguage::IDENTIFY_TABLE_ID_COL_NAME])  ? : AppFunctions::TempPasswordText())
                                               . PHP_EOL
                                               . (new CronSMSEncryption())->DeHashed($item['message']),
                    default => $item['message'],
                };
                if(SmsSender::obj()->SendSms($item['phone'], $message)){
                    $this->SentMarker($item[$this->identify_table_id_col_name]);
                }
            }
        }
    }
}