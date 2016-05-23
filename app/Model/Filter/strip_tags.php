<?php
namespace App\Model\Filter;

/**
 *
 */
class strip_tags
{
    public function filter($value)
    {
        return strip_tags($value);
    }
}
