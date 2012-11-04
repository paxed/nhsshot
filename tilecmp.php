<?php

include "common.php";


function printcss()
{
  global $tiledata;

  print '<style type="text/css">
body { }
';

  print '.f0 { color: #000000; background: black; }
.f1 { color: #aa0000; background: black; }
.f2 { color: #00aa00; background: black; }
.f3 { color: #aa5500; background: black; }
.f4 { color: #0000aa; background: black; }
.f5 { color: #aa00aa; background: black; }
.f6 { color: #00aaaa; background: black; }
.f7 { color: #555555; background: black; }
.f8 { color: #aaaaaa; background: black; }
.f9 { color: #ff5555; background: black; }
.f10 { color: #55ff55; background: black; }
.f11 { color: #ffff55; background: black; }
.f12 { color: #5555ff; background: black; }
.f13 { color: #ff55ff; background: black; }
.f14 { color: #55ffff; background: black; }
.f15 { color: #ffffff; background: black; }
';

  $z = 0;

  while (list($key, $val) = each($tiledata)) {
    if ($val['istile']) {
      print '
.t'.$z.' {
	width:'.$val['width'].'px;
	height:'.$val['height'].'px;
	background-image:url('.$val['data'].');
	background-repeat: no-repeat;
	margin: 0;
	padding: 0;
}
';
    }
    $z++;
  }

  print '</style>
';

}

function cmptile($tilenum)
{
  global $tiledata;
  $hexchars = '0123456789abcdef';

  $y = 0;
  $x = 0;
  $z = 0;

  reset($tiledata);

  print '<table border="1">';

  while (list($key, $val) = each($tiledata)) {

    $modd = $val['datawid'];

    print '<tr>';

    print '<td style="padding:0.5em;">';
    if ($val['istile'])
      print '<a href="'.$val['data'].'">'.$val['name'].'</a>';
    else
      print $val['name'];
    print '</td>';

    print '<td style="padding:0.5em;width:'.$val['width'].'px;height:'.$val['height'].'px;">';
    if ($val['istile']) {
      if (($val['width'] * floor($tilenum % $modd)) == 0) $xofs = 0;
      else $xofs = -(($val['width'] * floor($tilenum % $modd))-1);
      $yofs = -($val['height'] * floor($tilenum / $modd));
      print '<div class="t'.$z.'" style="top:0;left:0;background-position:'.$xofs.'px '.$yofs.'px;">';
      print '</div>';
    } else {
      $chr = $val['data'][$tilenum]['char'];
      $col = strpos($hexchars, $val['data'][$tilenum]['color']);
      print '<pre><span class="f'.$col.'">'.$chr.'</span></pre>';
    }
    $x += $val['width'];
    $z++;
    print '</td>';

    print '</tr>';

  }

  print '</table>';

}

$tilenames = file('tilenames.txt');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['tile']) && isset($tilenames[$_POST['tile']]))
    $tile = $_POST['tile'];
  else if (isset($_GET['tile']) && isset($tilenames[$_GET['tile']]))
    $tile = $_GET['tile'];
  else
    $tile = array_rand($tilenames);
} else if (isset($_GET['tile']) && isset($tilenames[$_GET['tile']]))
  $tile = $_GET['tile'];
 else
   $tile = array_rand($tilenames);

header('Content-type: text/html; charset=iso-8859-1');

print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'."\n";

print '<html>';
print '<head>'."\n";
print '<title>NetHack tileset tile comparison</title>'."\n";

printcss();

print '</head><body>'."\n";

print '<h1><a href="http://www.nethack.org/">NetHack</a> tileset tile comparison</h1>';

print '<p><small>See also how the <a href="./">map actually looks</a>';
print ' and <a href="tileimgmap.php">tile image map</a></small>'."\n";

print '<form method="POST" action="'.phpself_querystr().'" name="f1">';

print 'Tile: ';
print '<select name="tile">';
for ($x = 0; $x < count($tilenames); $x++) {
  print '<option value="'.$x.'"';
  if ($x == $tile) {
    print ' selected';
  }
  print '>'.$tilenames[$x].'</option>';
 }
print '</select>';

print '<input type="Submit" value="Generate">';

print '</form>';

print '<p>';
if ($tile > 0) print '<a href="'.phpself_querystr(array('tile'=>($tile-1))).'">Prev</a>';
else print 'Prev';
print '&nbsp;|&nbsp;';
if ($tile < count($tilenames)-1) print '<a href="'.phpself_querystr(array('tile'=>($tile+1))).'">Next</a>';
else print 'Next';
print '&nbsp;|&nbsp;';
print '<a href="'.phpself_querystr(array()).'">Random</a>';

#if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  print '<p>';
  cmptile($tile);
#}

print '<p><a href="'.phpself_querystr(array('tile'=>$tile)).'">Direct link to this comparison</a>';

print '</body></html>';

?>