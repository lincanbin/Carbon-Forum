<?php
/*
 * Material-Design-Avatars
 * https://github.com/lincanbin/Material-Design-Avatars
 *
 * Copyright 2015, Canbin Lin
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Create material deisgn avatars for users just like Gmail or Messager in Android.
 */
class MDAvtars
{
	public $Char;
	public $AvatarSize;
	public $Padding;
	public $Avatar;
	public $FontFile;
	public $IsNotletter;
	public $LetterFont;
	public $AsianFont;
	public $EnableAsianChar;


	function __construct($Char, $AvatarSize = 256)
	{
		$this->Char = strtoupper(mb_substr($Char, 0, 1, "UTF-8"));
		$this->AvatarSize = $AvatarSize;
		$this->Padding = 30*($this->AvatarSize/256);
		$this->LetterFont = dirname(__FILE__).'/fonts/SourceCodePro-Light.ttf';
		$this->AsianFont = dirname(__FILE__).'/fonts/SourceHanSansCN-Normal.ttf';
		$this->EnableAsianChar = is_file($this->AsianFont);

		$CNChar=ord($this->Char);
		if (!$this->EnableAsianChar && 
			preg_match("/^[\x7f-\xff]/", $this->Char) && 
			!($CNChar>=ord("A") && $CNChar<=ord("z"))
		){
		//如果是中文，并且没有中文字体包，则按拼音首字母对其进行转换
			$CNByte = iconv("UTF-8","gb2312", $this->Char);
			$Code=ord($CNByte{0}) * 256 + ord($CNByte{1}) - 65536;//求其偏移量
			if($Code>=-20319 and $Code<=-20284) $this->Char = "A";
			if($Code>=-20283 and $Code<=-19776) $this->Char = "B";
			if($Code>=-19775 and $Code<=-19219) $this->Char = "C";
			if($Code>=-19218 and $Code<=-18711) $this->Char = "D";
			if($Code>=-18710 and $Code<=-18527) $this->Char = "E";
			if($Code>=-18526 and $Code<=-18240) $this->Char = "F";
			if($Code>=-18239 and $Code<=-17923) $this->Char = "G";
			if($Code>=-17922 and $Code<=-17418) $this->Char = "H";
			if($Code>=-17417 and $Code<=-16475) $this->Char = "J";
			if($Code>=-16474 and $Code<=-16213) $this->Char = "K";
			if($Code>=-16212 and $Code<=-15641) $this->Char = "L";
			if($Code>=-15640 and $Code<=-15166) $this->Char = "M";
			if($Code>=-15165 and $Code<=-14923) $this->Char = "N";
			if($Code>=-14922 and $Code<=-14915) $this->Char = "O";
			if($Code>=-14914 and $Code<=-14631) $this->Char = "P";
			if($Code>=-14630 and $Code<=-14150) $this->Char = "Q";
			if($Code>=-14149 and $Code<=-14091) $this->Char = "R";
			if($Code>=-14090 and $Code<=-13319) $this->Char = "S";
			if($Code>=-13318 and $Code<=-12839) $this->Char = "T";
			if($Code>=-12838 and $Code<=-12557) $this->Char = "W";
			if($Code>=-12556 and $Code<=-11848) $this->Char = "X";
			if($Code>=-11847 and $Code<=-11056) $this->Char = "Y";
			if($Code>=-11055 and $Code<=-10247) $this->Char = "Z";
		}
		if(in_array($this->Char, str_split('QWERTYUIOPASDFGHJKLZXCVBNM0123456789', 1))){
			$this->IsNotletter = false;
			$this->FontFile = $this->LetterFont;
		}else{
			$this->IsNotletter = true;
			$this->FontFile = $this->AsianFont;
		}
		$this->Initialize();
	}

