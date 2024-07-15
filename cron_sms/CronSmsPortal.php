<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2024-07-16
 * Time: 9:30 AM
 * https://www.Maatify.dev
 */

namespace Maatify\CronSms;

use App\DB\DBS\DbPortalHandler;
use Maatify\Json\Json;
use Maatify\PostValidatorV2\ValidatorConstantsTypes;
use Maatify\PostValidatorV2\ValidatorConstantsValidators;

class CronSmsPortal extends DbPortalHandler
{
    const TABLE_NAME                 = CronSms::TABLE_NAME;
    const TABLE_ALIAS                = CronSms::TABLE_ALIAS;
    const IDENTIFY_TABLE_ID_COL_NAME = CronSms::IDENTIFY_TABLE_ID_COL_NAME;
    const LOGGER_TYPE                = CronSms::LOGGER_TYPE;
    const LOGGER_SUB_TYPE            = CronSms::LOGGER_SUB_TYPE;
    const Cols                       = CronSms::Cols;

    protected string $tableName = self::TABLE_NAME;
    protected string $tableAlias = self::TABLE_ALIAS;
    protected string $identify_table_id_col_name = self::IDENTIFY_TABLE_ID_COL_NAME;
    protected array $cols = self::Cols;
    private static self $instance;

    protected array $cols_to_filter = [
        [self::IDENTIFY_TABLE_ID_COL_NAME, ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        ['ct_id', ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        ['status', ValidatorConstantsTypes::Status, ValidatorConstantsValidators::Optional],
        ['type_id', ValidatorConstantsTypes::Int, ValidatorConstantsValidators::Optional],
        ['phone', ValidatorConstantsTypes::Bool, ValidatorConstantsValidators::Optional],
    ];

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function AllPaginationThisTableFilter(string $order_with_asc_desc = ''): void
    {
        [$tables, $cols] = $this->HandleThisTableJoins();
        $where_to_add = '';
        $where_val_to_add = [];
        if(!empty($_POST['record_date_from'])){
            $record_date_from = $this->postValidator->Optional('record_date_from', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $record_date_from .= ' 00:00:00';
            $where_to_add .= ' AND `record_time` >= ?';
            $where_val_to_add[] = $record_date_from;
        }
        if(!empty($_POST['record_date_to'])){
            $record_date_to = $this->postValidator->Optional('record_date_to', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $record_date_to .= ' 23:59:59';
            $where_to_add .= ' AND `record_time` <= ?';
            $where_val_to_add[] = $record_date_to;
        }
        if(!empty($_POST['sent_date_from'])){
            $sent_date_from = $this->postValidator->Optional('sent_date_from', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $sent_date_from .= ' 00:00:00';
            $where_to_add .= ' AND `sent_time` >= ?';
            $where_val_to_add[] = $sent_date_from;
        }
        if(!empty($_POST['sent_date_to'])){
            $sent_date_to = $this->postValidator->Optional('sent_date_to', ValidatorConstantsTypes::Date, $this->class_name . __LINE__);
            $sent_date_to .= ' 23:59:59';
            $where_to_add .= ' AND `sent_time` <= ?';
            $where_val_to_add[] = $sent_date_to;
        }
        $result = $this->ArrayPaginationThisTableFilter($tables, $cols,$where_to_add, $where_val_to_add, " ORDER BY `$this->identify_table_id_col_name` ASC");
        if(!empty($result['data'])) {
            $result['data'] = array_map(function ($item) {
                $types = CronSms::ALL_TYPES_NAME;
                $item['type_name'] = $types[$item['type_id']];
                return $item;
            }, $result['data']);
        }
        Json::Success(
            $result
        );
    }
}