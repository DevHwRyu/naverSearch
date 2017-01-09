<?php
set_time_limit(0);
class Keyword extends Controller {
	
	/**
	 * 키워드 리스트 로드
	 */
	
	public function getKeywordList() {
		
		$year = getHttpVal("year");
		$month = getHttpVal("month");
		$status = getHttpVal("status");
		
		
		$subQuery = "";
		
		if ($year != "") {
			$subQuery .= " AND TKM.SEARCH_YEAR = '{$year}' ";	
		}
		
		if ($month != "") {
			$subQuery .= " AND TKM.SEARCH_MONTH = '{$month}' ";
		}
		
		if ($status != "") {
			$subQuery .= " AND TKM.SEARCH_STATE = '{$status}' ";
		}
		
$selectSql = <<<EOD
	
		SELECT 
			TKM.SEQ as mSeq,
			TKM.SEARCH_YEAR as searchYear,
			TKM.SEARCH_MONTH as searchMonth,
			TKM.SEARCH_NAME as searchName,
			TKM.SEARCH_CNT as searchCnt,
			TKM.SEARCH_STATE as searchState,
			TMI.MEM_DEPT as userNm,
			TKM.SEARCH_TIME as firstDate,
			TKM.REG_DT as lastDate
		FROM TBL_KEYWORD_MASTER TKM LEFT JOIN TBL_MEM_INFO TMI
		ON TKM.SEARCH_USER = TMI.SEQ
		WHERE 1=1
		%s
		ORDER BY TKM.REG_DT DESC

EOD;
	
		$list = $this->db->GetAll(sprintf($selectSql, $subQuery));
		$this->resultAjax("OK", "", $list);
		
	}
	
	/**
	 * 키워드 등록
	 */
	public function addKeywordProc() {
	
		$keyword = getHttpVal("addKeyword");
		
$insertSql = <<<EOD
	INSERT INTO TBL_KEYWORD_MASTER ( SEARCH_NAME, SEARCH_CNT, SEARCH_STATE, SEARCH_USER, REG_DT ) VALUES (
		'%s', 0, 'N', %d, NOW()) 
EOD;

		$this->db->Execute(sprintf($insertSql, $keyword, $_SESSION["USER_SEQ"]));
		
		$this->resultAjax("OK", null, null);
		
	}
	
	
	/**
	 * 키워드 분석 가져오기
	 */
	public function getKeywordProc() {
		
		$mSeq = getHttpVal("mSeq");
		$keyword = getHttpVal("keyword");

		$kwdList = $this->api->GET('/keywordstool', array('hintKeywords' => $keyword, 'showDetail' => '1', "format" => 'json'));
		$list = $kwdList["keywordList"];
		

$updateSql = <<<EOD
	UPDATE TBL_KEYWORD_MASTER SET SEARCH_CNT = %d, SEARCH_STATE = 'Y', SEARCH_TIME = NOW(), SEARCH_USER = %d, UPT_DT = NOW() WHERE SEQ = %d
EOD;
		
$insertSql = <<<EOD

	INSERT INTO TBL_KEYWORD_SEARCH (
		MAS_SEQ,
		ORD,
		KEYWORD_NAME,
		SEARCH_PC_CNT,
		SEARCH_M_CNT,
		CLICK_PC_CNT,
		CLICK_M_CNT,
		CLICK_PC_PER,
		CLICK_M_PER,
		COMPETITION_RATE,
		MONTH_AVG_AD_CNT,
		REG_DT
	) VALUES (
		%d,
		%d,
		'%s',
		'%s',
		'%s',
		%d,
		%d,
		%d,
		%d,
		'%s',
		%d,
		NOW()
	)
EOD;
		
/*
 *  ["monthlyAvePcCtr"]=> 월평균클릭률 > PC
    float(0.17)
    ["monthlyMobileQcCnt"]=> 월간검색수 > 모바일
    int(19300)
    ["monthlyAveMobileClkCnt"]=> 월평균 클릭수 > 모바일
    float(51)
    ["plAvgDepth"]=> 월평균노출광고수
    int(15)
    ["relKeyword"]=> 연관키워드
    string(12) "빅데이터"
    ["monthlyPcQcCnt"]=> 월간검색수 > PC
    int(24600)
    ["monthlyAveMobileCtr"]=> 월평균클릭률 > 모바일
    float(0.28)
    ["monthlyAvePcClkCnt"]=> 월평균 클릭수 > PC
    float(40.9)
    ["compIdx"]=> 경쟁정도
    string(6) "높음"
 */
		
		for($j=0;$j<count($list);$j++) {
	
			$this->db->Execute(sprintf(
				$insertSql,
				$mSeq,
				($j+1),
				$list[$j]["relKeyword"],
				$list[$j]["monthlyPcQcCnt"],
				$list[$j]["monthlyMobileQcCnt"],
				$list[$j]["monthlyAvePcClkCnt"],
				$list[$j]["monthlyAveMobileClkCnt"],
				$list[$j]["monthlyAvePcCtr"],
				$list[$j]["monthlyAveMobileCtr"],
				$list[$j]["compIdx"],
				$list[$j]["plAvgDepth"]
			));
			
		}
		
		//마스터 테이블 UPDATE
		$this->db->Execute(sprintf($updateSql, count($list), $_SESSION["USER_SEQ"], $mSeq));
		
		$this->resultAjax("OK", "", null);
	}
	