	private function Initialize()
	{
		//extension_loaded('gd')
		$Width  = $this->AvatarSize;//Width of avatar
		$Height = $this->AvatarSize;//Height of avatar
		$Padding = $this->Padding;
		$this->Avatar = imagecreatetruecolor($Width, $Height);
		//全透明背景
		imageSaveAlpha($this->Avatar, true);
		$BackgroundAlpha = imagecolorallocatealpha($this->Avatar, 255, 255, 255, 127);
		imagefill($this->Avatar, 0, 0, $BackgroundAlpha);
		//抗锯齿
		imageantialias($this->Avatar, true);
		//Material Design参考颜色
		//http://www.google.com/design/spec/style/color.html#color-color-palette
		$MaterialDesignColor = array(
			[ 255 , 235 , 238 ],
			[ 255 , 205 , 210 ],
			[ 239 , 154 , 154 ],
			[ 229 , 115 , 115 ],
			[ 239 , 83 , 80 ],
			[ 244 , 67 , 54 ],
			[ 229 , 57 , 53 ],
			[ 211 , 47 , 47 ],
			[ 198 , 40 , 40 ],
			[ 183 , 28 , 28 ],
			[ 255 , 138 , 128 ],
			[ 255 , 82 , 82 ],
			[ 255 , 23 , 68 ],
			[ 213 , 0 , 0 ],
			[ 252 , 228 , 236 ],
			[ 248 , 187 , 208 ],
			[ 244 , 143 , 177 ],
			[ 240 , 98 , 146 ],
			[ 236 , 64 , 122 ],
			[ 233 , 30 , 99 ],
			[ 216 , 27 , 96 ],
			[ 194 , 24 , 91 ],
			[ 173 , 20 , 87 ],
			[ 136 , 14 , 79 ],
			[ 255 , 128 , 171 ],
			[ 255 , 64 , 129 ],
			[ 245 , 0 , 87 ],
			[ 197 , 17 , 98 ],
			[ 243 , 229 , 245 ],
			[ 225 , 190 , 231 ],
			[ 206 , 147 , 216 ],
			[ 186 , 104 , 200 ],
			[ 171 , 71 , 188 ],
			[ 156 , 39 , 176 ],
			[ 142 , 36 , 170 ],
			[ 123 , 31 , 162 ],
			[ 106 , 27 , 154 ],
			[ 74 , 20 , 140 ],
			[ 234 , 128 , 252 ],
			[ 224 , 64 , 251 ],
			[ 213 , 0 , 249 ],
			[ 170 , 0 , 255 ],
			[ 237 , 231 , 246 ],
			[ 209 , 196 , 233 ],
			[ 179 , 157 , 219 ],
			[ 149 , 117 , 205 ],
			[ 126 , 87 , 194 ],
			[ 103 , 58 , 183 ],
			[ 94 , 53 , 177 ],
			[ 81 , 45 , 168 ],
			[ 69 , 39 , 160 ],
			[ 49 , 27 , 146 ],
			[ 179 , 136 , 255 ],
			[ 124 , 77 , 255 ],
			[ 101 , 31 , 255 ],
			[ 98 , 0 , 234 ],
			[ 232 , 234 , 246 ],
			[ 197 , 202 , 233 ],
			[ 159 , 168 , 218 ],
			[ 121 , 134 , 203 ],
			[ 92 , 107 , 192 ],
			[ 63 , 81 , 181 ],
			[ 57 , 73 , 171 ],
			[ 48 , 63 , 159 ],
			[ 40 , 53 , 147 ],
			[ 26 , 35 , 126 ],
			[ 140 , 158 , 255 ],
			[ 83 , 109 , 254 ],
			[ 61 , 90 , 254 ],
			[ 48 , 79 , 254 ],
			[ 227 , 242 , 253 ],
			[ 187 , 222 , 251 ],
			[ 144 , 202 , 249 ],
			[ 100 , 181 , 246 ],
			[ 66 , 165 , 245 ],
			[ 33 , 150 , 243 ],
			[ 30 , 136 , 229 ],
			[ 25 , 118 , 210 ],
			[ 21 , 101 , 192 ],
			[ 13 , 71 , 161 ],
			[ 130 , 177 , 255 ],
			[ 68 , 138 , 255 ],
			[ 41 , 121 , 255 ],
			[ 41 , 98 , 255 ],
			[ 225 , 245 , 254 ],
			[ 179 , 229 , 252 ],
			[ 129 , 212 , 250 ],
			[ 79 , 195 , 247 ],
			[ 41 , 182 , 252 ],
			[ 3 , 169 , 244 ],
			[ 3 , 155 , 229 ],
			[ 2 , 136 , 209 ],
			[ 2 , 119 , 189 ],
			[ 1 , 87 , 155 ],
			[ 128 , 216 , 255 ],
			[ 64 , 196 , 255 ],
			[ 0 , 176 , 255 ],
			[ 0 , 145 , 234 ],
			[ 224 , 247 , 250 ],
			[ 178 , 235 , 242 ],
			[ 128 , 222 , 234 ],
			[ 77 , 208 , 225 ],
			[ 38 , 198 , 218 ],
			[ 0 , 188 , 212 ],
			[ 0 , 172 , 193 ],
			[ 0 , 151 , 167 ],
			[ 0 , 131 , 143 ],
			[ 0 , 96 , 100 ],
			[ 132 , 255 , 255 ],
			[ 24 , 255 , 255 ],
			[ 0 , 229 , 255 ],
			[ 0 , 184 , 212 ],
			[ 224 , 242 , 241 ],
			[ 178 , 223 , 219 ],
			[ 128 , 203 , 196 ],
			[ 77 , 182 , 172 ],
			[ 38 , 166 , 154 ],
			[ 0 , 150 , 136 ],
			[ 0 , 137 , 123 ],
			[ 0 , 121 , 107 ],
			[ 0 , 105 , 92 ],
			[ 0 , 77 , 64 ],
			[ 167 , 255 , 235 ],
			[ 100 , 255 , 218 ],
			[ 29 , 233 , 182 ],
			[ 0 , 191 , 165 ],
			[ 232 , 245 , 233 ],
			[ 200 , 230 , 201 ],
			[ 165 , 214 , 167 ],
			[ 129 , 199 , 132 ],
			[ 102 , 187 , 106 ],
			[ 76 , 175 , 80 ],
			[ 67 , 160 , 71 ],
			[ 56 , 142 , 60 ],
			[ 46 , 125 , 50 ],
			[ 27 , 94 , 32 ],
			[ 185 , 246 , 202 ],
			[ 105 , 240 , 174 ],
			[ 0 , 230 , 118 ],
			[ 0 , 200 , 83 ],
			[ 241 , 248 , 233 ],
			[ 220 , 237 , 200 ],
			[ 197 , 225 , 165 ],
			[ 174 , 213 , 129 ],
			[ 156 , 204 , 101 ],
			[ 139 , 195 , 74 ],
			[ 124 , 179 , 66 ],
			[ 104 , 159 , 56 ],
			[ 85 , 139 , 47 ],
			[ 51 , 105 , 30 ],
			[ 204 , 255 , 144 ],
			[ 178 , 255 , 89 ],
			[ 118 , 255 , 3 ],
			[ 100 , 221 , 23 ],
			[ 249 , 251 , 231 ],
			[ 240 , 244 , 195 ],
			[ 230 , 238 , 156 ],
			[ 220 , 231 , 117 ],
			[ 212 , 225 , 87 ],
			[ 205 , 220 , 57 ],
			[ 192 , 202 , 51 ],
			[ 164 , 180 , 43 ],
			[ 158 , 157 , 36 ],
			[ 130 , 119 , 23 ],
			[ 244 , 255 , 129 ],
			[ 238 , 255 , 65 ],
			[ 198 , 255 , 0 ],
			[ 174 , 234 , 0 ],
			[ 255 , 253 , 231 ],
			[ 255 , 249 , 196 ],
			[ 255 , 245 , 144 ],
			[ 255 , 241 , 118 ],
			[ 255 , 238 , 88 ],
			[ 255 , 235 , 59 ],
			[ 253 , 216 , 53 ],
			[ 251 , 192 , 45 ],
			[ 249 , 168 , 37 ],
			[ 245 , 127 , 23 ],
			[ 255 , 255 , 130 ],
			[ 255 , 255 , 0 ],
			[ 255 , 234 , 0 ],
			[ 255 , 214 , 0 ],
			[ 255 , 248 , 225 ],
			[ 255 , 236 , 179 ],
			[ 255 , 224 , 130 ],
			[ 255 , 213 , 79 ],
			[ 255 , 202 , 40 ],
			[ 255 , 193 , 7 ],
			[ 255 , 179 , 0 ],
			[ 255 , 160 , 0 ],
			[ 255 , 143 , 0 ],
			[ 255 , 111 , 0 ],
			[ 255 , 229 , 127 ],
			[ 255 , 215 , 64 ],
			[ 255 , 196 , 0 ],
			[ 255 , 171 , 0 ],
			[ 255 , 243 , 224 ],
			[ 255 , 224 , 178 ],
			[ 255 , 204 , 128 ],
			[ 255 , 183 , 77 ],
			[ 255 , 167 , 38 ],
			[ 255 , 152 , 0 ],
			[ 251 , 140 , 0 ],
			[ 245 , 124 , 0 ],
			[ 239 , 108 , 0 ],
			[ 230 , 81 , 0 ],
			[ 255 , 209 , 128 ],
			[ 255 , 171 , 64 ],
			[ 255 , 145 , 0 ],
			[ 255 , 109 , 0 ],
			[ 251 , 233 , 167 ],
			[ 255 , 204 , 188 ],
			[ 255 , 171 , 145 ],
			[ 255 , 138 , 101 ],
			[ 255 , 112 , 67 ],
			[ 255 , 87 , 34 ],
			[ 244 , 81 , 30 ],
			[ 230 , 74 , 25 ],
			[ 216 , 67 , 21 ],
			[ 191 , 54 , 12 ],
			[ 255 , 158 , 128 ],
			[ 255 , 110 , 64 ],
			[ 255 , 61 , 0 ],
			[ 221 , 38 , 0 ],
			[ 239 , 235 , 233 ],
			[ 215 , 204 , 200 ],
			[ 188 , 170 , 164 ],
			[ 161 , 136 , 127 ],
			[ 141 , 110 , 99 ],
			[ 121 , 85 , 72 ],
			[ 109 , 76 , 65 ],
			[ 93 , 64 , 55 ],
			[ 78 , 52 , 46 ],
			[ 62 , 39 , 35 ],
			[ 250 , 250 , 250 ],
			[ 245 , 245 , 245 ],
			[ 238 , 238 , 238 ],
			[ 224 , 224 , 224 ],
			[ 189 , 189 , 189 ],
			[ 158 , 158 , 158 ],
			[ 117 , 117 , 117 ],
			[ 97 , 97 , 97 ],
			[ 66 , 66 , 66 ],
			[ 33 , 33 , 33 ],
			[ 0 , 0 , 0 ],
			[ 255 , 255 , 255 ],
			[ 236 , 239 , 241 ],
			[ 207 , 216 , 220 ],
			[ 176 , 187 , 197 ],
			[ 144 , 164 , 174 ],
			[ 120 , 144 , 156 ],
			[ 96 , 125 , 139 ],
			[ 84 , 110 , 122 ],
			[ 69 , 90 , 100 ],
			[ 55 , 71 , 79 ],
			[ 38 , 50 , 56 ]
		);
		$BackgroundColorIndex = mt_rand(0, count($MaterialDesignColor)-1);
		$BackgroundColor = imagecolorallocate($this->Avatar, 
											  $MaterialDesignColor[$BackgroundColorIndex][0],
											  $MaterialDesignColor[$BackgroundColorIndex][1],
											  $MaterialDesignColor[$BackgroundColorIndex][2]
											 );
		//画一个居中圆形
		imagefilledellipse($this->Avatar, 
						   $Width/2, 
						   $Height/2, 
						   $Width, 
						   $Height, 
						   $BackgroundColor
		);
		//字体
		$FontColor = imagecolorallocate($this->Avatar, 255, 255, 255);
		if($this->IsNotletter){
			//中文字符偏移
			$FontSize = $Width - $Padding * 3.5;
			$X = $Padding + (-2/166)*$FontSize;
			$Y = $Height - $Padding - (23.5/166) * $FontSize;
		}else{
			$FontSize = $Width - $Padding * 2;
			$X = $Padding + (20/196)*$FontSize;
			$Y = $Height - $Padding - (13/196)*$FontSize;
		}
		// 在圆正中央填入字符
		imagettftext($this->Avatar, 
					 $FontSize, 
					 0, 
					 $X, 
					 $Y, 
					 $FontColor, 
					 $this->FontFile, 
					 $this->Char
		);
	}

