<?php

namespace App\Model;

class Verb
{
    public static $vowel = ['a','å','e','ě','ę','i','o','ò','u','ų','y'];
    public static $liquid = ['l','r','ŕ'];
    public static $nasal = ['n','m'];
    
    static public function morph ($inf, $pts = '')
    {
        $refl		= self::reflexive($inf);
        $pref		= self::prefix($inf);
        $is 		= self::infinitive_stem($pref, $inf);
        if ($is == "ERROR-1")
        {
            // no input
        }
        else if ($is == "ERROR-2")
        {
            // illegal form
        }

        $ps 		= self::present_tense_stem ($pref, $pts, $is);
        $psi 		= self::secondary_present_tense_stem ($ps);
        $lpa		= self::l_participle ($pref, $is);
        $infinitive = self::build_infinitive ($pref, $is, $refl);
        $present	= self::build_present ($pref, $ps, $psi, $refl);
        $imperfect	= self::build_imperfect ($pref, $is, $refl);
        $perfect	= self::build_perfect ($lpa, $refl);
        $pluperfect	= self::build_pluperfect ($lpa, $refl);
        $future		= self::build_future ($infinitive, $ps);
        $conditional= self::build_conditional ($lpa, $refl);
        $imperative	= self::build_imperative ($pref, $psi, $refl);
        $prap		= self::build_prap ($pref, $ps, $refl);
        $prpp		= self::build_prpp ($pref, $ps, $psi);
        $pfap		= self::build_pfap ($lpa, $refl);
        $pfpp		= self::build_pfpp ($pref, $is, $psi);
        $gerund		= self::build_gerund ($pfpp, $refl);

        $result = '<table class="border" style="font-family:ms sans serif; font-size:10pt;">';
        $result .= '<tr><th class="leeg" width="50px" colspan="2"> </th><th> present </th><th> imperfect </th><th> future </th>';
        $result .= '</tr><tr>';
        $result .= '<th> 1sg <br> 2sg <br> 3sg <br><br> 1pl <br> 2pl <br> 3pl </th>';
        $result .= self::transliterate_back ('<td align="center"> ja <br> ty <br> on/ona/ono <br><br> my <br> vy <br> oni </td>');
        $result .= '	<td>' . $present . '</td>';
        $result .= '	<td>' . $imperfect . '</td>';
        $result .= '	<td>' . $future . '</td>';
        $result .= '</tr><tr>';
        $result .= '	<th class="leeg" colspan="2"> </th><th> perfect </th><th> pluperfect </th><th> conditional </th>';
        $result .= '</tr><tr>';
        $result .= '	<th> 1sg <br> 2sg <br> 3sg <br><br><br><br> 1pl <br> 2pl <br> 3pl </th>';
        $result .= self::transliterate_back ('<td align="center"> ja <br> ty <br> on <br> ona <br> ono <br><br> my <br> vy <br> oni </td>');
        $result .= '	<td>' . $perfect . '</td>';
        $result .= '	<td>' . $pluperfect . '</td>';
        $result .= '	<td>' . $conditional . '</td>';
        $result .= '</tr></table><br>';
        $result .= '<table class="border" style="font-family:ms sans serif; font-size:10pt;">';
        $result .= '	<tr><th> $infinitive </th><td>' . $infinitive . '</td>';
        $result .= '	<tr><th> imperative </th><td>' . $imperative . '</td>';
        $result .= '	<tr><th> present active participle </th><td>' . $prap . '</td>';
        $result .= '	<tr><th> present passive participle </th><td>' . $prpp . '</td>';
        $result .= '	<tr><th> past active participle </th><td>' . $pfap . '</td>';
        $result .= '	<tr><th> past passive participle </th><td>' . $pfpp . '</td>';
        $result .= '	<tr><th> verbal&nbsp;noun </th><td>' . $gerund . '</td>';
        $result .= '</tr></table>';

        return $result;
    }

    static public function reflexive($inf)
    {
        if (mb_strrpos($inf, 'se') == mb_strlen($inf) - 2 || mb_strrpos($inf, 'sę') == mb_strlen($inf) - 2 ||
            mb_strpos($inf, 'se ') === 0 || mb_strpos($inf, 'sę ') === 0)
        {
            $result = ' sę';
        }
        else	
        {
            $result = '';
        }
        
        return $result;
    }

