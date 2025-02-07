<?php
// app/Models/AIModel.php
namespace App\Models;

class AIModel
{
    public function validateInput($value, $min, $max)
    {
        return $value >= $min && $value <= $max;
    }

    public function normalize($value, $min, $max)
    {
        return ($value - $min) / ($max - $min);
    }
}