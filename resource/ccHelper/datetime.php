<?php

/**
 *  建立預設的日期格式, 年月日時分秒
 */
function ccHelper_datetime( $value, $format='Y-m-d H:i:s' )
{
    if( $value < 0 ) {
        return '';
    }
    return date($format, $value);
}
