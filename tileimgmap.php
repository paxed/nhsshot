<?php

include "common.php";



if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  if (isset($_POST['tileset']) && isset($tiledata[$_POST['tileset']]) &&
      $tiledata[$_POST['tileset']]['showmap'] && $tiledata[$_POST['tileset']]['istile'])
    $til = $_POST['tileset'];

}


if (!isset($til)) $til = "vanilla16";


header('Content-type: text/html; charset=iso-8859-1');

print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'."\n";

print '<html>';
print '<head>'."\n";
print '<title>NetHack tileset - imagemap with wikilinks</title>'."\n";

print '</head><body>'."\n";



print '<h1><a href="http://www.nethack.org/">NetHack</a> tileset imagemap with wikilinks</h1>'."\n";

print '<p><small>Also see <a href="tilecmp.php">tile comparisons</a>';
print ' and how the <a href="./">map actually looks</a></small>.';


print '<form method="POST" action="'.phpself_querystr().'" name="f1">'."\n";

print '<p>Tileset ';
print '<select name="tileset">';
while (list($key, $val) = each($tiledata)) {
  if ($val['showmap'] && $val['istile']) {
    print '<option value="'.$key.'"';
    if ($key == $til) {
      print ' selected';
    }
    print '>'.$val['name'].'</option>'."\n";
  }
 }
print '</select>';

print '<input type="Submit" value="Change">';

print '</form>'."\n";

print "<p>\n";


$val = $tiledata[$til];

$w = $val['width'];
$h = $val['height'];
$modd = $val['datawid'];

$tilenum = 0;

$tilenames = file('tilenames.txt');

print '<img src="'.$val['data'].'" usemap="#tilecoords">'."\n";

print '<map name="tilecoords">'."\n";

foreach ($tilenames as $tilename) {

  $tname = preg_replace('/\n/', '', $tilename);

  if (preg_match('/ \/ /', $tname))
    $tname = preg_replace('/(.*) \/ (.*)/', '\2', $tname);

  $tname = 'http://nethackwiki.com/wiki/'.$tname;

  $xofs = (($val['width'] * floor($tilenum % $modd)));
  $yofs = ($val['height'] * floor($tilenum / $modd));

  print '<area shape="rect" coords="'.$xofs.','.$yofs.','.($xofs+$w-1).','.($yofs+$h-1).'" href="'.$tname.'">'."\n";

#  print 'rect '.$xofs.' '.$yofs.' '.($xofs+$w-1).' '.($yofs+$h-1).' [['.$tname.']]'."\n";

  $tilenum++;
}

print "</map>\n";


print '</body></html>';



?>