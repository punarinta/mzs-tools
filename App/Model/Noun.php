<?php

namespace App\Model;

/**
 * Class Noun
 * @package App\Model
 */
class Noun
{
    private static $originalGender = '';

    /**
     * @param $noun
     * @param string $gender
     * @return array
     * @throws \Exception
     */
    public static function morph($noun, $gender = 'm2')   // m2, m1, f, n
    {
        if ($gender == 'm')
        {
            // make it inanimate if animacy is unknown
            $gender = 'm2';
        }

        self::$originalGender = $gender;
        $noun = str_replace(' ', '', $noun);

        if (mb_strlen($noun) == 0)
        {
            throw new \Exception('Empty input');
        }
        else
        {
            $noun = str_replace('è', '(e)', $noun);
            $noun = str_replace('ò', '(o)', $noun);

            $n1	= self::substring($noun, 0, mb_strlen($noun) - 2);
            $n2	= self::substring($noun, mb_strlen($noun) - 2, mb_strlen($noun));
            $n2 = str_replace('ť', 't^', $n2);
            $n2 = str_replace('ď', 'd^', $n2);
            $n2 = str_replace('[ňń]', 'n^', $n2);
            $n2 = str_replace('[ľĺ]', 'l^', $n2);
            $n2 = str_replace('[řŕ]', 'r^', $n2);
            $n2 = str_replace('ś', 's^', $n2);
            $n2 = str_replace('ź', 'z^', $n2);
            $n2 = str_replace('j', 'j^', $n2);
            $n2 = str_replace('š', 'š^', $n2);
            $n2 = str_replace('ž', 'ž^', $n2);
            $n2 = str_replace('č', 'č^', $n2);
            $n2 = str_replace('ć', 'ć^', $n2);
            $n2 = str_replace('c', 'c^', $n2);
            $noun	= $n1 . $n2;
    
            $noun 	= str_replace('(e)', 'è', $noun);
            $noun 	= str_replace('(o)', 'ò', $noun);
    
            $gender	= self::establish_gender ($noun, $gender);
            $root	= self::establish_root ($noun, $gender);

            $plroot	= self::establish_plural_root ($root);
            $plgen	= self::establish_plural_gender ($root, $plroot, $gender);
            $nom_sg	= self::nominative_sg ($noun, $root, $gender);
            $gen_sg	= self::genitive_sg ($root, $gender);
            $dat_sg	= self::dative_sg ($root, $gender);
            $acc_sg	= self::accusative_sg ($nom_sg, $root, $gender);
            $ins_sg	= self::instrumental_sg ($root, $gender);
            $loc_sg	= self::locative_sg ($root, $gender);
            $voc_sg	= self::vocative_sg ($nom_sg, $root, $gender);
            $nom_pl	= self::nominative_pl ($plroot, $plgen);
            $gen_pl	= self::genitive_pl ($plroot, $plgen);
            $dat_pl	= self::dative_pl ($plroot, $gender);
            $acc_pl	= self::accusative_pl ($nom_pl, $gen_pl, $plgen);
            $ins_pl	= self::instrumental_pl ($plroot, $gender);
            $loc_pl	= self::locative_pl ($plroot, $gender);
        }

        return array
        (
            'sg' => array
            (
                'nom' => $nom_sg,
                'gen' => $gen_sg,
                'dat' => $dat_sg,
                'acc' => $acc_sg,
                'ins' => $ins_sg,
                'loc' => $loc_sg,
                'voc' => $voc_sg,
            ),
            'pl' => array
            (
                'nom' => $nom_pl,
                'gen' => $gen_pl,
                'dat' => $dat_pl,
                'acc' => $acc_pl,
                'ins' => $ins_pl,
                'loc' => $loc_pl,
            ),
        );
    }

