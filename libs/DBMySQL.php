<?php
class MySQL {
	private $db = null;
	/**
	 * 초기
	 * @param unknown $host 호스트
	 * @param unknown $db 디비명
	 * @param unknown $user 사용자
	 * @param unknown $pass 비밀번호
	 */
	function MySQL($host, $db, $user, $pass){
		$this->db = @mysql_connect($host, $user, $pass	) or die(mysql_error());
		
		mysql_select_db($db,$this->db);
	}
	
	function startTranjection() {
		$this->setAutocommit("0");
	}
	
	function endTranjection() {
		$this->setAutocommit("1");
	}
	
	function setAutocommit($v){
		$this->Execute("set autocommit={$v}");
	}
	
	function commit() {
		$this->Execute("commit");
	}
	
	function rollback(){
		$this->Execute("rollback");
	}
	
	function Disconnect(){
		mysql_close($this->db);
	}

	function Execute($sql){
		$res = mysql_query($sql,$this->db);
		return $res;
	}

	function GetAll($sql){
		$res = $this->Execute($sql);
		
		$data = array();
		
		while ($row = mysql_fetch_assoc($res))
		{
			$data[] = $row;
		}

		return $data;
	}

	function GetOne($sql){
		$res = $this->Execute($sql);

		$data = mysql_fetch_array($res);

		return $data[0];
	}

	function GetRow($sql){
		$res = $this->Execute($sql);

		$data = mysql_fetch_assoc($res);

		return $data;
	}

	function AffectedRows()	{
		return mysql_affected_rows($this->db);
	}
	
	function GetLastInsertId(){
		return $this->GetOne("SELECT LAST_INSERT_ID()");
	}
	
	function GetError(){
		return mysql_error($this->db);
	}
}
?>