	/**
	 * 키워드관리리스트 가져오기
	 */
	public function getKeywordEdit() {
		
		
$selectSql = <<<EOD
	
		SELECT
			SEQ as seq,	
			KEYWORD_NAME as kName,
			KEYWORD_ORD as kOrd,
			UPT_DT as uptDt,
			REG_DT as regDt
		FROM TBL_KEYWORD_LIST
		WHERE KEYWORD_STATE = 'Y'
		AND 0=%d
		ORDER BY KEYWORD_ORD ASC
		
EOD;

		$list = $this->db->GetAll(sprintf($selectSql, 0));
	
		$this->resultAjax("OK", "", $list);
		
	}
	
	/**
	 * 키워드 등록
	 */
	public function regKeywordProc() {
		
		$keyword = getHttpVal("regKeyword");
		
$selectCntSql = <<<EOD
	SELECT COUNT(*) FROM TBL_KEYWORD_LIST WHERE KEYWORD_NAME = '%s'
EOD;
		
$insertSql = <<<EOD
	INSERT INTO TBL_KEYWORD_LIST ( KEYWORD_NAME, KEYWORD_ORD, REG_DT ) VALUES (
	'%s', %d, NOW())
EOD;

$selectSql = <<<EOD
	SELECT MAX(KEYWORD_ORD) + 1 FROM TBL_KEYWORD_LIST
EOD;
		
		$exist = $this->db->GetOne(sprintf($selectCntSql, $keyword));
		
		if ($exist > 0) {
			$this->resultAjax("FAIL", "이미 등록된 키워드명입니다.", null);
		}
		$maxCnt = $this->db->GetOne($selectSql);
		if ($maxCnt == 0) {
			$maxCnt = 1;
		}
		$this->db->Execute(sprintf($insertSql, $keyword, $maxCnt));
		$this->resultAjax("OK", null, null);
		
	}
	
	/**
	 * 키워드 삭제
	 */
	
	public function delKeywordProc() {
		
		$delSeq = getHttpVal("delSeq");
		$ord = getHttpVal("ord");
		
$updateSql = <<<EOD
	UPDATE TBL_KEYWORD_LIST SET KEYWORD_ORD = (KEYWORD_ORD - 1) WHERE KEYWORD_ORD > %d
EOD;
	
$deleteSql = <<<EOD
	DELETE FROM TBL_KEYWORD_LIST WHERE SEQ = %d
EOD;

		//순서변경
		$this->db->Execute(sprintf($updateSql, $ord));

		//키워드삭제
		$this->db->Execute(sprintf($deleteSql, $delSeq));
		
		$this->resultAjax("OK", null, null);

	}
	
	/**
	 * 순서변경
	 */
	
