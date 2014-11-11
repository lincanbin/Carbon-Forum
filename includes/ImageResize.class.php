<?php
/*
 * PHP-Image-Resize-Class
 * https://github.com/lincanbin/PHP-Image-Resize-Class/tree/master
 *
 * Copyright 2014, Lin Canbin
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

	public function __construct($PostField)
	{
		$this->PostField = $PostField;
		$this->GetImageObject();
	}

	private function GetImageObject()
	{
		if(stristr($_FILES[$this->PostField]['type'], "image")){
			$this->ImageSize = getimagesize($_FILES[$this->PostField]['tmp_name']);
			if($this->ImageSize){
				//创建源图片
				/* 
				$ImageTypeArray = array
				 (
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
					case 1://GIF
						$this->ImageObject = imagecreatefromgif($_FILES[$this->PostField]['tmp_name']);
						break;
					case 2://JPEG
						$this->ImageObject = imagecreatefromjpeg($_FILES[$this->PostField]['tmp_name']);
						break;
					case 3://PNG
						$this->ImageObject = imagecreatefrompng($_FILES[$this->PostField]['tmp_name']);
						break;
					default:
						break;
				}
			}
		}
	}

	//bool Resize ( int $TargetMaxPx, string $TargetPath [, int $Quality ] )
	public function Resize($TargetMaxPx, $TargetPath, $Quality=95)
	{
		
		if(isset($this->ImageObject)){
			$MaxImagePx = max($this->ImageSize[0], $this->ImageSize[1]);
			//chmod($TargetPath, 0777);
			if($MaxImagePx>$TargetMaxPx){
				$Percent = $TargetMaxPx/$MaxImagePx;
				$TargetWidth = round($this->ImageSize[0]*$Percent);
				$TargetHeight = round($this->ImageSize[1]*$Percent);
							
				$TargetImageData = imagecreatetruecolor($TargetWidth, $TargetHeight);
				$Background = imagecolorallocate($TargetImageData, 255, 255, 255);
				imagefill($TargetImageData, 0, 0, $Background);
				imagecopyresampled($TargetImageData, $this->ImageObject, 0, 0, 0, 0, $TargetWidth, $TargetHeight, $this->ImageSize[0], $this->ImageSize[1]);
				return imagejpeg($TargetImageData, $TargetPath, $Quality);
			}else{
				return imagejpeg($this->ImageObject, $TargetPath, $Quality);
			}
		}else{
			return false;
		}
		
	}


}
?>