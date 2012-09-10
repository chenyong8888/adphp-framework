<?php
class FilterUserInputTool
{
	protected $ArrFiltrate=array("select","delete","from","update","create","destory","drop","alter","and","or","like","exec","count","chr","mid","master","truncate","char","declare","\;","\-","\'");

	public function __construct(){
		if(function_exists(array_merge)){
		    $ArrPostAndGet=array_merge($HTTP_POST_VARS,$HTTP_GET_VARS);
		}else{
		    foreach($HTTP_POST_VARS as $key=>$value){
		       $ArrPostAndGet[]=$value;
		    }
		    foreach($HTTP_GET_VARS as $key=>$value){
		       $ArrPostAndGet[]=$value;
		    } 
		}		
		foreach($ArrPostAndGet as $key=>$Param){
		    if ($this->funStringExist($Param,$this->ArrFiltrate)){ 
		       return false;
		    }
		}
		return true;
	}
	
	private function funStringExist($StrFiltrate,$ArrFiltrate){
	    foreach ($ArrFiltrate as $key=>$value){
	       if (eregi($value,$StrFiltrate)){
	          return true;
	       } 
	    }
	    return false;
	}
}