    static public function prefix ($inf)
    {
        $result = '';
        $kreska = mb_strpos($inf, '-');
        
        if ($kreska != false)
        {	
            $result = self::substring($inf, 0, $kreska + 1);	
        }
        /*	else if (($inf.substring (0, 4) == 'pred') || ($inf.substring (0, 4) == 'prėd'))
                {	$result = $inf.substring (0, 4); 	}
            else if (($inf.substring (0, 3) == 'pre') || ($inf.substring (0, 3) == 'prė'))
                {	$result = $inf.substring (0, 3); 	}
            else if (($inf.substring (0, 3) == 'pri') || ($inf.substring (0, 3) == 'pro'))
                {	$result = $inf.substring (0, 3); 	}
            else if (($inf.substring (0, 3) == 'raz') || ($inf.substring (0, 3) == 'råz'))
                {	$result = $inf.substring (0, 3); 	}
            else if (($inf.substring (0, 3) == 'pod') || ($inf.substring (0, 3) == 'nad'))
                {	$result = $inf.substring (0, 3); 	}
            else if (($inf.substring (0, 2) == 'po') || ($inf.substring (0, 2) == 'na'))
                {	$result = $inf.substring (0, 2); 	}
            else if (($inf.substring (0, 2) == 'do') || ($inf.substring (0, 2) == 'za'))
                {	$result = $inf.substring (0, 2); 	}
            else if (($inf.substring (0, 2) == 'iz') || ($inf.substring (0, 2) == 'od'))
                {	$result = $inf.substring (0, 2); 	}
            else if (($inf.substring (0, 2) == 'vy') || ($inf.substring (0, 2) == 'ob'))
                {	$result = $inf.substring (0, 2); 	}
        */
        return $result;
    }

    static public function infinitive_stem($pref, $inf)
    {
        $inf = str_replace($pref, '', $inf);
    
        if (mb_strlen($inf) == 0)
        {
            $result = 'ERROR-1';
            return $result;
        }
        else if (mb_strrpos($inf, 'se') == mb_strlen($inf) - 2 || mb_strrpos($inf, 'sę') == mb_strlen($inf) - 2)
        {
            $trunc = self::substring($inf, 0, mb_strlen($inf) - 3);
        }
        else if (mb_strpos($inf, 'se ') === 0 || mb_strpos($inf, 'sę ') === 0)
        {
            $trunc = self::substring($inf, 3, mb_strlen($inf));
        }
        else
        {
            $trunc = $inf;
        }
    
        if ($trunc == '')
        {
            $result = 'ERROR-2';
            return $result;
        }
    
        if (mb_strrpos($trunc, 'ti') == mb_strlen($trunc) - 2 || mb_strrpos($trunc, 'tì') == mb_strlen($trunc) - 2)
        {
            $result = self::substring($trunc, 0, mb_strlen($trunc) - 2);
        }
        else if (mb_strrpos($trunc, 't') == mb_strlen($trunc) - 1 || mb_strrpos($trunc, 'ť') == mb_strlen($trunc) - 1)
        {
            $result = self::substring($trunc, 0, mb_strlen($trunc) - 1);
        }
        else
        {
            $result = 'ERROR-2';
        }

        if (mb_strrpos($result, 's') == mb_strlen($result) - 1)
        {
            $result = self::substring($result, 0, mb_strlen($result) - 1) . 'd';
            if ($result == 'ned') 						
            {
                $result = 'nes'; 
            }
            else if (mb_strrpos($result, 'gned') == mb_strlen($result) - 4)
            {
                $result = str_replace('gned', 'gnet', $result);
            }
            else if (mb_strrpos($result, 'pled') == mb_strlen($result) - 4)
            {
                $result = str_replace('pled', 'plet', $result);
            }
            else if (mb_strrpos($result, 'tręd') == mb_strlen($result) - 4)
            {
                $result = str_replace('tręd', 'tręs', $result);
            }
            else if (mb_strrpos($result, 'tred') == mb_strlen($result) - 4)
            { 
                $result = str_replace('tred', 'tres', $result);
            }
            else if (mb_strrpos($result, 'ned') == mb_strlen($result) - 3)
            {
                $result = str_replace('ned', 'nes', $result);
            }
        }

        return $result;
    }

