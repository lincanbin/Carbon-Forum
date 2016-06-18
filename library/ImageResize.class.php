<?php
/*
 * PHP-Image-Resize-Class
 * https://github.com/lincanbin/PHP-Image-Resize-Class/tree/master
 *
 * Copyright 2015, Lin Canbin
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A PHP class which can resize the image easily.
 */
class ImageResize
{
	
	private $PostField;
	private $ImageSize;
	private $ImageObject;
	
	public function __construct($Source, $PostField)
	{
		$this->PostField = $PostField;
		if($Source == 'PostField'){
			$this->GetImageObjectFromPostField();
		}else{
			$this->GetImageObjectFromString();
		}
		
	}
	private function GetImageObjectFromString()
	{
		$this->ImageSize = getimagesizefromstring($this->PostField);
		if($this->ImageSize){
			$this->ImageObject = imagecreatefromstring($this->PostField);
		}
	}

	private function GetImageObjectFromPostField()
	{
		if (stristr($_FILES[$this->PostField]['type'], "image")) {
			$this->ImageSize = getimagesize($_FILES[$this->PostField]['tmp_name']);
			if ($this->ImageSize) {
				//创建源图片
				/* 
				$ImageTypeArray = array(
					0=>'UNKNOWN',
					1=>'GIF',
					2=>'JPEG',
					3=>'PNG',
					4=>'SWF',
					5=>'PSD',
					6=>'BMP',
					7=>'TIFF_II',
					8=>'TIFF_MM',
					9=>'JPC',
					10=>'JP2',
					11=>'JPX',
					12=>'JB2',
					13=>'SWC',
					14=>'IFF',
					15=>'WBMP',
					16=>'XBM',
					17=>'ICO',
					18=>'COUNT'
				);
				$ImageType = $ImageTypeArray[$this->ImageSize[2]];
				*/
				switch ($this->ImageSize[2]) {
					case 1: //GIF
						$this->ImageObject = imagecreatefromgif($_FILES[$this->PostField]['tmp_name']);
						break;
					case 2: //JPEG
						$this->ImageObject = imagecreatefromjpeg($_FILES[$this->PostField]['tmp_name']);
						break;
					case 3: //PNG
						$this->ImageObject = imagecreatefrompng($_FILES[$this->PostField]['tmp_name']);
						break;
					default:
						break;
				}
			}
		}
	}
	
	//bool Resize ( int $TargetMaxPx, string $TargetPath [, int $Quality ] )
	public function Resize($TargetMaxPx, $TargetPath, $Quality = 95)
	{
		if (isset($this->ImageObject)) {
			$MinImagePx = min($this->ImageSize[0], $this->ImageSize[1]);
			//chmod($TargetPath, 0777);
			$Percent = 1.0;
			if($this->ImageSize[0] > $this->ImageSize[1]){
				$SourceX = round(($this->ImageSize[0] - $this->ImageSize[1])/2);
				$SourceY = 0;
				$Percent = 1.0 * $TargetMaxPx / $this->ImageSize[0];
			}else{
				$SourceX = 0;
				$SourceY = round(($this->ImageSize[1] - $this->ImageSize[0])/2);
				$Percent = 1.0 * $TargetMaxPx / $this->ImageSize[1];
			}
			//一个正方形图像，用来临时保存原图像中间部分
			$TempImageData = imagecreatetruecolor($MinImagePx, $MinImagePx);
			//将原图像中间部分拷贝到正方形中
			imagecopy($TempImageData, $this->ImageObject, 0, 0, $SourceX, $SourceY, $MinImagePx, $MinImagePx);
			//目标图像
			$TargetImageData = imagecreatetruecolor($TargetMaxPx, $TargetMaxPx);
			$Background      = imagecolorallocate($TargetImageData, 255, 255, 255);
			imagefill($TargetImageData, 0, 0, $Background);
			//缩放
			imagecopyresampled($TargetImageData, $TempImageData, 0, 0, 0, 0, $TargetMaxPx, $TargetMaxPx, $MinImagePx, $MinImagePx);
			return imagejpeg($TargetImageData, $TargetPath, $Quality);
		} else {
			return false;
		}
	}
}