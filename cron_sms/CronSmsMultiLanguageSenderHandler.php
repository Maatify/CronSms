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
use App\DB\Tables\Customer\Customer;
use App\Services\Providers\Sms\SmsSender;
use Maatify\CronSmsType\CronSmsTypeMessage;
use Maatify\LanguagePortalHandler\Tables\LanguageTable;

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
        $customer_table_name = Customer::TABLE_NAME;
        $customer_col_name = Customer::IDENTIFY_TABLE_ID_COL_NAME;
        return $this->Rows("$this->tableName 
            LEFT JOIN `$customer_table_name` 
                ON `$customer_table_name`.`$customer_col_name` = `$this->tableName`.`recipient_id` 
                AND `$this->tableName`.`recipient_type` = 'customer' ",
            "`$this->tableName`.*, IFNULL(`$customer_table_name`.`msg_pref_lang_id`, 1) as language_id",
            "`$this->tableName`.`status` = ? AND `$this->tableName`.`type_id` = ? ORDER BY `$this->tableName`.`$this->identify_table_id_col_name` ASC LIMIT 10",
            [0, $type_id]);
    }

/*    protected function NotSentByType(int $type_id): array
    {
        [$t, $c] = Customer::obj()->InnerJoinThisTableWithUniqueCols($this->tableName, ['msg_pref_lang_id' => 1]);
        return $this->Rows("$this->tableName $t",
            "`$this->tableName`.*, $c",
            "`$this->tableName`.`status` = ? AND `$this->tableName`.`type_id` = ? ORDER BY `$this->tableName`.`$this->identify_table_id_col_name` ASC LIMIT 10",
            [0, $type_id]);
    }*/

    public function CronSend(): void
    {
        // prepare sms sender
        $this->InitiateListToSend();
        $this->Send();
    }

    protected function Send(): void
    {

        if(!empty($this->list_to_send)){
            foreach ($this->list_to_send as $item){
                $message = match ($item['type_id']) {
                    self::TYPE_OTP =>
                        $this->ReplaceTemplateCode(
                            (CronSmsTypeMessage::obj()->
                            MessageByTypeAndLanguage($item['type_id'], $item[LanguageTable::IDENTIFY_TABLE_ID_COL_NAME])  ? :$this->OTPText()),
                            (new CronSMSEncryption())->DeHashed($item['message'])
                        ),

                    self::TYPE_TEMP_PASSWORD =>
                        $this->ReplaceTemplateCode(
                            (CronSmsTypeMessage::obj()->
                            MessageByTypeAndLanguage($item['type_id'], $item[LanguageTable::IDENTIFY_TABLE_ID_COL_NAME])  ? :$this->TempPasswordText()),
                            (new CronSMSEncryption())->DeHashed($item['message'])
                        ),

                    default => $item['message'],
                };
                if(SmsSender::obj()->SendSms($item['phone'], $message)){
                    $this->SentMarker($item[$this->identify_table_id_col_name]);
                }
            }
            $this->InitiateListToSend();
        }
    }
}