    static public function present_tense_stem ($pref, $pts, $is)
    {
        $result = $is;
    
        if (mb_strlen($pts) == 0)
        {
            if ((self::substring($result, mb_strlen($result) - 3, mb_strlen($result)) == 'ova' || self::substring($result, mb_strlen($result) - 3, mb_strlen($result)) == 'eva')
                && $result != 'hova')
            {
                $result = self::substring($result, 0, mb_strlen($result) - 3) . 'uj';
            }
            else if ((self::substring($result, mb_strlen($result) - 2, mb_strlen($result)) == 'nu' || self::substring($result, mb_strlen($result) - 2, mb_strlen($result)) == 'nų') && mb_strlen($result) > 3)
            {
                $result = self::substring($result, 0, mb_strlen($result) - 1);
            }
            else if (mb_substr($result, mb_strlen($result) - 1, 1) == 'ę')
            {
                if (mb_strrpos($result, 'ję') == mb_strlen($result) - 2)
                {
                    if (mb_strrpos($result, 'bję') == mb_strlen($result) - 3 || mb_strrpos($result, 'dję') == mb_strlen($result) - 3
                    || mb_strrpos($result, 'sję') == mb_strlen($result) - 3 || mb_strrpos($result, 'zję') == mb_strlen($result) - 3)
                    {
                        $result = self::substring($result, 0, mb_strlen($result) - 2) . 'òjm';
                    }
                    else
                    { 
                        $result = self::substring($result, 0, mb_strlen($result) - 1) . 'm';
                    }
                }
                else if ($result = 'vzę')
                { 
                    $result = 'vòzm';	
                }
                else
                {
                    $result = (self::substring($result, 0, mb_strlen($result) - 1) . 'n');	
                }
            }
            else if (mb_substr($result, mb_strlen($result) - 1, 1) == 'ų')
            {
                $result = self::substring($result, 0, mb_strlen($result) - 1) . 'm';
            }
            else if ((mb_substr($result, mb_strlen($result) - 1, 1) == 'i' || mb_substr($result, mb_strlen($result) - 1, 1) == 'y' ||
                    mb_substr($result, mb_strlen($result) - 1, 1) == 'o' || mb_substr($result, mb_strlen($result) - 1, 1) == 'u' ||
                    mb_substr($result, mb_strlen($result) - 1, 1) == 'ě' || mb_substr($result, mb_strlen($result) - 1, 1) == 'e') && mb_strlen($result) < 4)
            {
                if ($result == 'uči' )
                { 
                    $result = 'uči';
                }
                else if (mb_substr($result, 0, 1) == 'u')
                { 
                    $result = $result . 'ĵ'; 
                }
                else
                {
                    $result = $result . 'j';
                }
            }
            else if (mb_substr($result, mb_strlen($result) - 1, 1) == 'a' || mb_substr($result, mb_strlen($result) - 1, 1) == 'e' || mb_substr($result, mb_strlen($result) - 1, 1) == 'ě')
            {
                $result = $result . 'ĵ';
            }
        }
        else
        {
            if ((mb_strrpos($pts, 'se') == mb_strlen($pts) - 2 || mb_strrpos($pts, 'sę') == mb_strlen($pts) - 2) && mb_strlen($pts) > 2)
            {
                $pts = self::substring($pts, 0, mb_strlen($pts) - 3);
            }
            else if (mb_strpos($pts, 'se ') === 0 || mb_strpos($pts, 'sę ') === 0)
            {
                $pts = self::substring($pts, 3, mb_strlen($pts));
            }
    
            if (mb_strlen($pref) != 0)
            {
                if (mb_strpos($pts, $pref) != false)	
                { 
                    $pts = str_replace($pref, '', $pts);
                }
                else
                {
                    $pts = str_replace(self::substring($pref, 0, mb_strlen($pref) - 1), '', $pts);
                }
            }
            if (mb_strrpos($pts, '-') == mb_strlen($pts) - 1 || mb_strrpos($pts, 'm') == mb_strlen($pts) - 1 || mb_strrpos($pts, 'e') == mb_strlen($pts) - 1 ||
                mb_strrpos($pts, 'ų') == mb_strlen($pts) - 1 || mb_strrpos($pts, 'u') == mb_strlen($pts) - 1)
            {
                $result = self::substring($pts, 0, mb_strlen($pts) - 1);
            }
            else
            {
                $result = $pts;
            }
        }
    
        if ($result == 'j' || $result == 'jes' || $is == 'by' || ($result == 'je' && $is == 'bi'))
        {
            $result = 'jes';
        }
        else if ($result == 'věděĵ')
        {
            $result = 'vě';
        }
        else if ($result == 'jed')
        {
            $result = 'je';
        }
        else if ($result == 'jěd')
        {
            $result = 'jě';
        }
        else if ($result == 'jad')
        {
            $result = 'ja';
        }
        else if ($result == 'daĵ')
        {
            $result = 'da';
        }
        if ($result == 'jěhaĵ' || ($result == 'jě' && $is == 'jěha'))
        {
            $result = 'jěd';
        }
        if ($result == 'jehaĵ' || ($result == 'je' && $is == 'jeha'))
        {
            $result = 'jed';
        }
        if ($result == 'jahaĵ' || ($result == 'ja' && $is == 'jaha'))
        {
            $result = 'jad';
        }
    
        return $result;
    }

