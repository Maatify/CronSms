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
use App\DB\DBS\DbConnector;
use JetBrains\PhpStorm\NoReturn;
use Maatify\Json\Json;

abstract class CronSms extends DbConnector
{
    const TABLE_NAME                 = 'cron_sms';
    const TABLE_ALIAS                = '';
    const IDENTIFY_TABLE_ID_COL_NAME = 'cron_id';
    const LOGGER_TYPE                = self::TABLE_NAME;
    const LOGGER_SUB_TYPE            = '';
    const Cols                       =
        [
            self::IDENTIFY_TABLE_ID_COL_NAME => 1,
            'recipient_id'                   => 1,
            'type_id'                        => 1,
            'phone'                          => 0,
            'message'                        => 0,
            'record_time'                    => 0,
            'sent_status'                    => 1,
            'sent_time'                      => 0,
        ];

    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected string $logger_type = self::LOGGER_TYPE;
    protected string $logger_sub_type = self::LOGGER_SUB_TYPE;
    protected array $cols = self::Cols;
    const TYPE_MESSAGE       = 1;
    const TYPE_OTP           = 2;
    const TYPE_TEMP_PASSWORD = 3;

    const ALL_TYPES_NAME = [
        self::TYPE_MESSAGE       => 'message',
        self::TYPE_OTP           => 'OTP',
        self::TYPE_TEMP_PASSWORD => 'Temp Password',
    ];
    protected string $recipient_type;

    public function AllTypes(): array
    {
        return self::ALL_TYPES_NAME;
    }

    protected function AddCron(int $recipient_id, string $phone, string $message, int $type_id = 0): void
    {
        $this->Add([
            'recipient_id'   => $recipient_id,
            'recipient_type' => $this->recipient_type,
            'type_id'        => $type_id,
            'phone'          => $phone,
            'message'        => $message,
            'record_time'    => AppFunctions::CurrentDateTime(),
            'status'         => 0,
            'sent_time'      => AppFunctions::DefaultDateTime(),
        ]);
    }

    #[NoReturn] public function Resend(): void
    {
        $this->ValidatePostedTableId();
        $this->Add([
            'recipient_id'   => $this->current_row['recipient_id'],
            'recipient_type' => $this->current_row['recipient_type'],
            'type_id'        => $this->current_row['type_id'],
            'phone'          => $this->current_row['phone'],
            'message'        => $this->current_row['message'],
            'record_time'    => AppFunctions::CurrentDateTime(),
            'status'         => 0,
            'sent_time'      => AppFunctions::DefaultDateTime(),
        ]);
        $this->logger_keys = [$this->identify_table_id_col_name => $this->row_id];
        $log = $this->logger_keys;
        $changes = array();
        $log['change'] = 'Duplicate cron id: ' . $this->current_row[$this->identify_table_id_col_name];
        $this->Logger($log, $changes, $_GET['action']);
        Json::Success(line: $this->class_name . __LINE__);
    }

    public function InitializeArray(): array
    {
        $types = array();
        foreach (CronSms::ALL_TYPES_NAME as $key => $type) {
            $types[] = [
                'type_id'   => $key,
                'type_name' => $type,
            ];
        }

        return $types;
    }
}