<?php
class Filters_Profil_Model extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	function get_FILTER(){
    $filter = $this->input->get_post('filter');
    if (is_array($filter)){
    	$nama_dupeg = false;
    	for ($i=0; $i < count($filter); $i++){    		
    		switch($filter[$i]['data']['type']){ 
    			case 'string' : $this->db->like($filter[$i]['field'], $filter[$i]['data']['value']); break;
    			case 'numeric' : 
    				switch($filter[$i]['data']['comparison']){
    					case "ne":
    						$this->db->where($filter[$i]['field'] . " !=", $filter[$i]['data']['value']); 
    						break;
    					case "lt":
    						$this->db->where($filter[$i]['field'] . " <=", $filter[$i]['data']['value']); 
    						break;
    					case "gt":
    						$this->db->where($filter[$i]['field'] . " >=", $filter[$i]['data']['value']); 
    						break;
    					case "eq":
    						$this->db->where($filter[$i]['field'], $filter[$i]['data']['value']); 
    						break;
    				}
    				break;
    			case 'date' : 
    				switch($filter[$i]['data']['comparison']){
    					case "ne":
    						$this->db->where($filter[$i]['field'] . " !=", date("Y-m-d", strtotime($filter[$i]['data']['value']))); 
    						break;
    					case "lt":
    						$this->db->where($filter[$i]['field'] . " <=", date("Y-m-d", strtotime($filter[$i]['data']['value']))); 
    						break;
    					case "gt":
    						$this->db->where($filter[$i]['field'] . " >=", date("Y-m-d", strtotime($filter[$i]['data']['value']))); 
    						break;
    					case "eq":
    						$this->db->where($filter[$i]['field'], date("Y-m-d", strtotime($filter[$i]['data']['value']))); 
    						break;
    				}
    				break;
    			case 'list' : 
    				if (strstr($filter[$i]['data']['value'],',')){
    					$value = array();
    					$a_val = explode(',',$filter[$i]['data']['value']); 
    					foreach($a_val as $key => $list){
    						if($list == 'Aktif'){
    							$value[] = 1;
    						}elseif($list == 'Non Aktif'){
    							$value[] = 0;
    						}else{
    							$value[] = $list;
    						}
    					}
							$this->db->where_in($filter[$i]['field'], $value);	
    				}else{ 
    					$list = $filter[$i]['data']['value'];
    					if($list == 'Aktif'){
    						$value = 1;
    					}elseif($list == 'Non Aktif'){
    						$value = 0;
    					}else{
    						$value = $list;
    					}    					
    					$this->db->where_in($filter[$i]['field'], $value);
    				}
    				if($filter[$i]['field'] == 'nama_dupeg'){
    					$nama_dupeg = true;
    				}
    				break;
    			case 'boolean' : $this->db->where($filter[$i]['field'], $filter[$i]['data']['value']); break;    			
    		}
    	}
    	if($nama_dupeg == false){
    		$this->db->where_in('kode_dupeg', array(1,17));
    	}
    }else{
    	if(!$this->input->post('kode_dupeg')){
    		$this->db->where_in('kode_dupeg', array(1,17));
    	}
    }
	}

}
?>