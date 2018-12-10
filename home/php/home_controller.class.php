<?php 
class HomeController extends BaseController{
	private $co_num = null;
	private $co_msg = array();

	public function HomeAction(){
		session_start();
		$name 		= $_SESSION['username'];
		$sessid_new	= $_SESSION['usersessionid'];
		
		BaseController::CheckLegality($name, $sessid_new);
		$home_model = new HomeModel();	
		
		$this->co_msg = $home_model->GetCompanyMsgs();
		$this->co_num = count($this->co_msg);
		
		require POSITION_PATH.POSITION.".html";	
	}
	private function PrintMapArray($arr) {
		for($x = 0; $x<count($arr);$x++) {
			foreach($arr[$x] as $k => $k_value) {
				echo "Key=" . $k . ", Value=" . $k_value;
				echo "<br>";
			}
		}
	}
}
?>