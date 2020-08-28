<?php

class UserapiModel extends CI_Model
{
	public function save($data=null,$table=null) {
		
		$result = $this->db->insert($table,$data);
		if($result){
			return [
				'id'		=> $this->db->insert_id(),
				'status' 	=> true,
				'message'	=> 'successfully saved'
			];
		}else{
			return [
				'status' 	=> false,
				'message'	=> 'something went wrong'
			];
		}
	}
    
	   function get_state(){
		   $this ->db-> select('*');
		   $this -> db -> from('state');
		   $query = $this -> db -> get();
		   $res= $query -> num_rows();
		   if($res  > 0){
			return $query->result_array();  
		   } else {
			 return 0;
		   }
	   }
	   function get_child(){
		   $this ->db-> select('*');
		   $this -> db -> from('child_details');
		   $query = $this -> db -> get();
		   $res= $query -> num_rows();
		   if($res  > 0){
			return $query->result_array();  
		   } else {
			 return 0;
		   }
	   }
	   function get_district(){
		   $this ->db-> select('*');
		   $this -> db -> from('district');
		   $query = $this -> db -> get();
		   $res= $query -> num_rows();
		   if($res  > 0){
			return $query->result_array();  
		   } else {
			 return 0;
		   }
	   }
	
	   function checkLogin( $data){
			$username= $data['username'];
			$password= $data['password'];
			$this->db->select('*');
			$this->db -> from('admin');
		   $this->db-> where('username', $username);
		   $this->db-> where('password', $password);
		   $query = $this -> db -> get(); 
		   $res= $query -> num_rows();
		   if($res  > 0){
			return $query->result_array();  
		   } else {
			 return 0; 
		   }
		}

	
	
}