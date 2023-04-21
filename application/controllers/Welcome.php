<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set("memory_limit", "-1");
require APPPATH . '/libraries/REST_Controller.php';
class Welcome extends REST_Controller {


	public function index() {
        $response = array('status' => false, 'msg' => 'Oops! Please try again later.', 'code' => 200);
        echo json_encode($response);
    }

	public function get_home_page_data_post()
	{
		$response['code'] = REST_Controller::HTTP_OK;
		$response['status'] = true;
		$response['message'] = 'success';

		echo json_encode($response);
	}
}
