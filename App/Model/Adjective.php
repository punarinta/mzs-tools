<?php

namespace App\Model;

class Adjective
{
    public static $vowel = ['a','å','e','ě','ę','i','o','ò','u','ų','y'];
    public static $liquid = ['l','r','ŕ'];
    public static $nasal = ['n','m'];
    
    static function morph($adj)
    {
        $adj = str_replace(' ', '', $adj);
        if (mb_strlen($adj) == 0)
        {
            throw new \Exception('boo');
        }
        else
        {
            $root = self::establish_root($adj);
            if (mb_strlen($root) == 0)
            {
                throw new \Exception('boo');
            }
            else
            {
                $m_nom_sg	= self::m_nominative_sg($adj, $root); $m_nom_sg = self::rules($m_nom_sg);
                $m_acc_sg	= self::m_accusative_sg($adj, $root); $m_acc_sg = self::rules($m_acc_sg); $m_acc_sg = self::rules($m_acc_sg);
                $f_nom_sg	= self::f_nominative_sg($root); $f_nom_sg = self::rules($f_nom_sg);
                $n_nom_sg	= self::n_nominative_sg($root); $n_nom_sg = self::rules($n_nom_sg);
                $mn_gen_sg	= self::mn_genitive_sg($root); $mn_gen_sg = self::rules($mn_gen_sg);
                $mn_dat_sg	= self::mn_dative_sg($root); $mn_dat_sg = self::rules($mn_dat_sg);
                $mn_ins_sg	= self::mn_instrumental_sg($root); $mn_ins_sg = self::rules($mn_ins_sg);
                $mn_loc_sg	= self::mn_locative_sg($root); $mn_loc_sg = self::rules($mn_loc_sg);
                $f_acc_sg	= self::f_accusative_sg($root); $f_acc_sg = self::rules($f_acc_sg);
                $f_gdl_sg	= self::f_gendatloc_sg($root); $f_gdl_sg = self::rules($f_gdl_sg);
                $f_ins_sg	= self::f_instrumental_sg($root); $f_ins_sg = self::rules($f_ins_sg);
                $m_nom_pl	= self::m_nominative_pl($root); $m_nom_pl = self::rules($m_nom_pl); $m_nom_pl = self::rules($m_nom_pl);
                $m_acc_pl	= self::m_accusative_pl($root); $m_acc_pl = self::rules($m_acc_pl); $m_acc_pl = self::rules($m_acc_pl);
                $fn_nom_pl	= self::fn_nominative_pl($root); $fn_nom_pl = self::rules($fn_nom_pl);
                $glo_pl		= self::genloc_pl($root); $glo_pl = self::rules($glo_pl);
                $dat_pl		= self::dative_pl($root); $dat_pl = self::rules($dat_pl);
                $ins_pl		= self::instrumental_pl($root); $ins_pl = self::rules($ins_pl);
                $adv		= self::adverb($root); $adv = self::rules($adv);
                $comp_adj	= self::comparative_adj($root); $comp_adj = self::rules($comp_adj);
                $comp_adv	= self::comparative_adv($root); $comp_adv = self::rules($comp_adv);
                $sup_adj	= self::superlative($root, $comp_adj, 'adj'); $sup_adj = self::rules($sup_adj);
                $sup_adv	= self::superlative($root, $comp_adv, 'adv'); $sup_adv = self::rules($sup_adv);
            }
        }

        return array
        (
            'sg' => array
            (
                'm' => array
                (
                    $m_nom_sg,
                    $mn_gen_sg,
                    $mn_dat_sg,
                    $m_acc_sg,
                    $mn_ins_sg,
                    $mn_loc_sg,
                ),
                'f' => array
                (
                    $f_nom_sg,
                    $f_gdl_sg,
                    $f_gdl_sg,
                    $f_acc_sg,
                    $f_ins_sg,
                    $f_gdl_sg,
                ),
                'n' => array
                (
                    $n_nom_sg,
                    $mn_gen_sg,
                    $mn_dat_sg,
                    $n_nom_sg,
                    $mn_ins_sg,
                    $mn_loc_sg,
                ),
            ),
            'pl' => array
            (
                'm' => array
                (
                    $m_nom_pl,
                    $glo_pl,
                    $dat_pl,
                    $m_acc_pl,
                    $ins_pl,
                    $glo_pl,
                ),
                'f' => array
                (
                    $fn_nom_pl,
                    $glo_pl,
                    $dat_pl,
                    $fn_nom_pl,
                    $ins_pl,
                    $glo_pl,
                ),
                'n' => array
                (
                    $fn_nom_pl,
                    $glo_pl,
                    $dat_pl,
                    $fn_nom_pl,
                    $ins_pl,
                    $glo_pl,
                ),
            ),
            'comp' => $comp_adj,
            'sup' => $sup_adj,
            'adv' => $adv,
            'adv_comp' => $comp_adv,
            'adv_sup' => $sup_adv,
        );
    }

