<?php 
class MonitorController extends BaseController{
	public function MonitorAction(){
		session_start();
		$name 		= $_SESSION['username'];
		$sessid_new = $_SESSION['usersessionid'];

		BaseController::CheckLegality($name, $sessid_new);
		$co_id 				= $_GET['co_id'];
		$sta_id				= $_GET['sta_id'];
		$obj_temp			= $_GET['obj'];
		$_SESSION['co_id'] 	= $co_id;//存储本次会话的co_id，panel的ajax会用到!!

		$monitor_model 	= new MonitorModel();
		$res 			= $monitor_model->GetCompanyInfo($co_id);
		$sta_num 		= $res[sta_num];
		$co_nam 		= $res[co_nam];
		$user_type		= $_SESSION['usertype'];
		if (!($sta_num)) {
			if ($user_type == 'admin') {
				BaseController::GoToURL('?pos=home','there exists no station!');
			}else
				session_destroy();
				BaseController::GoToURL('?pos=login','there exists no station!');
		}else{
			if(empty($obj_temp))
				require POSITION_PATH.POSITION.".html";
			else
				require POSITION_PATH.$obj_temp.".html"; 
		}
	}
	public function GetAllDataAction(){
		session_start();
		$name 		= $_SESSION['username'];
		$user_type	= $_SESSION['usertype'];
		$co_id 		= $_SESSION['co_id'];
		$sta_id		= $_GET['sta_id'];
		$obj_temp	= $_GET['obj'];
		
		$monitor_model = new MonitorModel();
		$res_bl_pos 	= $monitor_model->GetRTBladePosi($co_id, $sta_id);
		$res_sys_state 	= $monitor_model->GetSysState($co_id, $sta_id);
		$res_cy_pos 	= $monitor_model->GetRTCyPosi($co_id, $sta_id);
		$res = array("BladePosi"=>$res_bl_pos, "SysState"=>$res_sys_state, "CyPosi"=>$res_cy_pos);
		echo json_encode($res);
	}
	/********************************************** control ***************************************/
	public function SetSysParamsAction(){
		session_start();
		$name = $_SESSION['username'];
		$sessid_new = $_SESSION['usersessionid'];
		
		BaseController::CheckLegality($name, $sessid_new);
		$co_id 		= $_SESSION['co_id'];
		$user_type	= $_SESSION['usertype'];
		$sta_id 	= $_GET['sta_id'];
		$obj_temp 	= $_GET['obj'];

		if ($user_type != 'admin' && $user_type != 'firm') {
			echo -1;
		}else {
			$blade_am_set = $_POST[blade_am_set];
			$blade_cnt_set = $_POST[blade_cnt_set];
			$cylinder_am_set = $_POST[cylinder_am_set];
			$cylinder_t_set = $_POST[cylinder_t_set];

			$monitor_model = new MonitorModel();
			$monitor_model->SetSysControlVal($co_id,$sta_id,$blade_am_set,$blade_cnt_set,$cylinder_am_set,$cylinder_t_set);
			echo 0;
			// echo "<script>history.go(-1);</script>";
		}	
	}
	public function SetCyOnetimeClkAction() {
		session_start();
		$name = $_SESSION['username'];
		$sessid_new = $_SESSION['usersessionid'];
		
		BaseController::CheckLegality($name, $sessid_new);
		$co_id 		= $_SESSION['co_id'];
		$user_type	= $_SESSION['usertype'];
		$sta_id 	= $_GET['sta_id'];
		$obj_temp 	= $_GET['obj'];

		if ($user_type != 'admin' && $user_type != 'firm') {
			echo -1;
		}else {
			$cy_id 	= $_POST[cy_id];
			$act = null;
			if ($_POST[act] == 'jog-up') {
				$act = 'JogUp';
			}else if ($_POST[act] == 'jog-down') {
				$act = 'JogDown';
			}else if ($_POST[act] == 'reset-to-0') {
				$act = 'Reset';
			}

			$monitor_model = new MonitorModel();
			$monitor_model->SetCyControlVal($co_id,$sta_id,$cy_id,$act,null,null);
			echo 0;
			// echo "<script>history.go(-1);</script>";
		}
	}
	public function SetSingleCyParamsAction() {
		session_start();
		$name = $_SESSION['username'];
		$sessid_new = $_SESSION['usersessionid'];
		
		BaseController::CheckLegality($name, $sessid_new);
		$co_id 		= $_SESSION['co_id'];
		$user_type	= $_SESSION['usertype'];
		$sta_id 	= $_GET['sta_id'];
		$obj_temp 	= $_GET['obj'];

		if ($user_type != 'admin' && $user_type != 'firm') {
			echo -1;
		}else {
			$cy_id 		  = $_POST[cy_id];
			$single_cy_am = $_POST[single_cy_am];
			$single_cy_t  = $_POST[single_cy_t];

			$monitor_model = new MonitorModel();
			$res = $monitor_model->SetCyControlVal($co_id,$sta_id,$cy_id,null,$single_cy_am,$single_cy_t);
			echo 0;
			// echo "<script>history.go(-1);</script>";
		}
	}
	/********************************************** IPC ***************************************/
	public function GetIPCInfoAction(){
		session_start();
		$name = $_SESSION['username'];
		$sessid_new = $_SESSION['usersessionid'];
		
		BaseController::CheckLegality($name, $sessid_new);
		$co_id 		= $_SESSION['co_id'];
		$user_type	= $_SESSION['usertype'];
		$sta_id 	= $_GET['sta_id'];
		$obj_temp 	= $_GET['obj'];
		$monitor_model = new MonitorModel();
		$ipc_dev_id = $monitor_model->GetIPCMsgs($co_id, $sta_id);
		// $ipc_dev_id = "";
		// if ($name == "charles") {
		// 	$ipc_dev_id = 'IPCS00000101';
		// }else if($name == "test"){
		// 	$ipc_dev_id = 'IPCS00000102';
		// }
		
		echo $ipc_dev_id;
	}
	public function HeartBeatAction(){
		session_start();
		$res = 0;
		$co_id 		= $_SESSION['co_id'];
		$co_nam 	= $_GET['co_name'];
		$sta_id 	= $_GET['sta_id'];
		$obj_temp 	= $_GET['obj'];
		$act_para 	= $_GET['para'];
		$ipc_dev_id = $_GET['dev'];
		$res = $this->SocketClient($act_para, $ipc_dev_id);

		echo $res;
	}
	private function SocketClient($act_para, $ipc_dev_id){
		error_reporting(E_ALL);
		set_time_limit(0);
		 
		$service_port = 9001;
		$address = "127.0.0.1"; 
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket < 0){
			// echo "socket_create() failed: reason: " . socket_strerror($socket) . "/n";
			return 1;
		}
		$result = socket_connect($socket, $address, $service_port);
		if ($result < 0){
			// echo "socket_connect() failed./nReason: ($result) " . socket_strerror($result) . "/n";
			return 2;
		}	 
		$msgs = $act_para;
		$msgs .= ':';
		$msgs .= $ipc_dev_id;
		
		if(!socket_write($socket, $msgs, strlen($msgs))){
			// echo "socket_write() failed: reason: " . socket_strerror($socket) . "/n";
			return 3;
		}
		socket_close($socket);
		
		return 0;
	}
}
 ?>

