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

namespace Maatify\CronSmsType;

use Maatify\Json\Json;
use Maatify\LanguagePortalHandler\DBHandler\ParentClassHandler;
use Maatify\PostValidatorV2\ValidatorConstantsTypes;
use Maatify\PostValidatorV2\ValidatorConstantsValidators;

class CronSmsTypePortal extends ParentClassHandler
{
    public const IDENTIFY_TABLE_ID_COL_NAME = CronSmsType::IDENTIFY_TABLE_ID_COL_NAME;
    public const TABLE_NAME                 = CronSmsType::TABLE_NAME;
    public const TABLE_ALIAS                = CronSmsType::TABLE_ALIAS;
    public const LOGGER_TYPE                = CronSmsType::LOGGER_TYPE;
    public const LOGGER_SUB_TYPE            = CronSmsType::LOGGER_SUB_TYPE;
    public const COLS                       = CronSmsType::COLS;
    public const IMAGE_FOLDER               = self::TABLE_NAME;

    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $logger_type = self::LOGGER_TYPE;
    protected string $logger_sub_type = self::LOGGER_SUB_TYPE;
    protected array $cols = self::COLS;
    protected string $image_folder = self::IMAGE_FOLDER;

    // to use in list of AllPaginationThisTableFilter()
    protected array $inner_language_tables = [];

    // to use in list of source and destination rows with names
    protected string $inner_language_name_class = '';

    protected array $cols_to_add = [
        ['type_name', ValidatorConstantsTypes::Slug, ValidatorConstantsValidators::Require],
    ];

    protected array $cols_to_edit = [
        ['type_name', ValidatorConstantsTypes::Slug, ValidatorConstantsValidators::Optional],
    ];

    protected array $cols_to_filter = [
        [self::IDENTIFY_TABLE_ID_COL_NAME, ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        ['type_name', ValidatorConstantsTypes::Slug, ValidatorConstantsValidators::Optional],
    ];

    // to use in add if child classes no have language_id
    protected array $child_classes = [];

    // to use in add if child classes have language_id
    protected array $child_classe_languages = [CronSmsTypeMessagePortal::class];
    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function Record(): void
    {
        $type_name = $this->postValidator->Require('type_name', ValidatorConstantsTypes::Slug);
        if($this->RowIsExistThisTable('`type_name` = ? ', [$type_name])){
            Json::Exist('type_name', 'Type Name ' . $type_name . ' Already Exists', $this->class_name . __LINE__);
        }
        parent::Record();
    }
}