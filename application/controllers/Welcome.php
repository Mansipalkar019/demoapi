<?php
defined('BASEPATH') OR exit('No direct script access allowed');
ini_set("memory_limit", "-1");
require APPPATH . '/libraries/REST_Controller.php';
class Welcome extends REST_Controller {


	public function index() {
        $response = array('status' => false, 'msg' => 'Oops! Please try again later.', 'code' => 200);
        echo json_encode($response);
    }

	public function register_user_post()
	{
		$response = array('code' => - 1, 'status' => false, 'message' => '');
		$validate = validateToken();
		if ($validate) {

			$rfid_card_no = $this->input->post('rfid_card_no');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
			$contact_no = $this->input->post('contact_no');
			$wallet_amount=$this->input->post('wallet_amount');
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
				$check_rfid_card_no_exist = $this->model->CountWhereRecord('tbl_user',array('rfid_card_no'=>$rfid_card_no));
				$check_contact_no_exist = $this->model->CountWhereRecord('tbl_user',array('contact_no'=>$contact_no));
				if($check_rfid_card_no_exist > 0){
                        $response['code'] = 201;
                        $response['status'] = false;
                        $response['message'] = 'RFID Already exist.';                
                        $response['error_status'] = 'rfid';                 
                }else if($check_contact_no_exist > 0){
                        $response['code'] = 201;
                        $response['status'] = false;
                        $response['message'] = 'Contact No Already exist.';                
                        $response['error_status'] = 'contact';                   
                }else{
					$curl_data=array(
						'rfid_card_no'=>$rfid_card_no,
						'first_name'=>$first_name,
						'last_name'=>$last_name,
						'contact_no'=>$contact_no,
						'date'=>date('Y-m-d'),
					);
					$inserted_id = $this->model->insertData('tbl_user',$curl_data);
					
					$current_date = date('Y-m-d');
					
					$six_months_from_now = date('Y-m-d', strtotime("+6 months", strtotime($current_date)));

					$user_wallet=array(
						'fk_user_id'=>$inserted_id,
						'wallet_amount'=>$wallet_amount,
						'validity_from_date'=>$current_date,
						'validity_to_date'=>$six_months_from_now,
					);
					$this->model->insertData('tbl_user_wallet',$user_wallet);
					$user_wallet_history = array(
						'fk_user_id'=>$inserted_id,
						'add_amount'=>$wallet_amount,
						'total_amount'=>$wallet_amount,
						'used_status'=>1,
					);
					$this->model->insertData('tbl_wallet_history',$user_wallet_history);
					$response['code'] = REST_Controller::HTTP_OK;
					$response['status'] = true;
					$response['message'] = 'Registration Successfull'; 
				}
			}

		
		}else{
			$response['code'] = REST_Controller::HTTP_UNAUTHORIZED;
            $response['message'] = 'Unauthorised';
		}
		
		echo json_encode($response);
	}

	public function fetch_rfid_post()
	{
		$response = array('code' => - 1, 'status' => false, 'message' => '');
		$validate = validateToken();

		if ($validate) {

			$rfid_card_no = $this->input->post('rfid_card_no');
            $amt = 20;
			$check_rfid_exist = $this->Supermodel->get_user_data($rfid_card_no);
			$get_wallet_history = $this->model->selectWhereData('tbl_wallet_history', array('used_status'=>1),array('*'));
			if(!empty($get_wallet_history['total_amount'])){
				$deduct_amt=$get_wallet_history['total_amount']-$amt;
				$final_amt=$get_wallet_history['add_amount']-$deduct_amt;
				$this->model->updateData(' tbl_wallet_history',array('used_status'=>0),array('id'=>$get_wallet_history['id']));
				$curl_data=array(
					'fk_user_id'=>$get_wallet_history['fk_user_id'],
					'deduct_amount'=>$amt,
					'total_amount'=>$deduct_amt,
					'used_status'=>1,
				);
				$inserted_id = $this->model->insertData('tbl_wallet_history',$curl_data);
				$response['code'] = REST_Controller::HTTP_OK;
				$response['status'] = true;
				$response['message'] = 'Success '; 
				$response['deduct_amount'] = $amt; 
				$response['total_amount'] = $deduct_amt; 
			}else{
				$response['code'] = 201;
				$response['status'] = false;
				$response['message'] = '';                  
			}
		
		}else{
			$response['code'] = REST_Controller::HTTP_UNAUTHORIZED;
            $response['message'] = 'Unauthorised';
		}
		
		echo json_encode($response);
	}
}