    static public function secondary_present_tense_stem ($ps)
    {
        $i = mb_strlen($ps) - 1;
        if (mb_substr($ps, $i, 1) == 'g')
        {
            $result = self::substring ($ps, 0, $i) . 'ž';
        }
        else if (mb_substr($ps, $i, 1) == 'k')
        {
            $result = self::substring($ps, 0, $i) . 'č';
        }
        else
        {
            $result = $ps;
        }

        return $result;
    }

    static public function l_participle ($pref, $is)
    {
        if ($is == 'vojd' || $is == 'vòjd')
        { 
            $result = 'všèl';
        }
        else if ($is == 'id' || $is == 'jd')							
        { 
            $result = $pref . 'šèl';
        }
        else if (self::substring($is, mb_strlen($is) - 2, mb_strlen($is)) == 'id' ||
                 self::substring($is, mb_strlen($is) - 2, mb_strlen($is)) == 'jd')		
        {
            $result = $pref . self::substring($is, 0, mb_strlen($is) - 2) . 'šèl';
        }
        else 											
        {
            $result = $pref . $is . 'l';
        }
        
        return $result;
    }

    static public function build_infinitive ($pref, $is, $refl)
    {
        if (mb_strrpos($is, 't') == mb_strlen($is) - 1)
        {
            $is = self::substring($is, 0, mb_strlen($is) - 1) . 's';
        }
        else if (mb_strrpos($is, 'id') == (mb_strlen($is) - 2))
        {
            $is = self::substring($is, 0, mb_strlen($is) - 1) . 'd';
        }
        else if (mb_strrpos($is, 'jd') == (mb_strlen($is) - 2))
        {
            $is = self::substring($is, 0, mb_strlen($is) - 1) . 'd';
        }
        else if (mb_strrpos($is, 'd') == (mb_strlen($is) - 1))
        {
            $is = self::substring($is, 0, mb_strlen($is) - 1) . 's';
        }
    
        $result = $pref . $is . 'tì' . $refl;
    
        return self::transliterate_back ($result);
    }

