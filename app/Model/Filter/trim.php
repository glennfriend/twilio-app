<?php
namespace App\Model\Filter;

/**
 *
 */
class trim
{
    public function filter($value)
    {
        return trim($value);
    }
}
