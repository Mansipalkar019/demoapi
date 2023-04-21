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
		$response = array('code' => - 1, 'status' => false, 'message' => '');
		//$validate = validateToken();

		//if ($validate) {

			$rfid_card_no = $this->input->post('rfid_card_no');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
			$contact_no = $this->input->post('contact_no');
			$wallet_amount=$this->input->post('wallet_amount');

			$curl_data=array(
				'rfid_card_no'=>$rfid_card_no,
				'first_name'=>$first_name,
				'last_name'=>$last_name,
				'contact_no'=>$contact_no,
				'date'=>date('Y-m-d'),
				'created_at'=>date('Y-m-d H:i:s'),
			);
			
			if (empty($rfid_card_no)) {
                $response['message'] = 'RFID NO. is required.';
                $response['code'] = 201;
            }
			else if (empty($first_name))
			{
				$response['message'] = 'First Name is required.';
                $response['code'] = 201;
			}
			else if (empty($last_name))
			{
				$response['message'] = 'Last Name is required.';
                $response['code'] = 201;
			}
			else if (empty($contact_no))
			{
				$response['message'] = 'Contact No. is required.';
                $response['code'] = 201;
			}
			else
			{
				$check_user_exist = $this->model->selectWhereData('tbl_user',array('rfid_card_no'=>$rfid_card_no,'contact_no'=>$contact_no),array('*'),false,array('id' => 'desc'));
				if(empty($check_user_exist))
				{
					$inserted_id = $this->model->insertData('tbl_user',$curl_data);
					
					$current_date = new DateTime();
					$six_months_from_now = $current_date->modify('+6 months')->format('Y-m-d');

					$user_wallet=array(
						'fk_user_id'=>$inserted_id,
						'wallet_amount'=>$wallet_amount,
						'validity_from_date'=>$current_date,
						'validity_to_date'=>$six_months_from_now,
						'created_at'=>date('Y-m-d H:i:s'),
					);
					$inserted_id = $this->model->insertData('tbl_user_wallet',$user_wallet);
					$response['code'] = REST_Controller::HTTP_OK;
					$response['status'] = true;
					$response['message'] = 'Registration Successfull'; 
				}else{
					$response['message'] = 'User already Exist';
					$response['code'] = 201;
				}
			}

		
		// }else{
		// 	$response['code'] = REST_Controller::HTTP_UNAUTHORIZED;
        //     $response['message'] = 'Unauthorised';
		// }
		
		echo json_encode($response);
	}
}
