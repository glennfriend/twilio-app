<?php
namespace App\Model\Filter;

/**
 *
 */
class floatval
{
    public function filter($value)
    {
        return floatval($value);
    }
}