    static public function build_present ($pref, $ps, $psi, $refl)
    {
        $i = mb_strlen($ps) - 1;
    
        if ($ps == 'jes')
        {
            $result = 'jesm<br>jesi<br>jest (je)<br><br>jesmò<br>jeste<br>sųt';
        }
        else if ($ps == 'da')
        {
            $result = $pref . 'dam<br>' . $pref . 'daš<br>' . $pref . 'da<br><br>' . $pref . 'damò<br>' . $pref . 'date<br>' . $pref . 'dadųt';
        }
        else if ($ps == 'vě')
        {
            $result = $pref . 'věm<br>' . $pref . 'věš<br>' . $pref . 'vě<br><br>' . $pref . 'věmò<br>' . $pref . 'věte<br>' . $pref . 'vědųt';
        }
        else if ($ps == 'jě')
        {
            $result = $pref . 'jěm<br>' . $pref . 'jěš<br>' . $pref . 'jě<br><br>' . $pref . 'jěmò<br>' . $pref . 'jěte<br>' . $pref . 'jědųt';
        }
        else if ($ps == 'je')
        {
            $result = $pref . 'jem<br>' . $pref . 'ješ<br>' . $pref . 'je<br><br>' . $pref . 'jemò<br>' . $pref . 'jete<br>' . $pref . 'jedųt';
        }
        else if ($ps == 'ja')
        {
            $result = $pref . 'jam<br>' . $pref . 'jaš<br>' . $pref . 'ja<br><br>' . $pref . 'jamò<br>' . $pref . 'jate<br>' . $pref . 'jadųt';
        }
        else if (mb_substr($ps, $i, 1) == 'ĵ')
        {
            $cut = self::substring($ps, 0, mb_strlen($ps) - 1);
            $ps = $cut . 'j';
            $result = $pref . $ps . 'ų' . $refl . ', ' . $pref . $cut . 'm' . $refl . '<br>';
            $result = $result . $pref . $ps . 'eš' . $refl . ', ' . $pref . $cut . 'š' . $refl . '<br>';
            $result = $result . $pref . $ps . 'e' . $refl . ', ' . $pref . $cut . $refl . '<br><br>';
            $result = $result . $pref . $ps . 'emò' . $refl . ', ' . $pref . $cut . 'mo' . $refl . '<br>';
            $result = $result . $pref . $ps . 'ete' . $refl . ', ' . $pref . $cut . 'te' . $refl . '<br>';
            $result = $result . $pref . $ps . 'ųt' . $refl;
        }
        else if (mb_substr($ps, $i, 1) == 'i')
        {
            $cut = self::substring($ps, 0, mb_strlen($ps) - 1);
            $result = $pref . $cut . 'xų' . $refl . ', ' . $pref . $ps . 'm' . $refl . '<br>';
            $result = $result . $pref . $ps . 'š' . $refl . '<br>';
            $result = $result . $pref . $ps . $refl . '<br><br>';
            $result = $result . $pref . $ps . 'mò' . $refl . '<br>';
            $result = $result . $pref . $ps . 'te' . $refl . '<br>';
            $result = $result . $pref . $cut . 'ęt' . $refl . ', ' . $ps . 'jųt' . $refl;
        }
        else
        {
            $result = $pref . $ps . 'ų' . $refl . '<br>';
            $result = $result . $pref . $psi . 'eš' . $refl . '<br>';
            $result = $result . $pref . $psi . 'e' . $refl . '<br><br>';
            $result = $result . $pref . $psi . 'emò' . $refl . '<br>';
            $result = $result . $pref . $psi . 'ete' . $refl . '<br>';
            $result = $result . $pref . $ps . 'ųt' . $refl . '<br>';
        }
        $result = self::transliterate_back ($result);
        return $result;
    }

    static public function build_imperfect ($pref, $is, $refl)
    {
        $i = mb_strlen($is) - 1;

        if (in_array(mb_substr($is, $i, 1), self::$vowel) == false)
        {
            if (mb_substr($is, $i, 1) == 'k')
            {
                $impst = self::substring($is, 0, $i) . 'če';
            }
            else if (mb_substr($is, $i, 1) == 'g')
            {
                $impst = self::substring($is, 0, $i) . 'že';
            }
            else
            {
                $impst = $is . 'e';
            }
        }
        else if ($is == 'by')
        {
            $impst = 'bě';
        }
        else
        {
            $impst = $is;
        }
    
        $result = $pref . $impst . 'h' . $refl . '<br>';
        $result = $result . $pref . $impst . 'še' . $refl . '<br>';
        $result = $result . $pref . $impst . 'še' . $refl . '<br><br>';
        $result = $result . $pref . $impst . 'hmò' . $refl . '<br>';
        $result = $result . $pref . $impst . 'ste' . $refl . '<br>';
        $result = $result . $pref . $impst . 'hų' . $refl;
    
        return self::transliterate_back ($result);
    }

    static public function build_future ($infinitive, $ps)
    {
        if (($infinitive == 'biti' && ($ps == 'j' || $ps == 'je' || $ps == 'jes'))
            || $infinitive == 'byti' || $infinitive == 'bytì')
        {
            $infinitive = ''; 
        }
    
        $result = 'bųdų ' . $infinitive . '<br>';
        $result = $result . 'bųdeš ' . $infinitive . '<br>';
        $result = $result . 'bųde ' . $infinitive . '<br><br>';
        $result = $result . 'bųdemò ' . $infinitive . '<br>';
        $result = $result . 'bųdete ' . $infinitive . '<br>';
        $result = $result . 'bųdųt ' . $infinitive;

        return self::transliterate_back ($result);
    }

