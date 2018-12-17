<?php 
class HomeModel extends BaseModel{
	public function GetCompanyMsgs(){
		$res = $this->db->query("SELECT * FROM Company");
		$results = array();
		while ( $row = $res->fetch_array()) {
			$results[]=$row;
		}
		$ret = array();
		for($x = 0; $x < count($results); $x++) {
		  $ret[$x][co_id] 	= $results[$x][Company_ID];
		  $ret[$x][co_name] = $results[$x][Company_Name];
		  $ret[$x][co_desc] = $results[$x][Company_Description];
		}

		return $ret;
	}
}
?>