    static function establish_root($adj)
    {
        if (($adj == 'naš') || ($adj == 'vaš'))
        {
            $result = $adj . '|^';
        }
        else if ((strrpos($adj, 'č') == mb_strlen($adj) - 3) && (strrpos($adj, 'i') == mb_strlen($adj) - 2) && (strrpos($adj, 'j') == mb_strlen($adj) - 1))
        {
            $result = $adj . '|^';
        }
        else if (($adj == 'sej') || ($adj == 'sjej'))
        {
            $result = self::substring($adj, 0, mb_strlen($adj) - 2) . '|^';
        }
        else if (($adj == 'veś') || ($adj == 'ves'))
        {
            $result = 'vs|^';
        }
        else if ($adj == 'onoj')
        {
            $result = 'on|';
        }
        else if (($adj == 'ovoj') || ($adj == 'ov'))
        {
            $result = 'ov|'; }
        else if ((strrpos($adj, 'o') == mb_strlen($adj) - 2) && (strrpos($adj, 'v') == mb_strlen($adj) - 1))
        {
            $result = $adj . '|';
        }
        else if ((strrpos($adj, 'i') == mb_strlen($adj) - 2) && (strrpos($adj, 'n') == mb_strlen($adj) - 1))
        {
            $result = $adj . '|';
        }
        else if ((strrpos($adj, 't') == mb_strlen($adj) - 3) && (strrpos($adj, 'o') == mb_strlen($adj) - 2) && (strrpos($adj, 'j') == mb_strlen($adj) - 1))
        {
            $result = self::substring($adj,0, mb_strlen($adj) - 2) . '|';
        }
        else if ((strrpos($adj, 'o') == mb_strlen($adj) - 2) && (strrpos($adj, 'j') == mb_strlen($adj) - 1))
        {
            $result = $adj . '|^';
        }
        else if ((strrpos($adj, 'e') == mb_strlen($adj) - 2) && (strrpos($adj, 'n') == mb_strlen($adj) - 1))
        {
            $result = self::substring($adj, 0, mb_strlen($adj) - 2) . 'n|';
        }
        else if ((strrpos($adj, 'y') == mb_strlen($adj) - 2) && (strrpos($adj, 'j') == mb_strlen($adj) - 1))
        {
            $result = self::substring($adj, 0, mb_strlen($adj) - 2);
        }
        else if ((strrpos($adj, 'i') == mb_strlen($adj) - 2) && (strrpos($adj, 'j') == mb_strlen($adj) - 1))
        {
            $result = self::substring($adj, 0, mb_strlen($adj) - 2) . '^';
        }
        else if (strrpos($adj, 'y') == mb_strlen($adj) - 1)
        {
            $result = self::substring($adj, 0, mb_strlen($adj) - 1);
        }
        else if (strrpos($adj, 'i') == mb_strlen($adj) - 1)
        {
            $result = self::substring($adj, 0, mb_strlen($adj) - 1) . '^';
        }
        else
        {
            $result = '';
        }
    
        $result = str_replace('k^', 'k', $result);
        $result = str_replace('g^', 'g', $result);
        $result = str_replace('h^', 'h', $result);

        return $result;
    }

    static function m_nominative_sg($adj, $root)
    {
        if (mb_strpos($root, '|') !== false)
        {
            $result = $adj;
        }
        else 	
        {
            $result = $root . 'y';
        }
        return $result;
    }