    /**
     * @param $noun
     * @param $gender
     * @return string
     * @throws \Exception
     */
    protected static function establish_gender($noun, $gender)
    {
        if (mb_strlen($noun) == 0)
        {
            throw new \Exception('Empty input');
        }
        else if ($noun == 'den' || $noun == 'dèn' || $noun == 'den^' || $noun == 'dèn^')
        {
            $result = 'm3';
        }
        else if ($gender == 'm2' && mb_strrpos($noun, 'e') == (mb_strlen($noun) - 2) && mb_strrpos($noun, 'n') == mb_strlen($noun) - 1)
        {
            $result = 'm3';
        }
        else if ($gender == 'm2' && mb_strrpos($noun, 'e') == (mb_strlen($noun) - 3) && mb_strrpos($noun, 'n') == mb_strlen($noun) - 2 && mb_strrpos($noun, '^') == (mb_strlen($noun) - 1))
        {
            $result = 'm3';
        }
        else if ($gender == 'f' && mb_strrpos($noun, 'v') == mb_strlen($noun) - 1)
        { 
            $result = 'f3'; 
        }
        else if (($noun == 'mati') || ($noun == 'doč^i') || ($noun == 'doć^i'))
        {
            $result = 'f3'; 
        }
        else if ((mb_strrpos($noun, 'a') == (mb_strlen($noun) - 1)) || (mb_strrpos($noun, 'i') == (mb_strlen($noun) - 1)))
        { 
            $result = 'f1'; 
        }
        else if (mb_strrpos($noun, 'ę') == (mb_strlen($noun) - 1))
        { 
            $result = 'n2'; 
        }
        else if ((mb_strrpos($noun, '^') != (mb_strlen($noun) - 2)) && (mb_strrpos($noun, 'e') == (mb_strlen($noun) - 1)))
        {
            $result = 'n2';
        }
        else if ((mb_strrpos($noun, 'o') == (mb_strlen($noun) - 1)) || (mb_strrpos($noun, 'e') == (mb_strlen($noun) - 1)))
        {
            $result = 'n1';
        }
        else if ((mb_strrpos($noun, 'u') == (mb_strlen($noun) - 2)) && (mb_strrpos($noun, 'm') == (mb_strlen($noun) - 1)))
        {
            $result = 'n1';
        }
        else if ($gender == 'm1')
        {
            $result = 'm1'; 
        }
        else if ($gender == 'f')
        {
            $result = 'f2';
        }
        else
        {
            $result = 'm2';
        }
        
        return $result;
    }

    /**
     * @param $noun
     * @param $gender
     * @return mixed|string
     */
    protected static function establish_root($noun, $gender)
    {
        if ($noun == 'den' || $noun == 'dèn' || $noun == 'den^' || $noun == 'dèn^')
        { 
            $result = 'dn'; 
        }
        else if ($noun == 'lèv')
        {
            $result = 'ľv'; 
        }
        else if ($gender == 'm3')
        { 
            $result = $noun . '%';
            $result = str_replace('^%','%', $result);
            $result = str_replace('%','', $result);
        }
        else if ($noun == 'mati' || $noun == 'doč^i' || $noun == 'doć^i')
        {
            $result = self::substring($noun, 0, mb_strlen($noun) - 1) . 'er'; 
        }
        else if ($gender == 'f3' && (mb_strrpos($noun, 'o') == mb_strlen($noun) - 2 || mb_strrpos($noun, 'ò') == mb_strlen($noun) - 2) && mb_strrpos($noun, 'v') == mb_strlen($noun) - 1)
        {
            $result = self::substring($noun, 0, mb_strlen($noun) - 2) . 'v';
        }
        else if ($gender == 'f3')
        { 
            $result = $noun; 
        }
        else if ($gender == 'n2' && mb_strrpos($noun, 'm') == mb_strlen($noun) - 2)
        { 
            $result = self::substring($noun, 0, mb_strlen($noun) - 1) . 'en';
        }
        else if ($gender == 'n2')
        { 
            $result = self::substring($noun, 0, mb_strlen($noun) - 1) . 'ęt'; 
        }
        else if (mb_strrpos($noun, 'i') == mb_strlen($noun) - 1)
        { 
            $result = self::substring($noun, 0, mb_strlen($noun) - 1) . '^';
        }
        else if (mb_strrpos($noun, 'a') == mb_strlen($noun) - 1 || mb_strrpos($noun, 'e') == mb_strlen($noun) - 1 ||
                 mb_strrpos($noun, 'o') == mb_strlen($noun) - 1)
        { 
            $result = self::substring($noun, 0, mb_strlen($noun) - 1);
        }
        else if (mb_strrpos($noun, 'u') == mb_strlen($noun) - 2 && mb_strrpos($noun, 'm') == mb_strlen($noun) - 1)
        { 
            $result = self::substring($noun, 0, mb_strlen($noun) - 2);
        }
        else if ($gender == 'f2' && mb_strrpos($noun, '^') == mb_strlen($noun) - 1)
        { 
            $result = self::substring($noun, 0, mb_strlen($noun) - 1);
        }
        else
        { 
            $result = $noun;
        }
    
        $filler_e = mb_strrpos($result, 'è');
        $filler_o = mb_strrpos($result, 'ò');
        
        if ($filler_e != false || $filler_o != false)
        {
            if ($filler_o > $filler_e) 
            { 
                $filler = $filler_o;
            }
            else				
            { 
                $filler = $filler_e;
            }
            $result = self::substring($result, 0, $filler) . self::substring($result, $filler . 1, mb_strlen($result));
        }

        return $result;
    }

