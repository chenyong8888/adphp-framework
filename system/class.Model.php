<?php
/**
 * 
 * @author chen yong
 *
 */
class Model
{
	protected $S_db = NULL;
	protected $M_db = NULL;
	
	public function __construct(){
		require_once(ROOT_PATH.'/application/config/class.Database.php');		
	}
	
	public function openConnM_S($sql){
		if(stristr($sql,'SELECT ')==False){
			$this->M_db = mysql_connect(M_DB_HOST, M_DB_USER, M_DB_PASS) or exit(mysql_error());
			mysql_select_db(M_DB_NAME, $this->M_db) or exit(mysql_error());	
		}else{			
			$this->S_db = mysql_connect(S_DB_HOST, S_DB_USER, S_DB_PASS) or exit(mysql_error());
			mysql_select_db(S_DB_NAME, $this->S_db) or exit(mysql_error());	
		}
		mysql_query("SET NAMES '".DB_CHARSET."'");
	}
	
	public function openConnM($sql){
		$this->M_db = mysql_connect(M_DB_HOST, M_DB_USER, M_DB_PASS) or exit(mysql_error());
		mysql_select_db(M_DB_NAME, $this->M_db) or exit(mysql_error());
		mysql_query("SET NAMES '".DB_CHARSET."'");
	}
	
	public function query($sql){
		if(M_S){
			if(!@mysql_ping($this->S_db)){
				$this->openConnM_S($sql);
			}
		}else{
			if(!@mysql_ping($this->M_db)){
				$this->openConnM($sql);
			}
		}
		$res = mysql_query($sql) or exit(mysql_error());
		return $res;
	}
	
	public function execute($sql){
		if(!@mysql_ping($this->M_db)){
			$this->openConnM($sql);
		}
		if(mysql_query($sql))
		{
			$rc = mysql_affected_rows();
			return $rc;
		}
		return 0;		
	}
	
	public function fetch($res, $type = 'array'){
		$func = $type == 'array' ? 'mysql_fetch_array' : 'mysql_fetch_object';
		$row  = $func($res);
		return $row;
	}
	
	public function closeConn(){
		mysql_close($this->S_db);
		$this->S_db = null;
		mysql_close($this->M_db);
		$this->M_db = null;
	}
}
