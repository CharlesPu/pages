<?php 
	class LoginController extends BaseController{
		public function LoginAction(){
			include POSITION_PATH.POSITION.".html";
		}
		public function CheckLoginAction() {
			$name = $_POST['username'];
			$pswd = md5($_POST['password']);
					
			$login_model = new LoginModel();
			$res = $login_model->CheckAdmin($name,$pswd);
			if ($res) { //登录失败
				BaseController::GoToURL('?pos=login','密码错误！');				
			}else{//登录成功
				session_start();
				$sid = session_id();//产生新的session_id写入每个用户，只能有一个，只要有登入，数据库中就刷新！！
				$login_model->ChangeSessionId($name,$pswd,$sid);
				$login_model->SetLoginTimeIP($name);

				$_SESSION['username']		= $name;
				$_SESSION['usersessionid']	= $sid;//但是同一个用户登录成功每次都分配一个session_id,可以不同，所以只要检测到不同，就要求重登！！
				$type = $login_model->GetUserType($name);
				$_SESSION['usertype']		= $type;
				if (!strcmp($type, 'administrator')) {
					BaseController::GoToURL('?pos=home');
				}else
				if (!strcmp($type, 'firm')) {
					$company_id = $login_model->GetCompanyID($name);
					BaseController::GoToURL("?pos=monitor&co_id=$company_id&sta_id=1");
				}else
				if (!strcmp($type, 'certification')) {

				}
			}
		}
		public function LogoutAction(){
			session_start();
			$_SESSION['username']		= null;
			$_SESSION['usersessionid']	= null;
			$_SESSION['usertype']		= null;
			$_SESSION['co_id']			= null;
			session_destroy();
			BaseController::GoToURL('?pos=login');		
		}
	}
 ?>