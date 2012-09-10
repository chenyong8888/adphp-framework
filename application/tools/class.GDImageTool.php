<?php
class GDImageTool
{
	var $sourcePath; //图片存储路径 
	var $galleryPath; //图片缩略图存储路径 
	var $toFile = false; //是否生成文件 
	var $fontName; //使用的TTF字体名称 
	var $maxWidth = 500; //图片最大宽度 
	var $maxHeight = 600; //图片最大高度 

	//==========================================
	// 函数: __construct($sourcePath ,$galleryPath, $fontPath) 
	// 功能: constrUCtor 
	// 参数: $sourcePath 图片源路径(包括最后一个"/") 
	// 参数: $galleryPath 生成图片的路径 
	// 参数: $fontPath 字体路径 
	//==========================================
	function __construct($sourcePath, $galleryPath, $fontPath)
	{
		$this->sourcePath = $sourcePath;
		$this->galleryPath = $galleryPath;
		$this->fontName = $fontPath;
	}

	//==========================================
	// 函数: makeThumb($sourFile,$width=128,$height=128) 
	// 功能: 生成缩略图(输出到浏览器) 
	// 参数: $sourFile 图片源文件 
	// 参数: $width 生成缩略图的宽度 
	// 参数: $height 生成缩略图的高度 
	// 返回: 0 失败 成功时返回生成的图片路径 
	//==========================================
	function makeThumb($sourFile,$width=128,$height=128)
	{
		$myWidth = $width;
		$myHeight = $height;
		$imageInfo = $this->getInfo($sourFile);
		$sourFile = $this->sourcePath . $sourFile;
		$newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], "."));
		switch ($imageInfo["type"])
		{
			case 1: //gif
				$newName .= '"_s.gif"';
				$img = imagecreatefromgif($sourFile);
				break;
			case 2: //jpg
				$newName .= '"_s.jpg"';
				$img = imagecreatefromjpeg($sourFile);
				break;
			case 3: //png
				$newName .= '"_s.png"';
				$img = imagecreatefrompng($sourFile);
				break;
			default:
				return 0;
				break;
		}
		
		if (!$img) return 0;
		$width = ($width > $imageInfo["width"]) ? $imageInfo["width"] : $width;
		$height = ($height > $imageInfo["height"]) ? $imageInfo["height"] : $height;
		$srcW = $imageInfo["width"];
		$srcH = $imageInfo["height"];

		if ($srcW * $width > $srcH * $height){
			//$height = round($srcH * $width / $srcW);
			$width = round($srcW * $height / $srcH);
		}else{
			//$width = round($srcW * $height / $srcH);
			$height = round($srcH * $width / $srcW);
		}
		//*
		if (function_exists("imagecreatetruecolor")) //GD2.0.1
		{
			$new = imagecreatetruecolor($width, $height);
			ImageCopyResampled($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);
		}
		else
		{
			$new = imagecreate($width, $height);
			ImageCopyResized($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);
		}
		//*/
		if ($this->toFile)
		{
			if (file_exists($this->galleryPath . $newName))
			unlink($this->galleryPath . $newName);
			ImageJPEG($new, $this->galleryPath . $newName,100);
			@chmod($this->galleryPath.$newName,0777);
			//以下是用户切割图片的方法
			$x = 0;
			$y = 0;
			if($width>$myWidth){
				$x = round(($width-$myWidth)/2);
			}
			if($height>$myHeight){
				$y = round(($height-$myHeight)/2);
			}
				
			$this->cutImg($newName,$x,$y,$myWidth,$myHeight);
			//unlink($this->galleryPath . $newName);
			//=================================
			return $this->galleryPath . $newName;
		}else{
			ImageJPEG($new);
		}
		ImageDestroy($new);
		ImageDestroy($img);
	}
	//==========================================
	// 函数: waterMark($sourFile, $text) 
	// 功能: 给图片加水印 
	// 参数: $sourFile 图片文件名 
	// 参数: $text 文本数组(包含二个字符串) 
	// 返回: 1 成功 成功时返回生成的图片路径 
	//==========================================
	function waterMark($sourFile, $text)
	{
		$imageInfo = $this->getInfo($sourFile);
		$sourFile = $this->sourcePath . $sourFile;
		$newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], ".")) . "_mark.jpg";
		switch ($imageInfo["type"])
		{
			case 1: //gif
				$img = imagecreatefromgif($sourFile);
				break;
			case 2: //jpg
				$img = imagecreatefromjpeg($sourFile);
				break;
			case 3: //png
				$img = imagecreatefrompng($sourFile);
				break;
			default:
				return 0;
				break;
		}
		if (!$img)
		return 0;

		$width = ($this->maxWidth > $imageInfo["width"]) ? $imageInfo["width"] : $this->maxWidth;
		$height = ($this->maxHeight > $imageInfo["height"]) ? $imageInfo["height"] : $this->maxHeight;
		$srcW = $imageInfo["width"];
		$srcH = $imageInfo["height"];
		if ($srcW * $width > $srcH * $height)
		$height = round($srcH * $width / $srcW);
		else
		$width = round($srcW * $height / $srcH);
		//*
		if (function_exists("imagecreatetruecolor")) //GD2.0.1
		{
			$new = imagecreatetruecolor($width, $height);
			ImageCopyResampled($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);
		}
		else
		{
			$new = imagecreate($width, $height);
			ImageCopyResized($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);
		}
		$white = imageColorAllocate($new, 255, 255, 255);
		$black = imageColorAllocate($new, 0, 0, 0);
		$alpha = imageColorAllocateAlpha($new, 230, 230, 230, 40);
		//$rectW = max(strlen($text[0]),strlen($text[1]))*7;
		ImageFilledRectangle($new, 0, $height-26, $width, $height, $alpha);
		ImageFilledRectangle($new, 13, $height-20, 15, $height-7, $black);
		ImageTTFText($new, 4.9, 0, 20, $height-14, $black, $this->fontName, $text[0]);
		ImageTTFText($new, 4.9, 0, 20, $height-6, $black, $this->fontName, $text[1]);
		//*/
		if ($this->toFile)
		{
			if (file_exists($this->galleryPath . $newName))
			unlink($this->galleryPath . $newName);
			ImageJPEG($new, $this->galleryPath . $newName);
			return $this->galleryPath . $newName;
		}
		else
		{
			ImageJPEG($new);
		}
		ImageDestroy($new);
		ImageDestroy($img);

	}
	//==========================================
	// 函数: displayThumb($file) 
	// 功能: 显示指定图片的缩略图 
	// 参数: $file 文件名 
	// 返回: 0 图片不存在 
	//==========================================
	function displayThumb($file)
	{
		$thumbName = substr($file, 0, strrpos($file, ".")) . "_thumb.jpg";
		$file = $this->galleryPath . $thumbName;
		if (!file_exists($file))
		return 0;
		$Html = "";
		echo $html;
	}
	//==========================================
	// 函数: displayMark($file) 
	// 功能: 显示指定图片的水印图 
	// 参数: $file 文件名 
	// 返回: 0 图片不存在 
	//==========================================
	function displayMark($file)
	{
		$markName = substr($file, 0, strrpos($file, ".")) . "_mark.jpg";
		$file = $this->galleryPath . $markName;
		if (!file_exists($file))
		return 0;
		$html = "";
		echo $html;
	}
	//==========================================
	// 函数: getInfo($file) 
	// 功能: 返回图像信息 
	// 参数: $file 文件路径 
	// 返回: 图片信息数组 
	//==========================================
	function getInfo($file)
	{
		$file = $this->sourcePath . $file;
		$data = getimagesize($file);
		$imageInfo["width"] = $data[0];
		$imageInfo["height"]= $data[1];
		$imageInfo["type"] = $data[2];
		$imageInfo["name"] = basename($file);
		return $imageInfo;
	}

	//==========================================
	// 函数: cutImg($image,$start_x,$start_y,$thumbw = 128,$thumbh = 128)
	// 功能: 截取图片的某一部分 
	// 参数: $image 文件名称
	// 参数: $start_x 起始点x轴坐标
	// 参数: $start_y 起始点y轴坐标
	// 参数: $thumbw 图片宽度
	// 参数: $thumbh 图片高度
	// 返回: 图片信息数组 
	//==========================================
	function cutImg($image,$start_x,$start_y,$thumbw = 128,$thumbh = 128)
	{
		$imageInfo = $this->getInfo($image);
		$newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], ".")) . "_cut.jpg";
		$imagefile = $this->sourcePath.$image; // 原图		
		$imgstream = file_get_contents($imagefile);
		$im = imagecreatefromstring($imgstream);

		if(function_exists("imagecreatetruecolor")){
			$dim = imagecreatetruecolor($thumbw, $thumbh); // 创建目标图gd2
			ImageCopyResampled($dim,$im,0,0,$start_x,$start_y,$thumbw,$thumbh,$thumbw,$thumbh);
		}else{
			$dim = imagecreate($thumbw, $thumbh); // 创建目标图gd1
			imagecopyresized($dim,$im,0,0,$start_x,$start_y,$thumbw,$thumbh,$thumbw,$thumbh);
		}

		if ($this->toFile){
			if (file_exists($this->galleryPath.$newName))
			unlink($this->galleryPath . $newName);
			ImageJPEG($dim, $this->galleryPath.$newName);
			return $this->galleryPath . $newName;
		}else{
			imagejpeg ($dim);
		}
		ImageDestroy($dim);
	}

	/*
	 * 将多张gif图片合成一张动态gif
	 */
	function makeGif($gifArray,$gifTime,$returnGifName)
	{
		include_once 'gifreader.class.php';
		include_once 'gifencoder.class.php';
		$tempGifArray = array();
		for($i = 0; $i < count($gifArray); $i ++){
			for($j = 0 ; $j<$gifTime; $j++)
			{
				array_push($tempGifArray,$gifArray[$i]);
			}
		}
		$imgs = array();
		for($i = 0; $i < count($tempGifArray); $i ++){
			$gif = new GIFReader();
			$gif->load($tempGifArray[$i]);
			$im = $gif->fixgif(0);
			array_push($imgs, $gif->getsrc($im));
			imagedestroy($im);
		}
		$ger = new GIFEncoder($imgs, 0, 0, 2, 0, 0, 0, "bin");
		$gif->savegif($ger->GetAnimation(), "../uploadfile/$returnGifName");
	}

	//直接上传加缩略
	function cutphoto($o_photo,$d_photo,$width,$height){
		//	$temp_img=imagecreatefromjpeg($o_photo);
		switch (strtolower(substr($o_photo, strrpos($o_photo, "."))))
		{
			case '.gif': //gif
				$temp_img = imagecreatefromgif($o_photo);
				break;
			case '.jpg': //jpg
				$temp_img = imagecreatefromjpeg($o_photo);
				break;
			case '.png': //png
				$temp_img = imagecreatefrompng($o_photo);
				break;
			default:
				return 0;
				break;
		}
		$o_width = imagesx($temp_img);//取得原图宽 
		$o_height = imagesy($temp_img);//取得原图高 
		//判断处理方法 
		if($width>$o_width||$height>$o_height){//原图宽或高比规定的尺寸小,进行压缩 
			$newwidth=$o_width;
			$newheight=$o_height;
			if($o_width>$width){
				$newwidth=$width;
				$newheight=$o_height*$width/$o_width;
			}
			if($newheight>$height){
				$newwidth=$newwidth*$height/$newheight;
				$newheight=$height;
			}
			//缩略图片 
			$new_img=imagecreatetruecolor($newwidth,$newheight);
			imagecopyresampled($new_img,$temp_img,0,0,0,0,$newwidth,$newheight,$o_width,$o_height);

			switch (strtolower(substr($o_photo, strrpos($o_photo, "."))))
			{
				case '.gif': //gif
					imagegif($new_img,$d_photo);
					break;
				case '.jpg': //jpg
					imagejpeg($new_img,$d_photo);
					break;
				case '.png': //png
					imagepng($new_img,$d_photo);
					break;
				default:
					return 0;
					break;
			}

			imagedestroy($new_img);
		}else{//原图宽与高都比规定尺寸大,进行压缩后裁剪 
			if($o_height*$width/$o_width>$height){//先确定width与规定相同,如果height比规定大,则ok 
				$newwidth = $width;
				$newheight=$o_height*$width/$o_width;
				$x=0;
				$y=($newheight-$height)/2;
			}else{//否则确定height与规定相同,width自适应 
				$newwidth=$o_width*$height/$o_height;
				$newheight=$height;
				$x=($newwidth-$width)/2;
				$y=0;
			}
			//缩略图片 
			$new_img = imagecreatetruecolor($newwidth,$newheight);
			imagecopyresampled($new_img,$temp_img,0,0,0,0,$newwidth,$newheight,$o_width,$o_height);
			//imagejpeg($new_img,$d_photo);
			switch (strtolower(substr($o_photo, strrpos($o_photo, "."))))
			{
				case '.gif': //gif
					imagegif($new_img,$d_photo);
					break;
				case '.jpg': //jpg
					imagejpeg($new_img,$d_photo);
					break;
				case '.png': //png
					imagepng($new_img,$d_photo);
					break;
				default:
					return 0;
					break;
			}
			imagedestroy($new_img);
			$temp_img=imagecreatefromjpeg($d_photo);
			$o_width = imagesx($temp_img);//取得缩略图宽 
			$o_height=imagesy($temp_img);//取得缩略图高 
			//裁剪图片 
			$new_imgx=imagecreatetruecolor($width,$height);
			imagecopyresampled($new_imgx,$temp_img,0,0,$x,$y,$width,$height,$width,$height);
			//imagejpeg($new_imgx,$d_photo);
			switch (strtolower(substr($o_photo, strrpos($o_photo, "."))))
			{
				case '.gif': //gif
					imagegif($new_imgx,$d_photo);
					break;
				case '.jpg': //jpg
					imagejpeg($new_imgx,$d_photo);
					break;
				case '.png': //png
					imagepng($new_imgx,$d_photo);
					break;
				default:
					return 0;
					break;
			}
			imagedestroy($new_imgx);
		}
	}

	//图片叠加保存 完整路径
	function syntheticImage($fileName,$bgIMG,$img,$x=0,$y=0,$imgW=0,$imgH=0){
		// 大图层
		$bk = imagecreatefromjpeg($this->sourcePath.$bgIMG);
		$bk_w = imagesx($bk);
		$bk_h = imagesy($bk);
		// 图片1

		if($imgW==0 && $imgH==0){
			$new = $src;
			$new = imagecreatefromjpeg($img);
			$src_w = imagesx($new);
			$src_h = imagesy($new);
		}else{
			$src = imagecreatefromjpeg($img);
			$new = imagecreatetruecolor($imgW, $imgH);
			ImageCopyResampled($new, $src, 0, 0, 0, 0, $imgW, $imgH, imagesx($src), imagesy($src));
			if($imgW==0){
				$src_w = imagesx($src);
			}else{
				$src_w = $imgW;
			}
			if($imgH==0){
				$src_h = imagesy($src);
			}else{
				$src_h = $imgH;
			}
		}
		//底图
		$im = imagecreatetruecolor($bk_w, $bk_h);
		imagecopymerge($im, $bk, 0, 0, 0, 0, $bk_w, $bk_h, 100);
		imagecopymerge($im, $new, $x, $y, 0, 0, $src_w, $src_h, 100);
		if(imagejpeg($im, $this->sourcePath.$fileName, 100)){
			imagedestroy($im);
			imagedestroy($bk);
			imagedestroy($new);
			return true;
		}else{
			imagedestroy($im);
			imagedestroy($bk);
			imagedestroy($new);
			return false;
		}
	}



	//==========================================
	// 函数: writeText($sourFile, $text) 
	// 功能: 给图片加文字 
	// 参数: $sourFile 图片文件名 
	// 参数: $text 文本 
	// 返回: 1 成功 成功时返回生成的图片路径 
	//==========================================
	function writeText($sourFile, $text,$textsize=4.9,$x,$y,$color=0)
	{
		$imageInfo = $this->getInfo($sourFile);
		$sourFile = $this->sourcePath . $sourFile;
		$newName = substr($imageInfo["name"], 0, strrpos($imageInfo["name"], ".")) . ".jpg";
		switch ($imageInfo["type"])
		{
			case 1: //gif
				$img = imagecreatefromgif($sourFile);
				break;
			case 2: //jpg
				$img = imagecreatefromjpeg($sourFile);
				break;
			case 3: //png
				$img = imagecreatefrompng($sourFile);
				break;
			default:
				return 0;
				break;
		}
		if (!$img)
		return 0;

		$width = ($this->maxWidth > $imageInfo["width"]) ? $imageInfo["width"] : $this->maxWidth;
		$height = ($this->maxHeight > $imageInfo["height"]) ? $imageInfo["height"] : $this->maxHeight;
		$srcW = $imageInfo["width"];
		$srcH = $imageInfo["height"];
		if ($srcW * $width > $srcH * $height)
		$height = round($srcH * $width / $srcW);
		else
		$width = round($srcW * $height / $srcH);
		//*
		if (function_exists("imagecreatetruecolor")) //GD2.0.1
		{
			$new = imagecreatetruecolor($width, $height);
			ImageCopyResampled($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);
		}
		else
		{
			$new = imagecreate($width, $height);
			ImageCopyResized($new, $img, 0, 0, 0, 0, $width, $height, $imageInfo["width"], $imageInfo["height"]);
		}
		if($color==0){
			$color = imageColorAllocate($new, 0, 0, 0);
		}else{
			$color = imageColorAllocate($new, 255, 255, 255);
		}
		//		$alpha = imageColorAllocateAlpha($new, 230, 230, 230, 127);
		//$rectW = max(strlen($text[0]),strlen($text[1]))*7;
		//ImageFilledRectangle($new, 0, $height-26, $width, $height, $alpha);
		//ImageFilledRectangle($new, 13, $height-20, 15, $height-7, $color);
		ImageTTFText($new, $textsize, 0, $x, $y+$textsize, $color, $this->fontName, $text);
		//ImageTTFText($new, $textsize, 0, 20, $height-6, $black, $this->fontName, $text[1]);
		//*/
		if ($this->toFile)
		{
			if (file_exists($this->galleryPath . $newName))
			unlink($this->galleryPath . $newName);
			ImageJPEG($new, $this->galleryPath . $newName);
			return $this->galleryPath . $newName;
		}
		else
		{
			ImageJPEG($new);
		}
		ImageDestroy($new);
		ImageDestroy($img);

	}

}
?>
<?php
//require_once("GDImage.class.php");
//$dest_folder = dirname(__FILE__)."/../uploadfile/"; //上传文件路径
//$font_folder = dirname(__FILE__)."/../util/";//字体路径
//
////header("Content-type: image/jpeg");//输出到浏览器的话别忘了打开这个 
//$img = new GDImage($dest_folder, $dest_folder, $font_folder);
////$text = array("ice-berg.org","all rights reserved");
////$img->maxWidth = $img->maxHeight = 300;
////是否输出图片
//$img->toFile = true;
////$img->waterMark("mm.jpg", $text);
////生成缩略图
//$img->makeThumb("mm.jpg",100,100);
////截取部分图片
//$img->cutImg("mm.jpg",50,500,200,200);
////$img->displayThumb("mm.jpg");
////$img->displayMark("mm.jpg");
?>
<?php
//测试图片合成功能
//$gifArray = array();
//array_push($gifArray,"../uploadfile/78b743b6284e8c42bace324a0456d42e_shot_1.gif");
//array_push($gifArray,"../uploadfile/78b743b6284e8c42bace324a0456d42e_shot_2.gif");
//array_push($gifArray,"../uploadfile/78b743b6284e8c42bace324a0456d42e_shot_3.gif");
//array_push($gifArray,"../uploadfile/78b743b6284e8c42bace324a0456d42e_shot_4.gif");
//GDImage::makeGif($gifArray,2,"my.gif");
//echo "<img src=\"../uploadfile/1.gif\" />";
//echo "<img src=\"../uploadfile/2.gif\" />";
//echo "<img src=\"../uploadfile/3.gif\" />";
//echo "<img src=\"../uploadfile/4.gif\" />";
//echo "<br/>";
//echo "<img src=\"../uploadfile/my.gif\">";
?>
<?php
//测试图片叠加
//require_once("GDImage.class.php");
//$dest_folder = dirname(__FILE__)."/../uploadfile/"; //上传文件路径
//$font_folder = dirname(__FILE__)."/../util/";//字体路径
//$GDImage = new GDImage($dest_folder, $dest_folder, $font_folder);
//$fileName = 'f.jpg';
//$bgIMG = '../a.gif';
//$head = 'http://tp2.sinaimg.cn/1431175717/180/1290751469/1';
//$GDImage->syntheticImage($fileName,$bgIMG,$head)
?>