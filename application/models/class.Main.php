<?php
class Main extends Model{	
	
	private $database_table = "";
	private $database_mianID = "";
	
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 
	 * @param $database_table
	 * @return void
	 */
	public function set_database_table($database_table){
		$this->database_table = DB_PREFIX.$database_table;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function get_database_table(){
		return $this->database_table;
	}
	
	/**
	 * 
	 * @param $database_mianID
	 * @return void
	 */
	public function set_database_mianID($database_mianID){
		$this->database_mianID = $database_mianID;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function get_database_mianID(){
		return $this->database_mianID;
	}
	
	/**
	 * 
	 * @param $data
	 * @return number
	 */
	public function add($data)
	{
		if(!is_array($data)) die('Database Add Function Parameter ERR!');		
		$ksyStr = implode(',',array_keys($data));
		$valueStr = implode('\',\'',$data);		
		$sqlstr = "INSERT INTO $this->database_table ($ksyStr) VALUES ('$valueStr')";		
		return $this->execute($sqlstr);
	}
	
	/**
	 * 
	 * @param $id
	 * @return number
	 */
	public function del($id)
	{		
		$sqlstr = "DELETE FROM $this->database_table WHERE $this->database_mianID = '$id'";			
		return $this->execute($sqlstr);
	}
	
	/**
	 * 
	 * @param $data
	 * @return number
	 */
	public function update($data)
	{
		if(!is_array($data)) die('Database Update Function Parameter ERR!');		
		$sqltemp = '';
		$i = 1;
		foreach($data as $key=>$value){
			if($i<count($data)){
				$sqltemp .= $key."="."'$value',";
			}else{
				$sqltemp .= $key."="."'$value'";
			}
			$i++;
		}		
		$sqlstr = "UPDATE $this->database_table SET $sqltemp WHERE $this->database_mianID = '". $data[$this->database_mianID] ."'";
		return $this->execute($sqlstr);
	}
	
	/**
	 * 
	 * @param $id
	 * @return stdClass Object
	 */
	public function getRowById($id)
	{
		$sqlstr = "SELECT * FROM ".$this->database_table." WHERE ".$this->database_mianID."='".$id."'";
		$query = $this->query($sqlstr);
		return $this->fetch($query,'object');
	}
	
	/**
	 * 
	 * @param $offset
	 * @param $num
	 * @return array
	 */
	public function get($offset,$num)
  	{
  		$infoArray = array();
  		$sqlstr = "SELECT * FROM ".$this->database_table." LIMIT $offset,$num";  		
  		$query = $this->query($sqlstr);
  		$count = 0;
		while($row=mysql_fetch_object($query)){
			$infoArray[$count] = $row;
			$count++;
		}		
  		return $infoArray;
  	}

  	/**
  	 * 
  	 * @return number
  	 */
  	public function getRowTotal()
  	{
		$sqlstr = "SELECT count(*) as total FROM ".$this->database_table;
		$query = $this->query($sqlstr);
		$object = $this->fetch($query,'object');
		return $object->total;
  	}
  	
  	/**
  	 * 
  	 * @param $sqlstr
  	 * @return query
  	 */
  	public function getQuery($sqlstr)
  	{
  		$query = $this->query($sqlstr);
  		return $query;
  	}
  	
  	/**
  	 * 
  	 * @param $sqlstr
  	 * @return stdClass Object
  	 */
  	public function getRow($sqlstr)
  	{
  		$query = $this->query($sqlstr);
		return $this->fetch($query,'object');
  	}
  	
  	/**
  	 * 
  	 * @return array
  	 */
  	public function getAllData()
  	{
  		$infoArray = array();
  		$sqlstr = "SELECT * FROM ".$this->database_table;  		
  		$query = $this->query($sqlstr);
  		$count = 0;
		while($row=mysql_fetch_object($query)){
			$infoArray[$count] = $row;
			$count++;
		}		
  		return $infoArray;
  	}
	
}











