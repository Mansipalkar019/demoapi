<?php
class Supermodel extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}

    public function get_user_data($rfid)
    {
        $this->db->select('tbl_user.*,tbl_user_wallet.*');
		$this->db->from('tbl_user');
		$this->db->join('tbl_user_wallet','tbl_user_wallet.fk_user_id=tbl_user.id','left');
		
		$this->db->where('tbl_user.rfid_card_num',$rfid);
		$this->db->order_by('tbl_user.id','DESC');
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_user_history($rfid)
    {
        $this->db->select('tu.first_name,tu.last_name,tu.rfid_card_num,tu.rfid_card_no,tu.card_type,tu.device_id,tuw.wallet_amount,tuw.validity_from_date,tuw.validity_to_date,twh.add_amount,twh.deduct_amount,twh.total_amount,twh.created_at,twh.used_status');
		$this->db->from('tbl_user as tu');
		$this->db->join('tbl_user_wallet as tuw','tuw.fk_user_id=tu.id','left');
		$this->db->join('tbl_wallet_history as twh','twh.fk_user_id=tu.id','left');
		$this->db->where('tu.rfid_card_num',$rfid);
		$this->db->order_by('twh.total_amount','ASC');
		$query = $this->db->get();
		$result = $query->result_array();
        return $result;
    }

}