	public function moveKeywordProc() {
		$seq = getHttpVal("seq");
		$ord = getHttpVal("ord");
		$typ = getHttpVal("typ");

//이전 순서
$prevSelectSql = <<<EOD
	SELECT SEQ FROM TBL_KEYWORD_LIST WHERE KEYWORD_ORD < %d ORDER BY KEYWORD_ORD DESC LIMIT 0, 1
EOD;

//이후 순서
$nextSelectSql = <<<EOD
	SELECT SEQ FROM TBL_KEYWORD_LIST WHERE KEYWORD_ORD > %d ORDER BY KEYWORD_ORD ASC LIMIT 0, 1
EOD;

//등급 위로
$plusUpdateSql = <<<EOD
	UPDATE TBL_KEYWORD_LIST SET KEYWORD_ORD = (KEYWORD_ORD - 1) WHERE SEQ = %d
EOD;

//등급 밑으로
$minusUpdateSql = <<<EOD
	UPDATE TBL_KEYWORD_LIST SET KEYWORD_ORD = (KEYWORD_ORD + 1) WHERE SEQ = %d
EOD;
		
		//등급 위로처리
		if ($typ == "U") {
		
			//이전순서 등급 밑으로 내림
			$prevSeq = $this->db->GetOne(sprintf($prevSelectSql, $ord));
			$this->db->Execute(sprintf($minusUpdateSql, $prevSeq));
			
			//해당 키워드를 등급 UP
			$this->db->Execute(sprintf($plusUpdateSql, $seq));
			
		//등급 밑으로 처리
		} else {
			
			//이후순서 등급 위로 올림
			$nextSeq = $this->db->GetOne(sprintf($nextSelectSql, $ord));
			$this->db->Execute(sprintf($plusUpdateSql, $nextSeq));
				
			//해당 키워드를 등급 DOWN
			$this->db->Execute(sprintf($minusUpdateSql, $seq));
			
		}

		
		$this->resultAjax("OK", null, null);
		
	}
	
	/**
	 * 키워드 불러오기
	 */
	public function keywordCopyProc() {
		
		$year = getHttpVal("year");
		$month = getHttpVal("month");
		
$insertSql = <<<EOD
	
	INSERT INTO TBL_KEYWORD_MASTER (SEARCH_YEAR, SEARCH_MONTH, SEARCH_NAME, SEARCH_CNT, SEARCH_STATE, REG_DT)
    SELECT
    '%s' AS SEARCH_YEAR,
    '%s' AS SEARCH_MONTH,
    TKL.KEYWORD_NAME AS SEARCH_NAME,
    0 AS SEARCH_CNT,
    'N' AS SEARCH_STATE,
    NOW()
    FROM TBL_KEYWORD_LIST TKL LEFT JOIN 
    (
      SELECT
      *
      FROM TBL_KEYWORD_MASTER
      WHERE SEARCH_YEAR = '%s'
      AND SEARCH_MONTH = '%s'
    ) AS TKM
    ON TKL.KEYWORD_NAME = TKM.SEARCH_NAME
    WHERE TKM.SEQ IS NULL
    ORDER BY KEYWORD_ORD ASC

EOD;
		
		$this->db->Execute(sprintf($insertSql, $year, $month, $year, $month));

		$this->resultAjax("OK", null, null);
		
	}
	
	/**
	 * 키워드상세 검색값 불러오기
	 */
	public function getKeywordViewData() {
		
		$seq = getHttpVal("seq");
		
$selectSql = <<<EOD
	SELECT
		SEQ as seq,
		ORD as no,
		KEYWORD_NAME as keyword,
		SEARCH_PC_CNT as monthSearchPc,
		SEARCH_M_CNT as monthSearchMobile,
		CLICK_PC_CNT as monthClickPc,
		CLICK_M_CNT as monthClickMobile,
		CLICK_PC_PER as monthAvgClickPc,
		CLICK_M_PER as monthAvgClickMobile,
		COMPETITION_RATE as compitition,
		MONTH_AVG_AD_CNT as monthAdCnt
	FROM TBL_KEYWORD_SEARCH
		WHERE STATUS = 'Y'
		AND MAS_SEQ = %d
	ORDER BY ORD ASC
	
EOD;
	
		$list = $this->db->GetAll(sprintf($selectSql, $seq)); 
		$this->resultAjaxN("OK", null, $list);
		
	}
	
	
}