color-tool
==========

A PHP toolkit for converting between Hex, RGB, and HSL colors. Also can calculate hue shifts, monochromatic schemes, etc.

First initialize a ColorTool object:

	$color = new ColorTool();

Then use one of the following three functions to give it a value:

	$color->set_hex("#000000");
	$color->set_rgb(array($r, $g, $b));
	$color->set_hsl(array($h, $s, $l));

View `index.php` to see some examples.