    /**
     * @param $root
     * @return string
     */
    protected static function establish_plural_root ($root)
    {
        if ($root == 'dětęt' || $root == 'detet' || $root == 'dětet' || $root == 'detęt')
        { 
            $result = 'dět^';
        }
        else if ($root == 'človek' || $root == 'člověk')
        { 
            $result = 'ľud^';
        }
        else if ($root == 'ok')
        { 
            $result = 'oč^'; 
        }
        else if ($root == 'uh')
        { 
            $result = 'uš^'; 
        }
        else if (self::substring($root, mb_strlen($root) - 3, mb_strlen($root)) == 'nin')
        { 
            $result = self::substring($root, 0, mb_strlen($root) - 2); 
        }
        else
        { 
            $result = $root; 
        }
        
        return $result;
    }

    /**
     * @param $root
     * @param $plroot
     * @param $gender
     * @return string
     */
    protected static function establish_plural_gender ($root, $plroot, $gender)
    {
        if ($root != $plroot && mb_strpos($plroot, 'n') == false)
        { 
            $result = 'f2'; 
        }
        else if ($gender == 'f1' && self::$originalGender == 'm1')
        { 
            $result = 'm1'; 
        }
        else
        { 
            $result = $gender;
        }
        
        return $result;
    }

    /**
     * @param $noun
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function nominative_sg ($noun, $root, $gender)
    {
        if ($gender == 'f2')
        { 
            $result = $root . '^';
        }
        else if ($gender == 'm3' && $root == 'dn')
        { 
            $result = 'den / deň'; 
        }
        else if ($gender == 'm3')
        { 
            $result = $root . ' / ' . $root . '^';
        }
        else
        { 
            $result = $noun;
        }

        return self::rules($result);
    }

    /**
     * @param $noun
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function accusative_sg ($noun, $root, $gender)
    {
        if ($gender == 'm1')				
        {	
            $result = $root . 'a';
        }
        else if ($gender == 'f1')				
        {	
            $result = $root . 'ų';
        }
        else
        {
            $result = $noun;
        }

        return self::rules($result);
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function genitive_sg ($root, $gender)
    {
        $result = '';
        if ($gender == 'm1' || $gender == 'm2' || $gender == 'n1')
        {
            $result = $root . 'a';
        }
        else if ($gender == 'f1')						
        {
            $result = $root . 'y';	
        }
        else if ($gender == 'f2')					
        {
            $result = $root . 'i';
        } 
        else if ($gender == 'f3')						
        {	
            $result = $root . 'e / ' . $root . 'i'; 	
        }
        else if ($gender == 'm3')					
        {	
            $result = $root . 'e / ' . $root . 'ja'; 	
        }
        else if ($gender == 'n2')					
        {	
            $result = $root . 'e / ' . $root . 'a'; 
        }

        return self::rules($result);
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function dative_sg ($root, $gender)
    {
        $result = '';
        if ($gender == 'm1' || $gender == 'm2' || $gender == 'n1')
        {
            $result = $root . 'u';
        }
        else if ($gender == 'f1')
        {
            if (mb_strrpos($root, '^') == mb_strlen($root) - 1)
            {
                $result = $root . 'i';
            }
            else
            {
                    $result = $root . 'ě';
            }
        }
        else if ($gender == 'f2')
        {
            $result = $root . 'i';
        }
        else if ($gender == 'f3')
        {
            $result = $root . 'i';
        }
        else if ($gender == 'm3')
        {
            $result = $root . 'i / ' . $root . 'ju';
        }
        else if ($gender == 'n2')
        {
            $result = $root . 'i / ' . $root . 'u';
        }

        return self::rules($result);
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function instrumental_sg ($root, $gender)
    {
        $result = '';
        if (($gender == 'm1') || ($gender == 'm2') || ($gender == 'n1'))		{	$result = $root . 'om';	}
        else if ($gender == 'f1')						{	$result = $root . 'ojų';	}
        else if ($gender == 'f2')						{	$result = $root . 'jų'; 	}
        else if ($gender == 'f3')						{	$result = $root . 'jų'; 	}
        else if ($gender == 'm3')						{	$result = $root . 'em / ' . $root . 'jem'; 	}
        else if ($gender == 'n2')						{	$result = $root . 'em / ' . $root . 'om'; 	}

        return self::rules($result);
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function locative_sg($root, $gender)
    {
        $result = '';
        
        if ($gender == 'm1' || $gender == 'm2' || $gender == 'n1')
        {
            if (mb_strrpos($root, 'k') == mb_strlen($root) - 1)		
            {	
                $result = $root . 'u';
            }
            else if (mb_strrpos($root, 'g') == mb_strlen($root) - 1)	
            {
                $result = $root . 'u';	
            }
            else if (mb_strrpos($root, 'h') == mb_strlen($root) - 1)		
            {
                $result = $root . 'u';
            }
            else
            {
                $result = $root . 'ě';
            }
        }
        else if ($gender == 'f1')						
        {
            $result = $root . 'ě';
        }
        else if ($gender == 'f2')						
        {
            $result = $root . 'i'; 
        }
        else if ($gender == 'f3')						
        {
            $result = $root . 'i'; 
        }
        else if ($gender == 'm3')						
        {
            $result = $root . 'i / ' . $root . 'ji'; 	
        }
        else if ($gender == 'n2')						
        {
            $result = $root . 'i / ' . $root . 'ě'; 	
        }

        return self::rules($result);
    }

    /**
     * @param $nom_sg
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function vocative_sg($nom_sg, $root, $gender)
    {
        if (($gender == 'm1') || ($gender == 'm2'))
        {	
            if (mb_strrpos($nom_sg, 'ec') == mb_strlen($nom_sg) - 2)
            {
                $result = self::substring($root, 0, mb_strlen($root) - 2) . 'če';
            }
            else if (mb_strrpos($root, '^') == mb_strlen($root) - 1)
            {
                $result = $root . 'u';
            }
            else if (mb_strrpos($root, 'k') == mb_strlen($root) - 1)
            {
                $result = self::substring($root, 0, mb_strlen($root) - 1) . 'če';
            }
            else if (mb_strrpos($root, 'g') == mb_strlen($root) - 1)
            {
                $result = self::substring($root, 0, mb_strlen($root) - 1) . 'že';
            }
            else if (mb_strrpos($root, 'h') == mb_strlen($root) - 1)
            {
                $result = self::substring($root, 0, mb_strlen($root) - 1) . 'še';
            }
            else
            {
                $result = $root . 'e';
            }
        }
        else if ($gender == 'f1')
        {
            $result = $root . '#o';
        }
        else
        {
            $result = '—';
        }

        return self::rules($result);
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function nominative_pl($root, $gender)
    {
        if (mb_substr($gender, 0, 1) == 'n')						
        {
            $result = $root . 'a';
        }
        else if ($gender == 'f1' || $gender == 'm2')
        {
            $result = $root . 'y';
        }
        else if ($gender == 'm3')
        {
            $result = $root . 'i / ' . $root . 'je';
        }
        else
        {
            $result = $root . 'i';
        }

        return self::rules($result);
    }

    /**
     * @param $nom_pl
     * @param $gen_pl
     * @param $gender
     * @return mixed
     */
    protected static function accusative_pl($nom_pl, $gen_pl, $gender)
    {
        if ($gender == 'm1')
        {
            $result = $gen_pl;
        }
        else
        {
            $result = $nom_pl;
        }

        return $result;
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function genitive_pl($root, $gender)
    {
        if ($gender == 'f1' || mb_substr($gender, 0, 1) == 'n')		
        {
            $result = $root;	
        }
        else if ($gender == 'm3')					
        {	
            $result = $root . 'ev / ' . $root . 'jev'; 
        }
        else if (mb_substr($gender, 0, 1) == 'm')					
        {	
            $result = $root . 'ov';	
        }
        else
        {
            $result = $root . 'ij';
        }

        return self::rules($result);
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function dative_pl($root, $gender)
    {
        if ($gender == 'f2')							
        {
            $result = $root . '^am';	
        }
        else if ($gender == 'm3')						
        {
            $result = $root . 'am / ' . $root . 'jam';
        }
        else
        {
            $result = $root . 'am';
        }

        return self::rules($result);
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function instrumental_pl($root, $gender)
    {
        if ($gender == 'f2')							
        {
            $result = $root . '^ami';	
        }
        else if ($gender == 'm3')						
        {
            $result = $root . 'ami / ' . $root . 'jami'; 
        }
        else
        {
            $result = $root . 'ami';
        }

        return self::rules($result);
    }

    /**
     * @param $root
     * @param $gender
     * @return mixed
     */
    protected static function locative_pl ($root, $gender)
    {
        if ($gender == 'f2')							
        {	
            $result = $root . '^ah';
        }
        else if ($gender == 'm3')						
        {
            $result = $root . 'ah / ' . $root . 'jah'; 
        }
        else
        {
            $result = $root . 'ah';
        }

        return self::rules($result);
    }

    /**
     * @param $word
     * @return mixed
     */
    protected static function rules ($word)
    {
        $word = str_replace('^o', '^e', $word);
        $word = str_replace('^y', '^e', $word);
        $word = str_replace('^ě', 'i', $word);
        $word = str_replace('^i', 'i', $word);
        $word = str_replace('#', '', $word);
        $word = str_replace('č^', 'č', $word); $word = str_replace('š^', 'š', $word); $word = str_replace('ž^', 'ž', $word); $word = str_replace('ć^', 'ć', $word); $word = str_replace('c^', 'c', $word);
        $word = str_replace('l^', 'lj', $word); $word = str_replace('n^', 'ń', $word); $word = str_replace('r^', 'ŕ', $word); $word = str_replace('j^', 'j', $word);
        $word = str_replace('t^', 'ť', $word); $word = str_replace('d^', 'ď', $word); $word = str_replace('s^', 'ś', $word); $word = str_replace('z^', 'ź', $word);
        $word = str_replace('jy', 'ji', $word);
        $word = str_replace('ky', 'ki', $word);
        $word = str_replace('gy', 'gi', $word);
        $word = str_replace('hy', 'hi', $word);
        $word = str_replace('cy', 'ci', $word);
        
        return $word;
    }

    /**
     * @param $str
     * @param int $from
     * @param bool $to
     * @return string
     */
    protected static function substring($str, $from = 0, $to = false)
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
