<?php

class Application
{
	private $controller = null;
	private $action = null;

	public function __construct()
	{
		session_start();
		
		$cancontroll = false;
		$this->controller = getHttpVal("ctrl");

		$excludeAction = array("login","loginProc");
		
		if (file_exists('./controllers/' . $this->controller . 'Ctrl.php')) {
			require './controllers/' . $this->controller . 'Ctrl.php';
			$this->controller = new $this->controller();
			
			$this->action = getHttpVal("action");
			
			if (method_exists($this->controller, $this->action)) {
				$cancontroll = true;
				
				if(!in_array($this->action,$excludeAction)){
					if(!isset($_SESSION["BID"])){
						$result=array();
						$result["result"]="REQ_LOGIN";
						$result["resultMsg"]="로그인이 필요합니다.";
						echo json_encode($result);
						exit();
					} else {
						if($_SESSION["LVL"]=="S"){
							$this->controller->isAdmin="Y";
						}
					}
				}
				$this->controller->{$this->action}();
			}
		}
		
		if($cancontroll === false) {
			if($_SERVER["CONTENT_TYPE"]=="application/x-www-form-urlencoded; charset=UTF-8"){
				$result=array();
				$result["result"]="FAIL";
				$result["resultMsg"]="잘못된 접근입니다.";
				echo json_encode($result);
			} else {
				echo "<!DOCTYPE html><html><head><meta charset='utf-8'></head><body><h1>Oops!!! 잘못된 접근입니다.</h1></body></html>";
			}
		} 
	}
}