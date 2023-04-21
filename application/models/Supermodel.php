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
		
		$this->db->where('tbl_user.rfid_card_no',$rfid);
		$this->db->order_by('tbl_user.id','DESC');
		$query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

}