<?php
use App\Utility\Datetime\Timezone;

/**
 *  建立預設的日期格式, 年月日時分秒
 *  會依照 Time Zone 做轉換
 */
function ccHelper_convertTimezone($value, $convertTimezone, $format='Y-m-d H:i:s')
{
    // check system timezone
    $systemTimezone = Timezone::getSystem();
    if (!$systemTimezone) {
        return '';
    }

    // validate;
    $all = Timezone::getAll();
    if (!isset($all[$convertTimezone])) {
        return '';
    }

    $result = Timezone::convert($value, $systemTimezone, $convertTimezone);
    if ($result < 0) {
        return '';
    }
    return date($format, $result);
}
