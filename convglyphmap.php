<?php

$dumpfiles = array(
		   'glyph_dump_1176516037.txt'=>'Dungeons of Doom',
		   'glyph_dump_1176522356.txt'=>'Oracle',
		   'glyph_dump_1176522358.txt'=>'Sokoban',
		   'glyph_dump_1176522360.txt'=>'Minetown',
		   'glyph_dump_1176522361.txt'=>'Medusa',
		   'glyph_dump_1176522362.txt'=>'Gehennom'
);


while (list($key, $val) = each($dumpfiles)) {

  $arr = file($key);

  $fh = fopen($key.'.dat', 'wb');

  $hei = count($arr);
  $wid = count(explode(',', $arr[0]));

  $dat = pack("SS", $wid, $hei);

  foreach ($arr as $l) {
    $glyphs = explode(',', $l);
    foreach ($glyphs as $g) {
      $dat .= pack("S", $g);
    }
  }

  fwrite($fh, $dat);

  fclose($fh);
}




?>