    static public function build_perfect ($lpa, $refl)
    {
        $result = '(jesm) ' . $lpa . '(a)' . $refl . '<br>';
        $result = $result . '(jesi) ' . $lpa . '(a)' . $refl . '<br>';
        $result = $result . '(jest) ' . $lpa . $refl . '<br>';
        $result = $result . '(jest) ' . $lpa . 'a' . $refl . '<br>';
        $result = $result . '(jest) ' . $lpa . 'o' . $refl . '<br><br>';
        $result = $result . '(jesmò) ' . $lpa . 'i' . $refl . '<br>';
        $result = $result . '(jeste) ' . $lpa . 'i' . $refl . '<br>';
        $result = $result . '(sųt) ' . $lpa . 'i' . $refl . '<br>';

        if (mb_strpos($result, 'šèl') !== false)
        {
            $result = self::idti($result);
        }

        return self::transliterate_back ($result);
    }

    static public function build_pluperfect($lpa, $refl)
    {
        $result = '(běh) ' . $lpa . '(a)' . $refl . '<br>';
        $result = $result . '(běše) ' . $lpa . '(a)' . $refl . '<br>';
        $result = $result . '(běše) ' . $lpa . $refl . '<br>';
        $result = $result . '(běše) ' . $lpa . 'a' . $refl . '<br>';
        $result = $result . '(běše) ' . $lpa . 'o' . $refl . '<br><br>';
        $result = $result . '(běhmo) ' . $lpa . 'i' . $refl . '<br>';
        $result = $result . '(běste) ' . $lpa . 'i' . $refl . '<br>';
        $result = $result . '(běhų) ' . $lpa . 'i' . $refl . '<br>';

        if (mb_strpos($result, 'šèl') !== false)
        {
            $result = self::idti($result);
        }

        return self::transliterate_back ($result);
    }

    static public function build_conditional ($lpa, $refl)
    {
        $result = 'by(h) ' . $lpa . '(a)' . $refl . '<br>';
        $result = $result . 'by(s) ' . $lpa . '(a)' . $refl . '<br>';
        $result = $result . 'by ' . $lpa . $refl . '<br>';
        $result = $result . 'by ' . $lpa . 'a' . $refl . '<br>';
        $result = $result . 'by ' . $lpa . 'o' . $refl . '<br><br>';
        $result = $result . 'by(hmò) ' . $lpa . 'i' . $refl . '<br>';
        $result = $result . 'by(ste) ' . $lpa . 'i' . $refl . '<br>';
        $result = $result . 'by ' . $lpa . 'i' . $refl . '<br>';
    
        if (mb_strpos($result, 'šèl') !== false)
        {
            $result = self::idti($result); 
        }

        return self::transliterate_back ($result);
    }

    static public function build_imperative ($pref, $ps, $refl)
    {
        $i = mb_strlen($ps) - 1;
    
        if ($ps == 'jes')									
        {
            $p2s = 'bųď'; 
        }
        else if ($ps == 'da')									
        {
            $p2s = $pref . 'daj'; 
        }
        else if ($ps == 'je')									
        {
            $p2s = $pref . 'jeď';
        }
        else if ($ps == 'jě')									
        { 
            $p2s = $pref . 'jěď';
        }
        else if ($ps == 'ja')									
        { 
            $p2s = $pref . 'jaď'; 
        }
        else if ($ps == 'vě')									
        {
            $p2s = $pref . 'věď'; 
        }
        else if (mb_substr($ps, $i, 1) == 'ĵ' || mb_substr($ps, $i, 1) == 'j')			
        { 
            $p2s = $pref . $ps; 
        }
        else if (mb_substr($ps, $i, 1) == 'a' || mb_substr($ps, $i, 1) == 'e' || mb_substr($ps, $i, 1) == 'ě')	
        { 
            $p2s = $pref . $ps . 'j'; 
        }
        else if (mb_substr($ps, $i, 1) == 'i')								
        {
            $p2s = $pref . $ps; 
        }
        else
        {
            $p2s = $pref . $ps . 'i';
        }
    
        $result = $p2s . $refl . ', ' . $p2s . 'mò' . $refl . ', ' . $p2s . 'te' . $refl;
        $result = str_replace ('jij', 'j', $result); $result = str_replace ('ĵij','ĵ', $result);

        return self::transliterate_back ($result);
    }

