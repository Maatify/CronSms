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

namespace Maatify\CronSmsType;

use App\DB\DBS\DbConnector;
use App\DB\Tables\DbLanguage;

class CronSmsTypeMessage extends DbConnector
{
    public const TABLE_NAME                 = 'cron_sms_type_message';
    public const TABLE_ALIAS                = 'message';
    public const IDENTIFY_TABLE_ID_COL_NAME = CronSmsType::IDENTIFY_TABLE_ID_COL_NAME;
    public const LOGGER_TYPE                = self::TABLE_NAME;
    public const LOGGER_SUB_TYPE            = 'name';
    public const COLS                       =
        [
            self::IDENTIFY_TABLE_ID_COL_NAME       => 1,
            DbLanguage::IDENTIFY_TABLE_ID_COL_NAME => 1,
            'message'                              => 0,
        ];
    public const IMAGE_FOLDER               = self::TABLE_NAME;

    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected string $logger_type = self::LOGGER_TYPE;
    protected string $logger_sub_type = self::LOGGER_SUB_TYPE;
    protected array $cols = self::COLS;
    protected string $image_folder = self::IMAGE_FOLDER;

    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function MessageByTypeAndLanguage(int $type_id, int $language_id): string
    {
        return $this->ColThisTable('message', "`$this->identify_table_id_col_name` = ? AND `" . DbLanguage::IDENTIFY_TABLE_ID_COL_NAME . "` = ?", [$type_id, $language_id]);
    }
}