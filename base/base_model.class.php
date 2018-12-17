<?php 
class BaseModel {
	protected $db = 0;
	public function __construct(){
		$this->db = new mysqli('localhost',MYSQL_USER,MYSQL_PASSWD,"zhongzhen");
		if($this->db->connect_error)
			die('could not connect:'.$this->db->connect_error);
		$this->db->set_charset('utf-8');
		$this->db->query("set names 'utf8'");
	}
	public function __destruct() {
		$this->db->close();
	}
	public function GetSessionId($name){
		$res = $this->db->query("SELECT * FROM User where User_Name = '{$name}'");
		$row = $res->fetch_array();

		return $row[User_SessionID];
	}
}
?>