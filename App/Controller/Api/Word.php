<?php

namespace App\Controller\Api;

use App\Model\Dict\Dict1;
use App\Model\Dict\Dict2;

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
        if (!$word = \Input::data('word'))
        {
            throw new \Exception('No word provided.', 404);
        }

        $type = \Input::data('type');

        return [];
    }

    public function googleTest()
    {
        return [1,2,3];
    }
}

