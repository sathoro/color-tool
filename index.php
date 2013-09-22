<?

if (!isset($_GET['hex'])) {
  die("Please supply a hexadecimal value, <a href='?hex=FF0011'>example</a>");
}

require "ColorTool.php";

$color = new ColorTool();
$color->set_hex($_GET['hex']);

echo "<h4>Monochromatic</h4>";

$monochromatic = $color->monochromatic(10);
$color->display_hsls($monochromatic);

echo "<h4>Tones</h4>";

$tones = $color->tones(10);
$color->display_hsls($tones);

echo "<h4>Shades</h4>";

$shades = $color->shades(10);
$color->display_hsls($shades);

echo "<h4>Tints</h4>";

$tints = $color->tints(10);
$color->display_hsls($tints);

echo "<h4>Similar Colors</h4>";

$similar = $color->similar(10);
$color->display_hsls($similar);

echo "<h4>Complementary</h4>";

$complementary = $color->hue_shift(.5);
$color->display_hsl($complementary);

echo "<h4>Split Complementary</h4>";

$color->display_hsl($color->hue_shift(.55));
$color->display_hsl($color->get_hsl());
$color->display_hsl($color->hue_shift(.45));

echo "<h4>Analogous Colors</h4>";

$color->display_hsl($color->hue_shift(-.0833));
$color->display_hsl($color->get_hsl());
$color->display_hsl($color->hue_shift(.0833));

echo "<h4>Triadic Colors</h4>";

$color->display_hsl($color->hue_shift(-.333));
$color->display_hsl($color->get_hsl());
$color->display_hsl($color->hue_shift(.333));

echo "<h4>Tetradic Colors</h4>";

$color->display_hsl($color->hue_shift(-.14));
$color->display_hsl($color->get_hsl());
$color->display_hsl($color->hue_shift(.35));
$color->display_hsl($color->hue_shift(.49));