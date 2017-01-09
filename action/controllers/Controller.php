<?php
class Controller
{
	public $db;
	public $isAdmin="Y";
	
	public $api;
	
	function __construct()
	{
		
		$config = parse_ini_file("sample.ini");
		
		$this->db = new MySQL(DB_HOST, DB_NAME, DB_USER, DB_PASS);
		$this->api = new RestApi($config['BASE_URL'], $config['API_KEY'], $config['SECRET_KEY'], $config['CUSTOMER_ID']);
	
	}
	
	function __destruct(){
		$this->db->Disconnect();

	}

	/**
	 * JSON 형식으로 반환
	 * @param $code string 결과코드
	 * @param $msg string 결과 메세지
	 * @param array $data 데이터
	 * @param $isLog boolean 로깅여부
	 */
	function resultAjax($code, $msg="", $data=array()){
		$result = array();
		$result["result"]=$code;
		$result["resultMsg"]=$msg;
		$result["data"] = $data;
		echo json_encode($result);
		exit();
	}
	
	function resultAjaxN($code, $msg="", $data=array()){
		$result = array();
		$result["result"]=$code;
		$result["resultMsg"]=$msg;
		$result["data"] = $data;
		echo json_encode($result, JSON_NUMERIC_CHECK);
		exit();
	}
	

	/**
	 * 엑셀 다운로드
	 * @param $fileName string 파일명
	 * @param $contents string 내용
	 */
	function resultExcel($fileName, $contents){
		header( "Content-type: application/vnd.ms-excel" );
		header( "Content-type: application/vnd.ms-excel; charset=utf-8");
		header( "Content-Disposition: attachment; filename = ".$fileName );
		header( "Content-Description: PHP5 Generated Data" );
		echo '<meta content="application/vnd.ms-excel; charset=UTF-8" name="Content-type">';
		echo $contents;
		exit();
	}
	
	/**
	 * 페이징 계산부분
	 * @param unknown $totalCount //전체 갯수
	 * @param unknown $currentPage //현재 페이지
	 * @param unknown $pagePerArticleCnt //페이지당 개시물 갯수
	 */
	public function getPagination($totalArticleCount, $currentPage, $pagePerArticleCnt=10, $blockPerPageCnt=10){
		$totalPageCnt = ceil($totalArticleCount/$pagePerArticleCnt); //총 페이지수
		$firstPageNo = floor(($currentPage-1)/$blockPerPageCnt)*$blockPerPageCnt+1;
		$lastPageNo = $firstPageNo+$blockPerPageCnt-1;
		if($lastPageNo>$totalPageCnt){
			$lastPageNo=$totalPageCnt;
		}
		if($lastPageNo==0){
			$lastPageNo = 1;
		}
		
		$firstRecordIdx = ($currentPage-1)*$pagePerArticleCnt;
		//$lastRecordIdx = $currentPage*$pagePerArticleCnt;
		$lastRecordIdx =$pagePerArticleCnt;
		
		$p = array();
		$p["totalPageCnt"] = $totalPageCnt;
		$p["firstPageNo"] = $firstPageNo;
		$p["lastPageNo"] = $lastPageNo;
		$p["firstRecordIdx"] = $firstRecordIdx;
		$p["lastRecordIdx"] = $lastRecordIdx;
		$p["totalArticleCount"] = $totalArticleCount;
		$p["currentPage"] = $currentPage;
		
		return $p;
	}

}