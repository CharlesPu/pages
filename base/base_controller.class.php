<?php 
	class BaseController {
		public function CheckSessionIdAction($name,$sessid_now){
			$base_model = new BaseModel();
			$sessid_old = $base_model->GetSessionId($name);
			if($sessid_old == $sessid_now)return 0;
			else return 1;
		}
		public function GoToURL($url,$alert_text){
			if ( empty($alert_text) ) {
				echo "<script>location.href='$url';</script>";
			}else {
				echo "<script>alert('$alert_text');</script>";
				echo "<script>location.href='$url';</script>";
			}
		}

	}
 ?>