    static public function build_prap ($pref, $ps, $refl)
    {
        $i = mb_strlen($ps) - 1;
    
        if ($ps == 'jes')		
        { 
            $ps = $pref . 'sų';
        }
        else if ($ps == 'da')		
        { 
            $ps = $pref . 'dadų'; 
        }
        else if ($ps == 'je')		
        {
            $ps = $pref . 'jedų'; 
        }
        else if ($ps == 'jě')		
        { 
            $ps = $pref . 'jědų';
        }
        else if ($ps == 'ja')		
        {
            $ps = $pref . 'jadų';
        }
        else if ($ps == 'vě')			
        {
            $ps = $pref . 'vědų'; 
        }
    
        else if (mb_substr($ps, $i, 1) == 'a' || mb_substr($ps, $i, 1) == 'e' || mb_substr($ps, $i, 1) == 'ě')	
        {
            $ps = $pref . $ps . 'jų'; 
        }
        else if (mb_substr($ps, $i, 1) == 'i')								
        {
            $cut = self::substring($ps, 0, mb_strlen($ps) - 1);
            $ps = $pref . $cut . 'ę';
        }
        else
        {
            $ps = $pref . $ps . 'ų';
        }
    
        $result = $ps . 'ćí (' . $ps . 'ćá, ' . $ps . 'ćé)' . $refl;

        return self::transliterate_back ($result);
    }

    static public function build_prpp ($pref, $ps, $psi)
    {
        $result = '';
    
        if ($ps == 'jes')	
        { 
            $result = '—';
        }
        else if ($ps == 'da')	
        { 
            $ps = 'dado'; 
        }
        else if ($ps == 'je')
        { 
            $ps = 'jedo'; 
        }
        else if ($ps == 'jě')
        { 
            $ps = 'jědo';
        }
        else if ($ps == 'ja')
        { 
            $ps = 'jado';
        }
        else if ($ps == 'vě')
        {
            $ps = 'vědo';
        }
    
        $i = mb_strlen($ps) - 1;
        if (mb_substr($ps, $i, 1) == 'ĵ')
        { 
            $cut = self::substring($ps, 0, $i);
            $ps = $cut . 'j';
            $result = $pref . $ps . 'emý (—á, —œ)' . ', ' . $pref . $cut . 'mý (—á, —œ)';
        }
        else if (mb_substr($ps, $i, 1) == 'j')
        {
            $result = $pref . $psi . 'emý (' . $pref . $psi . 'emá, ' . $pref . $psi . 'emœ)';
        }
        else if (mb_substr($ps, $i, 1) == 's' || mb_substr($ps, $i, 1) == 'z'
            ||   mb_substr($ps, $i, 1) == 't' || mb_substr($ps, $i, 1) == 'd'
            ||   mb_substr($ps, $i, 1) == 'l')							
        { 
            $result = $pref . $ps . 'omý (' . $pref . $ps . 'omá, ' . $pref . $ps . 'omœ)';
        }
        else if (mb_substr($ps, $i, 1) == 'i' || mb_substr($ps, $i, 1) == 'o')
        {
            $result = $pref . $ps . 'mý (' . $pref . $ps . 'má, ' . $pref . $ps . 'mœ)'; 
        }
        else if ($result != '—')								
        {
            $result = $pref . $psi . 'emý (' . $pref . $psi . 'emá, ' . $pref . $psi . 'emœ)';
        }

        return self::transliterate_back ($result);
    }

    static public function build_pfap ($lpa, $refl)
    {
        if (in_array(mb_substr($lpa, mb_strlen($lpa) - 2, 1), self::$vowel) == false)
        {
            $result = self::substring($lpa, 0, mb_strlen($lpa) - 1) . 'ši' . $refl;
        }
        else
        {
            $result = self::substring($lpa, 0, mb_strlen($lpa) - 1) . 'vši' . $refl;
        }

        if (mb_strpos($result, 'šèv') !== false)
        { 
            $result = self::idti($result);
        }

        return self::transliterate_back($result);
    }

