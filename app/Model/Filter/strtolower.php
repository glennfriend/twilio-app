<?php
namespace App\Model\Filter;

/**
 *
 */
class strtolower
{
    public function filter($value)
    {
        return strtolower($value);
    }
}
