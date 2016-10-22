<?php

namespace App\Model\Dict;

/**
 * http://steen.free.fr/interslavic/dynamic_dictionary.html
 *
 * Class Dict2
 * @package App\Model
 */
class Dict2 extends Generic
{
    public static function toMzs($token, $options = [])
    {
        $words = [];
        $exact = isset ($options['exact']) ? $options['exact'] : true;
        $token = self::normalize($token);

        foreach (explode("\n", file_get_contents('data/dictionary.js')) as $line)
        {
            if (preg_match("#'(.*?)'#", $line, $matches))
            {
                $word = explode("\t", $matches[1]);

                if ($exact)
                {
                    if (self::normalize($word[3]) != self::normalize($token))
                    {
                        continue;
                    }
                }
                else
                {
                    if (strpos(self::normalize($word[3]), self::normalize($token)) === false)
                    {
                        continue;
                    }
                }

                $mzsKey = self::normalize($word[0]);

                $words[$mzsKey] = array
                (
                    'eng'   => $word[3],
                    'mzs'   => $mzsKey,
                    'mzs2'  => trim(self::normalize($word[1]), '()'),
                    'desc'  => '',
                    'type'  => self::normalizeType($word[2]),
                    'level' => (int) $word[4],
                    'code'  => $word[5],
                );
            }
        }

        return $words;
    }

    // n - noun, a - adjective, v - verb
    static function normalizeType($type)
    {
        $type = trim($type, '.');

        if ($type == 'm') return 'nm';
        if ($type == 'f') return 'nf';
        if ($type == 'n') return 'nn';
        if ($type == 'adj') return 'a';

        if (!mb_strlen($type)) return null;

        return $type;
    }
}

