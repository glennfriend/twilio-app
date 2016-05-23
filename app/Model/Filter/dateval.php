<?php
namespace App\Model\Filter;

/**
 *
 */
class dateval
{
    public function filter($value)
    {
        $value = intval($value);
        if (!$value) {
            return 0;
        }
        return $value;
    }
}
