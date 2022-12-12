<?php

// função que aplica uma função passada por parâmetro em todos os elementos do array
function array_map_recursive(callable $func, array $array){
    $res = [];
    foreach ($array as $k => $v)
        $res[$k] = is_array($v) ? array_map_recursive($func, $v) : $func(trim($v));
    return $res;
}

// função que recupera a primeira data encontrada em um array
function getDataInArray(array $array){
    foreach($array as $val){
        if(is_array($val)) return getDataInArray($array);
        if(preg_match('/\d{2,4}\-\d{1,2}\-\d{1,2}(?=$)/', $val)) return $val;
    }
    return false;
}
