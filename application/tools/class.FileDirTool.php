<?php
class FileDirTool{

	function createdir($dir){
		if(file_exists($dir) && is_dir($dir)){
			chmod($dir,0777);
		}else{
			mkdir($dir,0777);
			chmod($dir,0777);
		}
	}

	function rmdir_tree($dirname){
		$handle=opendir($dirname);
		while ($file=readdir($handle)){
			if (($file==".") || ($file=="..")) continue;
			$fullname=$dirname."/".$file;
			if (filetype($fullname)=="dir")
			rmdir_tree($fullname);
			else
			unlink($fullname);
		}
		closedir($handle);
		rmdir($dirname);
	}	
	
	function creatHtml($thisURL,$creatHtmlPath){
		try {
			$webTemp = file_get_contents($thisURL,r);
			eregi("(.*)",$webTemp,$webContent);
			$handle=fopen($creatHtmlPath,"w");
			fwrite($handle,$webContent[1]);			
			fclose($handle);
			return true;
		}catch(Exception $e){
			return false;
		}		
	}

	function uploadOneFile($filetypes,$max_file_size,$destination_folder,$updoadfile){
		include_once ROOT_PATH.'/application/language/'.DEFAULT_LANGUAGE.'/';
		$return = array();
		$message = '';
		$fname='';
		$True = true;;
		if (!is_uploaded_file($_FILES[$updoadfile][tmp_name])){}
		else
		{
			$file = $_FILES[$updoadfile];
			if($max_file_size < $file["size"]){
				$message = upload_lang::upload_invalid_filesize." $max_file_size KB";
				$True = false;
			}
			if(!in_array($file["type"], $filetypes)){
				$message = upload_lang::upload_stopped_by_extension.' '.$file["type"];
				$True = false;
			}
			if(!file_exists($destination_folder)){
				mkdir($destination_folder);
			}
			if($True){
				$filename=$file["tmp_name"];
				$image_size = getimagesize($filename);
				$pinfo=pathinfo($file["name"]);
				$ftype=$pinfo['extension'];
				$destination = $destination_folder.time().rand().".".$ftype;
				if (file_exists($destination) && $overwrite != true){
					$message = upload_lang::upload_bad_filename;
					$True = false;
				}
				if(!move_uploaded_file ($filename, $destination)){
					$message = upload_lang::upload_destination_error;
					$True = false;
				}else{
					@chmod($destination,0777);
				}
				$pinfo=pathinfo($destination);				
				$data = array(
					'fileName' => $pinfo[basename]
				);				
				$return['state'] = '1';
				$return['message'] = upload_lang::upload_success;
				$return['data'] = $data;				
			}else{
				$return['state'] = '0';
				$return['message'] = $message;
				$return['data'] = null;	
			}
		}
		return $return;
	}

	
}
























