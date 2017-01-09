<?php
/**
 * 메인 컨트롤러
 */
class Index extends Controller{

	/**
	 * 로그인 처리
	 */
	public function loginProc(){
		$id = cutString(getHttpVal("id"), 20) ;
		$pass = getHttpVal("pass");

$loginCheckSql = <<<EOD
	SELECT
		SEQ,
		MEM_ID,
		MEM_NAME,
		MEM_DEPT,
		MEM_LVL,
		MEM_STATUS
	FROM TBL_MEM_INFO
	WHERE MEM_ID = '%s'
	AND MEM_PASS = '%s'
EOD;

		$loginResult = $this->db->GetRow(sprintf($loginCheckSql, $id, $pass));
		
		$result = array();
		if($loginResult){
			if($loginResult["MEM_STATUS"] != "Y"){
				$result["result"]="FAIL";
				$result["resultMsg"]="미승인 또는 중지된 사용자입니다.";
			} else {
				$result["result"]="OK";
				$result["resultMsg"]="OK";
				$_SESSION["BID"] = $loginResult["MEM_ID"];
				$_SESSION["USER_SEQ"] = $loginResult["SEQ"];
				$_SESSION["USER_NAME"] = $loginResult["MEM_NAME"];
				$_SESSION["COMP_NAME"]= $loginResult["MEM_DEPT"];
				$_SESSION["LVL"] = $loginResult["MEM_LVL"];
			}
		} else {
			$result["result"]="FAIL";
			$result["resultMsg"]="사용자가 존재하지 않습니다.";
		}
		$this->resultAjax($result["result"], $result["resultMsg"], null);
	}
	
	/**
	 * 로그아웃
	 */
	public function logout(){
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-42000, '/');
		}
		session_destroy();
		
		header("Location: /login.php");
		exit();
	}
	
	public function loginStay(){
		
		
	}
}
?>