	private function Resize($TargetSize)
	{
		if (isset($this->Avatar)) {
			if ($this->AvatarSize > $TargetSize) {
				$Percent      = $TargetSize / $this->AvatarSize;
				$TargetWidth  = round($this->AvatarSize * $Percent);
				$TargetHeight = round($this->AvatarSize * $Percent);
				$TargetImageData = imagecreatetruecolor($TargetWidth, $TargetHeight);
				//全透明背景
				imageSaveAlpha($TargetImageData, true);
				$BackgroundAlpha = imagecolorallocatealpha($TargetImageData, 255, 255, 255, 127);
				imagefill($TargetImageData, 0, 0, $BackgroundAlpha);
				imagecopyresampled($TargetImageData, $this->Avatar, 0, 0, 0, 0, $TargetWidth, $TargetHeight, $this->AvatarSize, $this->AvatarSize);
				return $TargetImageData;
			} else {
				return $this->Avatar;
			}
		} else {
			return false;
		}
	}

	public function Free()
	{
		imagedestroy($this->Avatar);
	}

	public function Output2Browser($AvatarSize=0)
	{
		if(!$AvatarSize){
			$AvatarSize = $this->AvatarSize;
		}
		header ('Content-Type: image/png');
		return imagepng($this->Resize($AvatarSize));
	}

	public function Save($Path, $AvatarSize=0)
	{
		if(!$AvatarSize){
			$AvatarSize = $this->AvatarSize;
		}
		return imagepng($this->Resize($AvatarSize), $Path);
	}
}