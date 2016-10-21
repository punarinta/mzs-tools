<?php

namespace App\Model\Dict;

/**
 * Class Generic
 * @package App\Model\Dict
 */
class Generic
{
    /**
     * @param $text
     * @return mixed
     */
    static protected function normalize($text)
    {
        $tr = array
        (
        //    'ì' => 'i',
        );

        return strtr(preg_replace("/[!?]/", '', mb_strtolower($text)), $tr);
    }
}

