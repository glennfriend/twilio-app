<?php
namespace App\Model\Filter;

/**
 *
 */
class intval
{
    public function filter($value)
    {
        return intval($value);
    }
}
