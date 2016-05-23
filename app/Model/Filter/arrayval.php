<?php
namespace App\Model\Filter;

/**
 *  代入值如果不是 array, 會輸出 empty array
 *  否則就傳回原代入值 (不改變)
 */
class arrayval
{
    public function filter($value)
    {
        if (is_array($value)) {
            return $value;
        }
        return [];
    }
}
