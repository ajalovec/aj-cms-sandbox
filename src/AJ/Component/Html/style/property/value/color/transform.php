<?php
namespace AJ\Template\Html\style\property\value\color;
/* ----------------------------------------------------------------------------------------------------------------
	HSL
	H: 0 - 360
	0/360 = rdeča,
	90 = zelena,
	180 = cian,
	240 = modra
	300 = magenta
	
	S: 0 - 100	# 0 = siva, 100 = polna barvnost
	L: 0 - 100	# 0 = črna, 100 = bela	
	
	
	Colors class - hex, RGB, HSL
	
	hex_2_RGB(#f00)
>	hex_2_HSL(#f00)
	RGB_2_hex($RGB)
	RGB_2_HSL($RGB)
	HSL_2_RGB($HSL)
>	HSL_2_hex($HSL)
	
---------------------------------------------------------------------------------------------------------------- */

class Transform {	
	private static $hexLoadedColors = array();
	
	
	function adjustHSL($HSL, $HSL_color = array())
	{
		$new_HSL = array();
		
		foreach($HSL as $key => $val) {
			$key = strtoupper($key);
			$current_val = $HSL_color[$key] ? $HSL_color[$key] : 0;
			
			// pogleda če je relativno ali absolutna vrednost
			switch(substr($val, 0, 1))
			{
				case '+':
				case '-':
					$val = $current_val + (int)$val;
			}
			
			// filter and validate value
			switch($key)
			{
				case 'H':
					$val = $val - (floor($val/360) * 360);
					break;
				case 'S':
				case 'L':
					if($val < 0) 		$val = 0;
					elseif($val > 100) 	$val = 100;
			}
			
			$new_HSL[$key] = $val;
		}
		/*
		trace($HSL_color);
		trace($HSL);
		trace($new_HSL);
		*/
		return $new_HSL;
	}
	
	
		
	/* ===============================================
	-> hex > RGB
	 ===============================================*/	
	static function hex_2_RGB($hex)
	{
		if($hex[0] == '#')
		{ 
			$hex = substr($hex, 1);
		}
		
		if(strlen($hex) < 6)
		{
			$hex = $hex . substr($hex, 0, (6 - strlen($hex)));
		}
		
		$hex = str_split($hex, 2);
		
		foreach($hex as $key => $val) {
			$hex[$key] = base_convert($val, 16, 10)." ";
		}
		
		return $hex;
	}
		
	/* ===============================================
	-> hex > HSL
	 ===============================================*/
	static function hex_2_HSL($hex)
	{
		$RGB = self::hex_2_RGB($hex);
		return self::RGB_2_HSL($RGB);
	}
	
	/* ===============================================
	-> RGB > hex
	 ===============================================*/
	static function RGB_2_hex($RGB)
	{
		foreach($RGB as $key => $val) {
			$val = base_convert(round($val), 10, 16);
			$RGB[$key] = strlen($val) === 1 ? '0'.$val:$val;
		}
		return implode('',$RGB);
	}
	
	/* ===============================================
	-> RGB > HSL
	 ===============================================*/
	static function RGB_2_HSL($RGB)
	{
		foreach($RGB as $key => $val) {
			$RGB[$key] = $val/255;
		}
		$RGBcopy = $RGB;
		asort($RGBcopy,SORT_NUMERIC); $Min = reset($RGBcopy);
		arsort($RGBcopy,SORT_NUMERIC); $Max = reset($RGBcopy);
		
		$Max_del = $Max - $Min;
		$L = ($Max + $Min) / 2;
		
		if($Max_del == 0) {
			 $H = 0; $S = 0;
		} else {
			 if($L < 0.5) $S = $Max_del / ($Max + $Min);
			 else					$S = $Max_del / (2 - $Max - $Min);
		
			 $R_del = ((($Max - $RGB[0]) / 6) + ($Max_del / 2)) / $Max_del;
			 $G_del = ((($Max - $RGB[1]) / 6) + ($Max_del / 2)) / $Max_del;
			 $B_del = ((($Max - $RGB[2]) / 6) + ($Max_del / 2)) / $Max_del;
		
			 			if($RGB[0] == $Max) $H = $B_del - $G_del;
			 else if($RGB[1] == $Max) $H = (1 / 3) + $R_del - $B_del;
			 else if($RGB[2] == $Max) $H = (2 / 3) + $G_del - $R_del;
		
			 if($H < 0) $H += 1;
			 if($H > 1) $H -= 1;
		}
		$HSL = array(H=>$H, S=>$S, L=>$L);
		$HSL = self::inc_dec_HSL($HSL,"inc");
		//print_r($HSL);
		return $HSL;
	}
	
	/* ===============================================
	-> HSL > RGB
	 ===============================================*/
	public static function HSL_2_RGB($HSL)
	{
		//print_r($HSL);
		$HSL = self::inc_dec_HSL($HSL,"dec");
		if($HSL['S'] == 0) {
			$R = $HSL['L'] * 255;
			$G = $HSL['L'] * 255;
			$B = $HSL['L'] * 255;
		} else {
		
			if($HSL['L'] < 0.5) $t2 = $HSL['L'] * (1 + $HSL['S']);
			else           	  $t2 = ($HSL['L'] + $HSL['S']) - ($HSL['S'] * $HSL['L']);
			
			$t1 = 2 * $HSL['L'] - $t2;
			$R = intval(255 * self::Hue_2_RGB($t1, $t2, $HSL['H']+(1/3)));
			$G = intval(255 * self::Hue_2_RGB($t1, $t2, $HSL['H']));
			$B = intval(255 * self::Hue_2_RGB($t1, $t2, $HSL['H']-(1/3)));
		}
		
		return array(round($R), round($G), round($B));
	}
		
	/* ===============================================
	-> HSL > hex
	 ===============================================*/
	static function HSL_2_hex($HSL)
	{
		$RGB = self::HSL_2_RGB($HSL);
		return self::RGB_2_hex($RGB);
	}
	
	
	/* ===============================================
	-> Hue > RGB
	 ===============================================*/
	private static function Hue_2_RGB($v1, $v2, $vH)
	{
		if($vH < 0) $vH += 1;
		if($vH > 1) $vH -= 1;
		if((6 * $vH) < 1) return ($v1 + ($v2 - $v1) * 6 * $vH);
		if((2 * $vH) < 1) return ($v2);
		if((3 * $vH) < 2) return ($v1 + ($v2 - $v1) * ((2 / 3) - $vH) * 6);
		return ($v1);
	}
	
	private static function inc_dec_HSL($HSL, $type, $indec = array(H=>360, S=>100, L=>100))
	{
		if($type=="inc") {
			foreach($HSL as $key=>$val)
				$HSL[$key] = $val*$indec[$key];
		} else if($type=="dec") {
			foreach($HSL as $key=>$val)
				$HSL[$key] = $val/$indec[$key];
		} else
			return false;
		
		return $HSL;
	}

}

