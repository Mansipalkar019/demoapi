<?php

function validateToken(){
    $CI = get_instance();
    if (array_key_exists('HTTP_AUTHORIZATION', $_SERVER) && !empty($_SERVER['HTTP_AUTHORIZATION'])) {
        $decodedToken = AUTHORIZATION::validateToken($_SERVER['HTTP_AUTHORIZATION']);
        // print_r($decodedToken);
        if ($decodedToken != false) {
           return true;
        } else {
            return false;
        }
    }
    return false;
}
// function validateToken(){
//     $CI = get_instance();
//     if (array_key_exists('REDIRECT_HTTP_AUTHORIZATION', $_SERVER) && !empty($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
//         $decodedToken = AUTHORIZATION::validateToken($_SERVER['REDIRECT_HTTP_AUTHORIZATION']);
//         if ($decodedToken != false) {
//             return true;
//         } else {
//             return false;
//         }
//     } else if (array_key_exists('HTTP_AUTHORIZATION', $_SERVER) && !empty($_SERVER['HTTP_AUTHORIZATION'])) {
//         $decodedToken = AUTHORIZATION::validateToken($_SERVER['HTTP_AUTHORIZATION']);
//         if ($decodedToken != false) {
//             return true;
//         } else {
//             return false;
//         }
//     }
//     return false;
// }