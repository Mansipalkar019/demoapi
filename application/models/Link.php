<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Link extends CI_Model {

function hits($link,$request,$token='',$type = 1)
    {
        $Base_API = 'http://localhost/amusement_park/demoapi/';
        $query = http_build_query($request);
        if ($type == 0) {
            $custom_type = 'GET';
            $url = $Base_API . $link . "?" . $query;
        } else {
            $custom_type = 'POST';
            $url = $Base_API . $link;
        }
      
        // $data = json_encode($data);
        $header = array("Authorization:".token_get());
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom_type);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$request);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response1 = curl_exec($ch);
        curl_close($ch);
        return $response1;

    }

    function whatsapp_hits($data='')
    {
        $url = "https://button-api.com/api/send-text.php";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
} 

