<?php
class RandomCodeGenerate {

    function generate($length = 8, $in_params = [])
    {
        $in_params['upper_case']        = isset($in_params['upper_case']) ? $in_params['upper_case'] : true;
        $in_params['lower_case']        = isset($in_params['lower_case']) ? $in_params['lower_case'] : true;
        $in_params['number']            = isset($in_params['number']) ? $in_params['number'] : true;
        $in_params['special_character'] = isset($in_params['special_character']) ? $in_params['special_character'] : false;

        $chars = '';
        if ($in_params['lower_case']) {
            $chars .= "abcdefghijklmnopqrstuvwxyz";
        }

        if ($in_params['upper_case']) {
            $chars .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        }

        if ($in_params['number']) {
            $chars .= "0123456789";
        }

        if ($in_params['special_character']) {
            $chars .= "!@#$%^&*()_-=+;:,.";
        }

        return substr(str_shuffle($chars), 0, $length);
    }
    
}