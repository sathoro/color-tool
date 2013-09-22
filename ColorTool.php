<?

class ColorTool {
	private $hex;
	private $rgb;
	private $hsl;

	public function __construct() {
		$this->hex = null;
		$this->rgb = null;
		$this->hsl = null;
	}

	public function populate_colors() {
		if ($this->hex != null) {
			$this->rgb = $this->hex2rgb($this->hex);
			$this->hsl = $this->rgb2hsl($this->rgb);
		}
		else if ($this->rgb != null) {
			$this->hex = $this->rgb2hex($this->rgb);
			$this->hsl = $this->rgb2hsl($this->rgb);
		}
		else if ($this->hsl != null) {
			$this->rgb = $this->hsl2rgb($this->hsl);
			$this->hex = $this->rgb2hex($this->rgb);
		}
	}

	public function hue_shift($amount) {
		$h = $this->hsl[0] + $amount;
		while ($h >= 1.0) $h -= 1.0;
		while ($h < 0.0) $h += 1.0;
		return array($h, $this->hsl[1], $this->hsl[2]);
	}

	public function set_hex($hex) {
		$this->hex = $hex;
		$this->populate_colors();
	}

	public function set_rgb($rgb) {
		$this->rgb = $rgb;
		$this->populate_colors();
	}

	public function set_hsl($hsl) {
		$this->hsl = $hsl;
		$this->populate_colors();
	}

	public function get_hex() {
		if ($this->hex != null) {
			return $this->hex;
		}
		else {
			$this->error();
		}
	}

	public function get_rgb() {
		if ($this->rgb != null) {
			return $this->rgb;
		}
		else {
			$this->error();
		}
	}

	public function get_hsl() {
		if ($this->hsl != null) {
			return $this->hsl;
		}
		else {
			$this->error();
		}
	}

	public function display_hsls($colors, $width = 50) {
		foreach ($colors as $color) {
			$color = $this->hsl2rgb($color);
  			echo "<div style='display:inline-block;width:{$width}px;height:50px;background:rgb({$color[0]},{$color[1]},{$color[2]})'></div>";
		}
	}

	public function display_hsl($color, $width = 50) {
		$this->display_hsls(array($color), $width);
	}

	private function error() {
		throw new Exception("You must first set either a hex, rgb, or hsl value first.");
	}

	/*
	 * Spectrum of colors with same hue/saturation and variable lightness
	*/
	public function monochromatic($num) {
		$colors = array();
		$hsl = $this->get_hsl();

		for ($i = $hsl[2] - .03 * $num / 2; $i < $hsl[2] + (.03 * $num / 2); $i += .03) {
			$colors[] = array($hsl[0], $hsl[1], $i);
		}

		return $colors;
	}

	/*
	 * Tones have same hue/lightness and variable saturation
	*/
	public function tones($num) {
		$colors = array();
		$hsl = $this->get_hsl();

		for ($i = 0; $i <= 1; $i += 1 / $num) {
			$colors[] = array($hsl[0], $i, $hsl[2]);
		}

		return $colors;
	}

	/*
	 * Shades are darker colors with same hue and saturation
	*/
	public function shades($num) {
		$hsl = $this->get_hsl();
		$colors = array();

		if ($hsl[2] == 0) return $colors;

		for ($i = $hsl[2]; $i > 0; $i -= $hsl[2] / $num) {
			$colors[] = array($hsl[0], $hsl[1], $i);
		}

		return $colors;
	}

	/*
	 * Tints are brighter colors with same hue and saturation
	*/
	public function tints($num) {
		$hsl = $this->get_hsl();
		$colors = array();

		if ($hsl[2] == 1) return $colors;

		for ($i = $hsl[2]; $i <= 1; $i += (1 - $hsl[2]) / $num) {
			$colors[] = array($hsl[0], $hsl[1], $i);
		}

		return $colors;
	}

	/*
	 * Calculates similar colors by making slight changes to the hue
	*/
	public function similar($num) {
		$hsl = $this->get_hsl();
		$colors = array();
		
		for ($i = $hsl[0] - .015 * $num / 2; $i < $hsl[0] + (.015 * $num / 2); $i += .015) {
			$colors[] = array($i, $hsl[1], $hsl[2]);
		}

		return $colors;
	}

	private function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if (strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		}
		else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}

		return array($r, $g, $b);

	}

	private function rgb2hex($rgb) {
		$hex = str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

		return $hex;
	}

	private function rgb2hsl($rgb){
	    $r = $rgb[0] / 255;
	    $g = $rgb[1] / 255;
	    $b = $rgb[2] / 255;

	    $max = max($r, $g, $b);
	    $min = min($r, $g, $b);

	    $l = ($max + $min) / 2;

	    if ($max == $min) {
	        $h = $s = 0;
	    } else {
	        $d = $max - $min;
	        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
	        switch($max){
	            case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
	            case $g: $h = ($b - $r) / $d + 2; break;
	            case $b: $h = ($r - $g) / $d + 4; break;
	        }
	        $h /= 6;
	    }

	    return array($h, $s, $l);
	}

	private function hsl2rgb($hsl) {
		$h = $hsl[0];
		$s = $hsl[1];
		$l = $hsl[2];

		if ($s == 0){
			$r = $g = $b = $l;
		}
		else {
			$q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
			$p = 2 * $l - $q;
			$r = $this->hue2rgb($p, $q, $h + 1/3);
			$g = $this->hue2rgb($p, $q, $h);
			$b = $this->hue2rgb($p, $q, $h - 1/3);
		}

		return array(round($r * 255), round($g * 255), round($b * 255));
	}

	private function hue2rgb($p, $q, $t){
		if ($t < 0) $t += 1;
		if ($t > 1) $t -= 1;
		if ($t < 1/6) return $p + ($q - $p) * 6 * $t;
		if ($t < 1/2) return $q;
		if ($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;
		return $p;
	}

}

?>