<?php

namespace App\Controller\Api;

use App\Model\Adjective;
use App\Model\Dict\Dict1;
use App\Model\Dict\Dict2;
use App\Model\Noun;
use App\Model\Verb;

class Word extends Generic
{
    /**
     * @return array
     * @throws \Exception
     */
    public function translate()
    {
        if (!$token = \Input::data('token'))
        {
            throw new \Exception('No search token provided.', 404);
        }

        $loose = \Input::data('loose');

        return array_values(array_merge
        (
            Dict1::toMzs($token, ['exact' => !$loose]),
            Dict2::toMzs($token, ['exact' => !$loose])
        ));
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function showForms()
    {
        $res = [];

        if (!$word = \Input::data('word'))
        {
            throw new \Exception('No word provided.', 404);
        }
        if (!$type = \Input::data('type'))
        {
            throw new \Exception('No type provided.', 404);
        }

        if (mb_substr($type, 0, 1) == 'n')
        {
            $res = Noun::morph($word, mb_substr($type, 1, 1));
        }
        if (mb_substr($type, 0, 1) == 'a')
        {
            $res = Adjective::morph($word);
        }
        if (mb_substr($type, 0, 1) == 'v')
        {
            $res = Verb::morph($word);
        }

        return $res;
    }

    public function test()
    {
        $res = Noun::morph('gorod');

        return $res;
    }

    public function googleTest()
    {
        return [1,2,3];
    }
}