    static public function build_pfpp ($pref, $is, $psi)
    {
        $i = mb_strlen($is) - 1;

        if ((mb_substr($is, $i, 1) != 'j' && (mb_substr($psi, mb_strlen($psi) - 1, 1) == 'j' && $i < 4 && mb_substr($is, 0, 1) != 'u') || $is == 'by') || mb_substr($is, $i, 1) == 'ę')
        {
            $ppps = $pref . $is . 't';
        }
        else if ((mb_substr($is, $i, 1) == 'ų' || mb_substr($is, $i, 1) == 'u') && mb_substr($i, $i - 1, 1) == 'n')
        {
            $ppps = $pref . $psi . 'en';
        }
        else if (mb_substr($is, $i, 1) == 'ų' || mb_substr($is, $i, 1) == 'u')
        {
            $ppps = $pref . $is . 't';
        }
        else if (mb_substr($is, $i, 1) == 'a' || mb_substr($is, $i, 1) == 'e' || mb_substr($is, $i, 1) == 'ě')
        {
            $ppps = $pref . $is . 'n';
        }
        else if (mb_substr($is, $i, 1) == 'i')
        {
            $ppps = $pref . $is . 'Xen';
            $ppps = str_replace('stiX', 'šćX', $ppps);
            $ppps = str_replace('zdiX', 'žđX', $ppps);
            $ppps = str_replace('siX', 'šX', $ppps);
            $ppps = str_replace('ziX', 'žX', $ppps);
            $ppps = str_replace('tiX', 'ćX', $ppps);
            $ppps = str_replace('diX', 'dźX', $ppps);
            $ppps = str_replace('riX', 'řX', $ppps);
            $ppps = str_replace('liX', 'ľX', $ppps);
            $ppps = str_replace('niX', 'ňX', $ppps);
            $ppps = str_replace('jiX', 'jX', $ppps);
            $ppps = str_replace('šiX', 'šX', $ppps);
            $ppps = str_replace('žiX', 'žX', $ppps);
            $ppps = str_replace('čiX', 'čX', $ppps);
            $ppps = str_replace('iX', 'jX', $ppps);
            $ppps = str_replace('X', '', $ppps);
        }
        else if (mb_substr($is, $i, 1) == 'k' || mb_substr($is, $i, 1) == 'g')
        {
            $ppps = $pref . $psi . 'en';
        }
        else
        {
            $ppps = $pref . $is . 'en';
        }
        $result = $ppps . 'ý (' . $ppps . 'á, ' . $ppps . 'ó)';

        return self::transliterate_back ($result);
    }

    static public function build_gerund($pfpp, $refl)
    {
        $ppps = mb_strpos($pfpp, '(') - 2;
        $result = self::substring($pfpp, 0, $ppps) . 'ıje' . $refl;

        return self::transliterate_back($result);
    }

    static public function idti ($sel)
    {
        $sel = str_replace('šèl(a)', 'šèl/šla', $sel);
        $sel = str_replace('šèl(a)', 'šèl/šla', $sel);
        $sel = str_replace('všèl/šla', 'všèl/vòšla', $sel);
        $sel = str_replace('všèl/šla', 'všèl/vòšla', $sel);
        $sel = preg_replace('/šèla/', 'šla', $sel);
        $sel = preg_replace('/šèlo/', 'šlo', $sel);
        $sel = preg_replace('/šèli/', 'šli', $sel);
        $sel = preg_replace('/všl/', 'vòšl', $sel);
        $sel = preg_replace('/iz[oò]š/', 'izš', $sel);
        $sel = preg_replace('/ob[oò]š/', 'obš', $sel);
        $sel = preg_replace('/od[oò]š/', 'odš', $sel);
        $sel = preg_replace('/pod[oò]š/', 'podš', $sel);
        $sel = preg_replace('/nad[oò]š/', 'nadš', $sel);
        
        return $sel;
    }

    static public function transliterate_back($iW)
    {
        $iW = str_replace('stx','šć', $iW); $iW = str_replace('zdx','ždź', $iW);
        $iW = str_replace('sx','š', $iW); $iW = str_replace('šx','š', $iW); $iW = str_replace('zx','ž', $iW); $iW = str_replace('žx','ž', $iW);
        $iW = str_replace('cx','č', $iW); $iW = str_replace('čx','č', $iW); $iW = str_replace('tx','ć', $iW); $iW = str_replace('dx','dź', $iW);
        $iW = str_replace('jx','j', $iW); $iW = str_replace('x','j', $iW);

        return str_replace('-', '', $iW);
    }

    public static function substring($str, $from = 0, $to = false)
    {
        if ($to !== false)
        {
            if ($from == $to || ($from <= 0 && $to < 0))
            {
                return '';
            }

            if ($from > $to)
            {
                $from_copy = $from;
                $from = $to;
                $to = $from_copy;
            }
        }

        if ($from < 0)
        {
            $from = 0;
        }

        $substring = $to === false ? mb_substr($str, $from) : mb_substr($str, $from, $to - $from);
        return ($substring === false) ? '' : $substring;
    }
}
