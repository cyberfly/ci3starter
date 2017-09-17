<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * CodeIgniter My Date Helpers
 *
 * @package      CodeIgniter
 * @subpackage   Helpers
 * @category     Helpers
 * @author       Muhammad Fathur Rahman < mhd.fathur@live.com >
 */

// ------------------------------------------------------------------------

//eg 11.01 PM will return 23:01

function get_mysql_time($time)
{
    $time = date("H:i", strtotime($time));

    return $time;
}

function get_mysql_date($date)
{
    $date = date("Y-m-d", strtotime($date));

    return $date;
}

function get_year($date_time)
{
    $year = date("Y", strtotime($date_time));

    return $year;
}

function get_month($date_time)
{
    $month = date("m", strtotime($date_time));

    return $month;
}

//return eg 28

function get_datez($date_time)
{
    $datez = date("d", strtotime($date_time));

    return $datez;
}

function get_hour($date_time)
{
    $hour = date("H", strtotime($date_time));

    return $hour;
}

function get_minute($date_time)
{
    $minute = date("i", strtotime($date_time));

    return $minute;
}

function combine_date_time($date, $time)
{
    $datetime = $date . ' ' . $time;

    return $datetime;
}

function prepare_mysql_date($php_date)
{
    if (!empty($php_date)) {
        $mysql_date = date("Y-m-d", strtotime($php_date));
    } else {
        $mysql_date = '0000-00-00';
    }

    return $mysql_date;
}

function prepare_php_date($mysql_date)
{
    if ($mysql_date != '0000-00-00') {
        $php_date = date("d-m-Y", strtotime($mysql_date));
    } else {
        $php_date = '';
    }

    return $php_date;
}

function timestamp_to_phpdate($timestamp)
{
    $php_date = date("d-m-Y", $timestamp);

    return $php_date;
}