    static public function f_nominative_sg($root)
    {
        return $root . 'a';
    }

    static function n_nominative_sg($root)
    {
        return $root . 'o';
    }

    static function mn_genitive_sg($root)
    {
        return $root . 'ogo';
    }

    static function mn_dative_sg ($root)
    {
        return $root . 'omu';
    }

    static function m_accusative_sg($adj, $root)
    {
        return $root . 'ogo/' . $adj;
    }

    static function mn_instrumental_sg($root)
    {
        return $root . 'ym';
    }

    static function mn_locative_sg($root)
    {
        return $root . 'om';
    }

    static function f_accusative_sg($root)
    {
        return $root . 'ų';
    }

    static function f_gendatloc_sg($root)
    {
        return $root . 'oj';
    }

    static function f_instrumental_sg($root)
    {
        return $root . 'ojų';
    }

    static function m_nominative_pl($root)
    {
        return $root . 'i/' . $root . 'e';
    }

    static function m_accusative_pl($root)
    {
        return $root . 'yh/' . $root . 'e';
    }

    static function fn_nominative_pl($root)
    {
        return $root . 'e';
    }

    static function genloc_pl($root)
    {	
        $result = $root . 'yh';
        if ( $root == 'vs|^' ) { $result .= ' (' . 'vsěh)'; }
        if ( $root == 't|' ) { $result .= ' (' . 'těh)'; }
        return $result;
    }

    static function dative_pl($root)
    {
        $result = $root . 'ym';
        if ( $root == 'vs|^' ) { $result .= ' (' . 'vsěm)'; }
        if ( $root == 't|' ) { $result .= ' (' . 'těm)'; }
        return $result;
    }

    static function instrumental_pl($root)
    {
        $result = $root . 'ymi';
        if ( $root == 'vs|^' ) { $result .= ' (' . 'vsěmi)'; }
        if ( $root == 't|' ) { $result .= ' (' . 'těmi)'; }
        return $result;
    }

    static function adverb($root)
    {
        if (mb_substr($root, mb_strlen($root) - 2, 1) == 'ć')
        {
            $result = $root . '#i';
        }
        else
        {
            $result = $root . '#o';
        }
        return $result;
    }

    static function comparative_adj($root)
    {
        if (mb_strpos($root, '^') !== false)
        {
            $lastchar = mb_strlen($root) - 2;
        }
        else
        {
            $lastchar = mb_strlen($root) - 1;
        }
    
        if ($root == "velik")		{ $result = "vęčši"; }
        else if ($root == "mal")	{ $result = "menši"; }
        else if ($root == "dobr")	{ $result = "lěpši, lučši"; }
        else if ($root == "zl")		{ $result = "gorši"; }
        else if ($root == "mnog")	{ $result = "boľši"; }
        else if (mb_strrpos($root, "sk") == mb_strlen($root) - 2)
        {
            $result = "bolje " . $root . "i";
        }
        else if (mb_strrpos($root, "ok") == mb_strlen($root) - 2 || mb_strrpos($root, "ek") == mb_strlen($root) - 2)
        {
            $result = self::substring($root, 0, mb_strlen($root) - 2) . "ši";
        }
        else if (mb_strrpos($root, "k") == mb_strlen($root) - 1 && in_array(mb_substr($root, $lastchar - 1, 1), self::$vowel) == false)
        {
            $result = self::substring ($root, 0, mb_strlen($root) - 1) . "ši";
        }
        else if (in_array(mb_substr($root, $lastchar, 1), self::$vowel) == false && in_array(mb_substr($root, $lastchar - 1, 1), self::$vowel) == false && in_array(mb_substr($root, $lastchar - 1, 1), self::$liquid) == false)
        {
            if (mb_strpos($root, '^') === false)
            {
                $result = $root . "%ějši";
            }
            else
            {
                $result = $root . "%ejši";
            }
        }
        else if (in_array(mb_substr($root, $lastchar - 1, 1), self::$liquid) == true && in_array(mb_substr($root, $lastchar, 1), self::$nasal) == true)
        {
            $result = $root . "%ějši";
        }
        else
        {
            $result = $root . "ši";
        }
        $result = preg_replace('/k%/', "č", $result);
        $result = preg_replace('/g%/', "ž", $result);
        $result = preg_replace('/h%/', "š", $result);
        $result = preg_replace('/lši/', "ľši", $result);
        $result = preg_replace('/gši/', "žši", $result); $result = preg_replace('/ležši/', "legši", $result);
        $result = preg_replace('/%/', '', $result);
    
        return $result;
    }

