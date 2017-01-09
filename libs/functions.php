<?php
/**
 * HTTP 파라미터 값 가지고 오기
 * @param String $id
 * @param String $default=""
 * @return string
 */
function getHttpVal($id,$default=""){
	$retVal = $default;
	if(isset($_REQUEST[$id])){
		$retVal = $_REQUEST[$id];
	}
	if(!is_array($retVal)){
		$retVal = addslashes($retVal);
	}
	return $retVal;
}
/**
 * 숫자인지 체크
 * @param int $n
 * @return boolean
 */
function isNumber($n){
	return is_numeric($n);
}

/**
 * 지정한 문자 길이만큼 컷
 * @param string $str 문자
 * @param int $length 제한
 * @return string
 */
function cutString($str,$length){
	return mb_substr($str,0,$length,"UTF-8");
}

/**
 * 해당코드가 존재하는지 체크
 * @param unknown $s
 * @param unknown $valid
 * @return mixed
 */
function checkValidCode($s, $valid){
	return array_search($s, $valid);
}
/**
 * 로그인 여부 체크
 */
function loginCheck(){
	if(session_status()==PHP_SESSION_NONE){
		session_start();
	}
	if(!isset($_SESSION["BID"])){
		header("Location: /login.php");
		exit();
	}
}

function gen_uuid() {
	return sprintf( '%04x%04x%04x%04x%04x%04x%04x%04x',
			// 32 bits for "time_low"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

			// 16 bits for "time_mid"
			mt_rand( 0, 0xffff ),

			// 16 bits for "time_hi_and_version",
			// four most significant bits holds version number 4
			mt_rand( 0, 0x0fff ) | 0x4000,

			// 16 bits, 8 bits for "clk_seq_hi_res",
			// 8 bits for "clk_seq_low",
			// two most significant bits holds zero and one for variant DCE1.1
			mt_rand( 0, 0x3fff ) | 0x8000,

			// 48 bits for "node"
			mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
	);
}

/**
 * 계좌번호 자리수 나누기
 * @param $account string 계좌번호
 * @return string 계좌번호
 */
function splitAccount($account){
	$accountLen = strlen($account);
	$retAccount = "";
	if($accountLen<10){ //전체
		$retAccount = $account;
	} else if($accountLen==10){ //4-2-4
		$retAccount = substr($account,0,4).'-'.substr($account,4,2).'-'.substr($account,-4);
	} else if($accountLen==11){ //4-3-4
		$retAccount = substr($account,0,4).'-'.substr($account,4,3).'-'.substr($account,-4);
	} else if($accountLen==12){ //4-4-4
		$retAccount = substr($account,0,4).'-'.substr($account,4,4).'-'.substr($account,-4);
	} else if($accountLen==13){ //5-2-6
		$retAccount = substr($account,0,5).'-'.substr($account,5,2).'-'.substr($account,-6);
	} else if($accountLen==14){ //6-2-6
		$retAccount = substr($account,0,6).'-'.substr($account,6,2).'-'.substr($account,-6);
	} else { // 6-3-나머지
		$retAccount = substr($account,0,6).'-'.substr($account,6,3).'-'.substr($account,9);
	}
	return $retAccount;
}


/**
 * @param $log string 로그를 파일로 남긴다.
 */
function logToFile($fileName,$log){
	$logDir="";
	if(PHP_OS=="Linux"){
		$logDir = LINUX_LOG_DIR;
	} else {
		$logDir = WIN_LOG_DIR;
	}
	//로그 디렉토리 존재여부 확인
	if(!is_dir($logDir)){
		@mkdir($logDir,0700);
	}
	$logFile = fopen($logDir."/".$fileName,"w");
	fwrite($logFile,$log);
	fclose($logFile);
}