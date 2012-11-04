<?php

$tilenames = file('tilenames.txt');

$val = array('datawid'=>40, # tiles per line in image file
	     'width'=>16,
	     'height'=>16);


$w = $val['width'];
$h = $val['height'];

$modd = $val['datawid'];

$tilenum = 0;

foreach ($tilenames as $tilename) {

  $tname = preg_replace('/\n/', '', $tilename);

  if (preg_match('/ \/ /', $tname))
    $tname = preg_replace('/(.*) \/ (.*)/', '\2|\1', $tname);

  $xofs = (($val['width'] * floor($tilenum % $modd)));
  $yofs = ($val['height'] * floor($tilenum / $modd));

  print 'rect '.$xofs.' '.$yofs.' '.($xofs+$w-1).' '.($yofs+$h-1).' [['.$tname.']]'."\n";

  $tilenum++;
}


?>