    static function comparative_adv ($root)
    {
        if (mb_strpos($root,'^') !== false)
        {
            $lastchar = mb_strlen($root) - 2;
        }
        else
        {
            $lastchar = mb_strlen($root) - 1;
        }
    
        if ($root == "velik")		{ $result = "vęče"; }
        else if ($root == "mal")	{ $result = "menje"; }
        else if ($root == "dobr")	{ $result = "lěpje, lučše"; }
        else if ($root == "zl")		{ $result = "gorje"; }
        else if ($root == "mnog")	{ $result = "bolje"; }
        else if (mb_strrpos($root, "sk") == mb_strlen($root) - 2)
        {
            $result = "bolje " . $root . "o";
        }
        else if (mb_strrpos($root, "ok") == mb_strlen($root) - 2 || mb_strrpos($root, "ek") == mb_strlen($root) - 2)
        {
            $result = self::substring($root, 0, mb_strlen($root) - 2) . "%je";
        }
        else if (mb_strrpos($root, "k") == mb_strlen($root) - 1 && in_array(mb_substr($root, $lastchar - 1, 1), self::$vowel) == false)
        {
            $result = self::substring($root, 0, mb_strlen($root) - 1) . "%je";
        }
        else if (mb_strpos($root, '^') !== false)
        {
            $result = $root . "eje";
        }
        else
        {
            $result = $root . "%ěje";
        }
        $result = preg_replace("k%ě/", "če", $result);
        $result = preg_replace("g%ě/", "že", $result);
        $result = preg_replace("h%ě/", "še", $result);
        $result = preg_replace("k%j/", "kš", $result);
        $result = preg_replace("g%j/", "gš", $result);
        $result = preg_replace("st%j/", "šč", $result);
        $result = preg_replace("s%j/", "š", $result);
        $result = preg_replace("z%j/", "ž", $result);
        $result = preg_replace("t%j/", "č", $result);
        $result = preg_replace("d%j/", "dž", $result);
        $result = preg_replace("%/", "", $result);
    
        return $result;
    }

    static function superlative($root, $comp, $srt)
    {
        if ($root == "dobr" && $srt == "adj")
        {
            $result = "najlěpši, najlučši";
        }
        else if ($root == "dobr" && $srt == "adv")
        {
            $result = "najlěpje, najlučše";
        }
        else
        {
            $result = "naj" . $comp;
        }

        return $result;
    }

    static function rules($word)
    {
        $word = str_replace("^o", "^e", $word);
        $word = str_replace("^y", "^i", $word);
        $word = str_replace("s|^e","se", $word);
        $word = str_replace("s|^i","si", $word);
        $word = str_replace("|", "", $word);
        $word = str_replace("č^", "č", $word); $word = str_replace("š^", "š", $word); $word = str_replace("ž^", "ž", $word); $word = str_replace("ć^", "ć", $word); $word = str_replace("c^", "c", $word);
        $word = str_replace("/l^/", "lj", $word); $word = str_replace("n^", "ń", $word); $word = str_replace("r^", "ŕ", $word); $word = str_replace("j^", "j", $word);
        $word = str_replace("t^", "ť", $word); $word = str_replace("d^", "ď", $word); $word = str_replace("s^", "ś", $word); $word = str_replace("z^", "ź", $word); $word = str_replace("^", "", $word);
        $word = str_replace("jy", "ji", $word);
        $word = str_replace("ky", "ki", $word);
        $word = str_replace("gy", "gi", $word);
        $word = str_replace("hy", "hi", $word);
        $word = str_replace("cy", "ci", $word);

        $word = str_replace("#", "", $word);

        return $word;
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
