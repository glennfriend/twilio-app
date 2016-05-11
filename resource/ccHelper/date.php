<?php

/**
 *  建立預設的日期格式, 年月日
 */
function ccHelper_date( $value, $format='Y-m-d' )
{
    if( $value < 0 ) {
        return '';
    }
    return date($format, $value);
}
