<?php

namespace App\Support;

class Helper
{
    /**
     * Transforma a primeira letra da frase em maiúscula
     * @param string|null $string
     * @return string
     */
    public static function primeiraMaiuscula(?string $string)
    {
        if (empty($string)) {
            return $string;
        }

        $ignorar = array('do', 'dos', 'da', 'das', 'de', 'e');
        $array = explode(' ', mb_strtolower($string, 'UTF-8'));
        $out = '';
        foreach ($array as $ar) {
            $out .= (in_array($ar, $ignorar) ? $ar : mb_convert_case($ar, MB_CASE_TITLE, "UTF-8")) . ' ';
        }
        return trim($out);
    }

    /**
     * Transforma toda a palavra para letras maiúsculas
     * @param string|null $string
     * @return string
     */
    public static function todasMaiusculas($str){
		return strtr(strtoupper($str),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
	}

     /**
     * Verifica se variavel vazia e retorna null
     * @param string|null $string
     * @return string
     */
    public static function verificaVariavelVazia(?string $string)
    {
        if (empty($string)) {
            return NULL;
        }
        return $string;
    }



}