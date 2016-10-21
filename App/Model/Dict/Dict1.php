<?php

namespace App\Model\Dict;

/**
 * http://dict.interslavic.com/
 *
 * Class Dict1
 * @package App\Model
 */
class Dict1 extends Generic
{
    public static function toMzs($token, $options = [])
    {
        $words = [];
        $exact = isset ($options['exact']) ? $options['exact'] : true;
        $token = self::normalize($token);

        $ch = curl_init('http://dict.interslavic.com/index.jsp?input=english&word=' . $token . '&type=' . ($exact ? 'exact':'contains'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);

        if (preg_match_all('#<tr bgcolor="">(.*?)<\/tr>#', $output, $matches))
        {
            foreach ($matches[1] as $match)
            {
                $word = array_map(function ($x){return strtr($x, ['<td>'=>'','</td>'=>'']);}, explode('</td><td>', $match));

                $mzs = explode('(', $word[1]);
                $mzsKey = trim(self::normalize($mzs[0]));

                $words[$mzsKey] = array
                (
                    'eng'   => $word[0],
                    'mzs'   => $mzsKey,
                    'mzs2'  => count($mzs) == 2 ? trim(self::normalize($mzs[1]), ' )') : '',
                    'desc'  => $word[2],
                    'type'  => $word[3],
                    'level' => (int) $word[4],
                    'code'  => $word[5],
                );
            }
        }

        return $words;
    }
}

