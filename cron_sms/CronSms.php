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
use App\DB\DBS\DbConnector;

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
            'ct_id'                          => 1,
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
    protected array $cols = self::Cols;
    const TYPE_MESSAGE       = 1;
    const TYPE_OTP           = 2;
    const TYPE_TEMP_PASSWORD = 3;

    const ALL_TYPES_NAME = [
        self::TYPE_MESSAGE => 'message',
        self::TYPE_OTP => 'OTP',
        self::TYPE_TEMP_PASSWORD => 'Temp Password',
    ];

    public function AllTypes(): array
    {
        return self::ALL_TYPES_NAME;
    }

    protected function AddCron(int $ct_id, string $phone, string $message, int $type_id = 0): void
    {
        $this->Add([
            'ct_id'       => $ct_id,
            'type_id'     => $type_id,
            'phone'       => $phone,
            'message'     => $message,
            'record_time' => AppFunctions::CurrentDateTime(),
            'sent_status' => 0,
            'sent_time'   => AppFunctions::DefaultDateTime(),
        ]);
    }
}