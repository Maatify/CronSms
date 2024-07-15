<?php
/**
 * Created by Maatify.dev
 * User: Maatify.dev
 * Date: 2024-07-15
 * Time: 10:20 AM
 * https://www.Maatify.dev
 */

namespace Maatify\CronSms;

class Test
{
    private static self $instance;

    public static function obj(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}