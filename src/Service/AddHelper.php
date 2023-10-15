<?php

namespace App\Service;

class AddHelper
{
    public function add(int $a, int $b): int
    {
        $result = $a + $b;

        return $result;
    }
}