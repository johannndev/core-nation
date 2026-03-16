<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class StringValueBinder extends DefaultValueBinder
{
    public function bindValue(Cell $cell, $value)
    {
        // Paksa string angka panjang tetap STRING
        if (is_string($value) && ctype_digit($value) && strlen($value) > 15) {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);
            return true;
        }

        return parent::bindValue($cell, $value);
    }
}
