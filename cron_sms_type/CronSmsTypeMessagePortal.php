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

use App\DB\Handler\SubClassLanguageHandler;
use App\DB\Tables\DbLanguage;
use Maatify\PostValidatorV2\ValidatorConstantsTypes;
use Maatify\PostValidatorV2\ValidatorConstantsValidators;

class CronSmsTypeMessagePortal extends SubClassLanguageHandler
{
    public const TABLE_NAME                 = CronSmsTypeMessage::TABLE_NAME;
    public const TABLE_ALIAS                = CronSmsTypeMessage::TABLE_ALIAS;
    public const IDENTIFY_TABLE_ID_COL_NAME = CronSmsTypeMessage::IDENTIFY_TABLE_ID_COL_NAME;
    public const LOGGER_TYPE                = CronSmsTypeMessage::LOGGER_TYPE;
    public const LOGGER_SUB_TYPE            = CronSmsTypeMessage::LOGGER_SUB_TYPE;
    public const COLS                       = CronSmsTypeMessage::COLS;
    public const IMAGE_FOLDER               = CronSmsTypeMessage::TABLE_NAME;

    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected string $logger_type = self::LOGGER_TYPE;
    protected string $logger_sub_type = self::LOGGER_SUB_TYPE;
    protected string $image_folder = self::IMAGE_FOLDER;

    protected array $cols = self::COLS;

    protected array $cols_to_add = [
        ['message', ValidatorConstantsTypes::String, ''],
    ];

    protected array $cols_to_update = [
        ['message', ValidatorConstantsTypes::String, ValidatorConstantsValidators::Require],
    ];

    protected array $cols_to_filter = [
        [self::IDENTIFY_TABLE_ID_COL_NAME, ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        [DbLanguage::IDENTIFY_TABLE_ID_COL_NAME, ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        ['message', ValidatorConstantsTypes::String, ValidatorConstantsValidators::Optional],
    ];

    protected string $parent_class = CronSmsType::class;

    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

}

