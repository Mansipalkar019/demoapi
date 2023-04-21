<?php
class Model extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function display_where_record($table_name,$condition)
    {
        $query= $this->db->select('*')
        ->from($table_name)
        ->where($condition)
        ->get();
        return $query->result_array();
    }
	function getData($tableName, $where_data=array(), $where_in = array(),$fields='*'){
        try{
			if (isset($tableName) && isset($where_data)) {
				
				$this->db->trans_start();
				$this->db->select($fields);
				if(!empty($where_data)){
					$this->db->where($where_data);
				}
				if(!empty($where_in)){
					$this->db->where_in($where_in['field'],$where_in['in_array']);
				}
				$query = $this->db->get($tableName);
                               
				$this->db->trans_complete();
				if ($query->num_rows() > 0){
					$rows = $query->result_array();
					return $rows;
				}else{
					return false;
				} 
			}else{
				return false;
			}
		} catch (Exception $e){
			return false;
		}
	}

	function getDataLimit($tableName, $where_data, $limit='', $start=''){
		//echo '<pre>'; print_r($where_data); 
		//echo $tableName.' - '.$limit .' - '. $start;
		try{
			if (isset($tableName) && isset($where_data)) {
				
				$this->db->trans_start();
				$query = $this->db->get_where($tableName, $where_data, $limit, $start);
				
				$this->db->trans_complete();
				if ($query->num_rows() > 0){
					$rows = $query->result_array();
					return $rows;
				}else{
					return false;
				} 
			}else{
				return false;
			}
		} catch (Exception $e){
			return false;
		}
	}

	function get_like_data($tbl,$clm,$keyword,$fields='*',$row = true)
	{
		$this->db->select($fields);
		$this->db->from($tbl);
		/*$this->db->where($wh_data);*/
		$this->db->like($clm, $keyword);
		$query = $this->db->get($tbl);
		if($row)
		$rows = $query->row_array();
		else				
		$rows = $query->result_array();
		return $rows;
	}

    function countrecord($tablename)
    {
    	$query = $this->db->get($tablename);
    	$count = $query->num_rows(); 
    	return $count;
    }

    function CountWhereInRecord($tableName,$where_data)
    {
    	$query = $this->db->get_where($tableName, $where_data);
    	$count = $query->num_rows(); 
    	return $count;
    }

    function CountWhereRecord($tableName, $where_data=array(), $where_in = array(),$fields='*')
	{
		$this->db->trans_start();
		$this->db->select($fields);
		if(!empty($where_data)){
		$this->db->where($where_data);
		}
		if(!empty($where_in)){
			$this->db->where_in($where_in['field'],$where_in['in_array']);
		}
		$query = $this->db->get($tableName);
    	$count = $query->num_rows();
    	$this->db->trans_complete();
    	return $count;
    }
   	
   	function count_by_query($sql){
   		$query = $this->db->query($sql);
      	$count = $query->num_rows(); 
    	return $count;
   	}

	function insertData($tableName, $array_data){
		try{
			if (isset($tableName) && isset($array_data)) {
				
				$this->db->trans_start();

				$this->db->insert($tableName, $array_data);
				$globals_id = $this->db->insert_id();

				$this->db->trans_complete();

				return $globals_id;

			}else{
				return false;
			}
		} catch (Exception $e){
			return false;
		}
	}

	function getAllData($tableName){
		if (isset($tableName)) {
			
			$this->db->trans_start();	
			$query = $this->db->get_where($tableName);
			//$query = $this->db->get($tableName);
			$this->db->trans_complete();
			
			if ($query->num_rows() > 0){
				$rows = $query->result_array();
				return $rows;
			}else{
				return false;
			} 
		}else{
			return false;
		}
	}

	
	function selectData($tableName,$fields='*',$row = true,$order_by=''){
		if (isset($tableName)) {
			
			$this->db->trans_start();	
			$this->db->select($fields);
			if (!empty($order_by)) {
				$this->db->order_by(@$order_by[0],@$order_by[1]);
			}
			$query = $this->db->get($tableName);
			$this->db->trans_complete();
			
			if ($query->num_rows() > 0){
				if($row)
				$rows = $query->row_array();
				else
				$rows = $query->result_array();
				return $rows;
			}else{
				return false;
			} 
			
		}else{
			return false;
		}
	}

	function selectWhereData($tableName,$whereData,$fields='*',$row = true,$order_by='',$group_by=''){
		if (isset($tableName)&&isset($whereData)) {
			$this->db->trans_start();	
			$this->db->select($fields);
			$this->db->where($whereData);
			if (!empty($order_by)) {
				$this->db->order_by(@$order_by[0],@$order_by[1]);
			}
			if (!empty($group_by)) {
				$this->db->group_by(@$group_by);
			}
			// $this->db->limit(2);
			$query = $this->db->get($tableName);
			$this->db->trans_complete();
			
			if ($query->num_rows() > 0){
				if($row)
				$rows = $query->row_array();
				else				
				$rows = $query->result_array();
				return $rows;
			}else{
				return false;
			} 
			
		}else{
			return false;
		}
	}
	function selectDataNotIn($tableName,$selectField,$notInClmName,$notInData)
	{		
		if (isset($tableName)) {
			
			$this->db->trans_start();	
			$this->db->select($selectField);
			$this->db->where_not_in($notInClmName, $notInData);
			$query = $this->db->get($tableName);
			$this->db->trans_complete();
			
			if ($query->num_rows() > 0){
				$rows = $query->result_array();
				return $rows;
			}else{
				return false;
			} 
			
		}else{
			return false;
		}
	}

	function getReportData($tableName, $whereData ){
		//echo $tableName;print_r($whereData);
		if (isset($tableName) && isset($whereData)) {
			$this->db->trans_start();
			$query = $this->db->get_where($tableName, $whereData);
			$this->db->trans_complete();
			
			if ($query->num_rows() > 0){
				$rows = $query->result_array();
				return $rows;
			}else{
				return false;
			} 
			
		}else{
			return false;
		}
	}
	
	function getRowData($tableName, $whereData ){
		//echo $tableName;print_r($whereData);
		if (isset($tableName) && isset($whereData)) {
			$this->db->trans_start();
			$query = $this->db->get_where($tableName, $whereData);
			$this->db->trans_complete();
			
			if ($query->num_rows() > 0){
				$row = $query->row_array();
				return $row;
			}else{
				return false;
			} 
			
		}else{
			return false;
		}
	}

	function getDataOrderBy($tableName, $whereData, $order_by, $ASC_DESC='ASC'){
		if (isset($tableName) && isset($whereData)) {
			
			$this->db->trans_start();	
			//$query = $this->db->get_where($tableName, $whereData)->order_by($order_by, $ASC_DESC);

			$this->db->from($tableName);
			$this->db->where($whereData);
			$this->db->order_by($order_by, $ASC_DESC);
			$query = $this->db->get(); 
			
			$this->db->trans_complete();
			
			if ($query->num_rows() > 0){
				$rows = $query->result_array();
				return $rows;
			}else{
				return false;
			} 
			
		}else{
			return false;
		}
	}

	function getReportDataWhereNotIn($tableName, $whereData, $whereColumn, $WhereInValues){
		$del_clm = array('is_deleted' => '-1' ); //-1 : Record not deleted
		$whereData = array_merge($del_clm, $whereData);
		
		$this->db->trans_start();	
		
		$this->db->from($tableName);
		$this->db->where($whereData);
		$this->db->where_not_in($whereColumn, $WhereInValues);
		
		$query = $this->db->get(); 
		
		$this->db->trans_complete();
		
		if ($query->num_rows() > 0){
			$rows = $query->result_array();
			return $rows;
		}else{
			return false;
		} 	
	}

	function getDataWhereIn($tableName, $whereData, $whereColumn, $WhereInValues){
		$this->db->trans_start();	
		
		$this->db->from($tableName);
		$this->db->where($whereData);
		$this->db->where_in($whereColumn, $WhereInValues);
		
		$query = $this->db->get(); 
		
		$this->db->trans_complete();
		
		if ($query->num_rows() > 0){
			$rows = $query->result_array();
			return $rows;
		}else{
			return false;
		} 	
	}

	function updateData($tableName, $updateData, $where){		
		$this->db->trans_start();	
		$query = $this->db->update($tableName, $updateData, $where);
		$this->db->trans_complete();
		$result = $query ? 1 : 0;
		return $result;
	}

	function deleteData($tableName,$updateData,$whereData){
		if(isset($tableName) && isset($updateData) &&isset($whereData)){
			$this->db->trans_start();	
			$query = $this->db->update($tableName, $updateData, $whereData);
			$this->db->trans_complete();
			if($this->db->affected_rows() > 0){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}		
	}

	function getSqlData($sql){
		
       	$query = $this->db->query($sql);
      	$result=$query->result_array();
      	return $result;
	}        
	
	function tableInsert($tablename,$val)
    {
    	
        $this->db->insert($tablename, $val);
        if($this->db->affected_rows() == 1){
        	$global_id=$this->db->insert_id();
         	return $global_id;
        } else {
         	return False;
        }
    }

    function truncate_table($sql){
    	$this->db->from($sql); 
		$this->db->truncate(); 
    }

    function generate_next_id($tablename,$field,$series='req'){
    	$query = $this->db->select($field)
    	->from($tablename)
    	->order_by($field,'DESC')
    	->like($field,$series)
    	->limit(1)
    	->get();
    	$data = $query->first_row();

    	if(empty($data)){
    		return $series.'000001';
    	}
    	else{
    		$last_id = $data->$field;
    		$number = substr($last_id,strlen($series));
    		$number = (int)$number + 1;
    		$next_id = $series.sprintf('%06s',$number);
    		return $next_id;
    	}
    }

    function generate_next_id2($last_id,$series= ''){
		$number = substr($last_id,strlen($series));
		$number = (int)$number + 1;
		$next_id = $series.sprintf('%06s',$number);
		return $next_id;
    }

    function isExist($tablename,$where,$fieldname='*'){
    	$query = $this->db->select($fieldname)
    	->from($tablename)
    	->where($where)
    	->get();
    	$num_rows = $query->num_rows();
    	if($num_rows > 0){
    		return true;
    	}
    	else{
    		return false;
    	}
    }

    function getValue($tablename,$fieldname,$where =array()){
    	$query = $this->db->select($fieldname)
    	->from($tablename)
    	->where($where)
    	->get();
    	$data = $query->first_row();
    	$data = (array)$data;
    	return isset($data[$fieldname])?$data[$fieldname]:'';
    }

    function getValue2($tablename,$fieldname,$where =array()){
    	$query = $this->db->select($fieldname)
    	->from($tablename)
    	->where($where)
    	->get();
    	$data = $query->result_array();
    	$multipleValue = [];
    	foreach ($data as $key => $value) {
    		$multipleValue[] = $value[$fieldname];
    	}
    	return $multipleValue;
    }

	public function changeStatus($tableName,$where)
	{
		$status=$this->getValue($tableName,'status',$where);
		if ($status==1) {
			$update_status=0;
		} else {
			$update_status=1;
		}
		$updateData=array('status'=>$update_status);
		$this->db->trans_start();	
		$query = $this->db->update($tableName, $updateData, $where);
		$this->db->trans_complete();

		$result = $query ? 1 : 0;
		return $result;
	}

	public function direct_delete($table_name,$where)
	{
		$this->db->delete($table_name,$where); 
        return TRUE;
	}
	public function check_exist($tablename="", $column="", $data="") {
        $response = array();
        $query = $this->db->get_where($tablename, $column);
        if ($query->num_rows() > 0) {
            $response['status'] = 1;
            $response['message'] = 'Already Exist';
            return $response;
        } else {
            $response['status'] = 0;
            $response['message'] = 'New Data';
            return $response;
        }
    }

    function deleteData2($tableName, $whereData) {
        if (isset($tableName) && isset($whereData)) {
            $this->db->trans_start();
            $this->db->delete($tableName, $whereData);
            $this->db->trans_complete();
            if ($this->db->affected_rows() > 0) { // returns 1 ( == true) if successfuly deleted
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}//class ends here	