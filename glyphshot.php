<?php

include "common.php";

function printcss($tile=NULL)
{
  if (!$tile) return;

  print '<style type="text/css">
body { }
';

  print '.f0 { color: #555555; background: black; }
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

pre span { color: #aaaaaa; background: black; }

.info {
  z-index: 24;
}

.info:hover {
  z-index: 25;
  background-color: #303030;
}

.info span {
  display: none;
}

.info:hover span{
  display: block;
  position: absolute;
  top: -3em; left: 25%; min-width: 15em;
  padding: .5em;
  border: 1px solid black;
  background-color: lightgray;
  color: black;
  font-family: monospace;
}

';


  if ($tile['istile']) {
    print '
.t {
	width:'.$tile['width'].'px;
	height:'.$tile['height'].'px;
	background-image:url('.$tile['data'].');
	background-repeat: no-repeat;
	margin: 0;
	padding: 0;
	position: absolute;
}
';

  }

  print '</style>
';

}


function printmap($map, $tilenames=NULL, $tiles='tty')
{
  global $tiledata;
  $hexchars = '0123456789abcdef';

  if (!$tiledata[$tiles]) return;

  $modd = (isset($tiledata[$tiles]['datawid']) ? $tiledata[$tiles]['datawid'] : 0);

  $y = 0;
  $prevg = -1;

  foreach ($map as $l) {
    $glyphs = explode(',', $l);
    $x = 0;
    foreach ($glyphs as $g) {
      $g = intval($g);
      if ($g < 0) {
	if (rand(0,10) == 10) {
	  if ($g == -1) $g = rand(0, 392); # random monster
	  else if ($g == -2) $g = rand(0, 432) + 394; # random item
	  else if ($g == -3) $g = rand(0, 432 + 304); # random monster or item
	} else $g = 848;
      }
      if ($tiledata[$tiles]['istile']) {
	print '<div class="info t" style="top:'.$y.'px;left:'.$x.'px;background-position:-'.(($tiledata[$tiles]['width'] * floor($g % $modd))).'px -'.($tiledata[$tiles]['height'] * floor($g / $modd)).'px;">';
	if ($tilenames)
	  print '<span>'.$tilenames[$g].'&nbsp;(#'.$g.')</span>';
	print '</div>';
      } else {
	  /*
	$chr = $tiledata[$tiles]['data']{$g*2};
	$col = strpos($hexchars, $tiledata[$tiles]['data']{$g*2+1});
	  */
	$chr = $tiledata[$tiles]['data'][$g]['char'];
	$col = strpos($hexchars, $tiledata[$tiles]['data'][$g]['color']);

	if ($g == $prevg) {
	  print $chr;
	} else {
	  if ($tilenames) {
	    if ($prevg != -1)
	      print '<span>'.$tilenames[$prevg].'</span></span>';
	    print '<span class="info f'.$col.'">'.$chr;
	  } else {
	    if ($prevg != -1)
	      print '</span>';
	    print '<span class="f'.$col.'">'.$chr;
	  }
	}
      }
      $x += (isset($tiledata[$tiles]['width']) ? $tiledata[$tiles]['width'] : 16);
      $prevg = $g;
    }
    $y += (isset($tiledata[$tiles]['height']) ? $tiledata[$tiles]['height'] : 16);
    print "\n";
  }
  if (!$tiledata[$tiles]['istile']) { print '</span>'; }
}


function add_external_tileset($external_tilestr)
{
  global $tiledata;
  list($efname, $efwidth, $efheight, $efdatawid) = explode("|", $external_tilestr, 4);
  if ($efdatawid < 0 || !isset($efdatawid)) $efdatawid = 40;
  if ($efwidth < 2) $efwidth = 16;
  if ($efheight < 2) $efheight = 16;
  $tiledata['external'] = array('name'=>'(External)', 'istile'=>1, 'showmap'=>1,
				'data'=>$efname, 'datawid'=>$efdatawid, 'width'=>$efwidth, 'height'=>$efheight);
}

if (!isset($_GET['go'])) $_GET['go'] = 0;

if (($_SERVER['REQUEST_METHOD'] == 'POST') || ($_GET['go'] == '1')) {

  if (!($_SERVER['REQUEST_METHOD'] == 'POST') && ($_GET['go'] == '1')) {
    $_POST = $_GET;
  }

  if (isset($_POST['dump']) && isset($dumpfiles[$_POST['dump']]))
    $dump = $_POST['dump'];
  else
    $dump = array_rand($dumpfiles);

  $arr = file($dump);

  if (isset($_POST['tileset']) && isset($tiledata[$_POST['tileset']]) && $tiledata[$_POST['tileset']]['showmap'])
    $til = $_POST['tileset'];
  else if (isset($_POST['external'])) {
    add_external_tileset($_POST['external']);
    $til = 'external';
  } else {
    $tmp = 0;
    do {
      $til = array_rand($tiledata);
    } while ((!$tiledata[$til]['showmap']) && (++$tmp < 20));
    if ($tmp >= 20) $til = 'tty';
  }

  $map = array();

  foreach ($arr as $line) {
    array_push($map, $line);
  }

  if (isset($_POST['tooltip']) && $_POST['tooltip'] == 'on') $tooltip = 1;

} else {
  if (isset($_GET['dump']) && preg_match('/^-?[0-9]+$/', trim($_GET['dump']))) {
    $tmp = trim($_GET['dump']);
    $tmp_ak = array_keys($dumpfiles);
    if ($tmp < 0) $tmp = array_rand($tmp_ak);
    if (isset($tmp_ak[$tmp])) {
      $dump = $tmp_ak[$tmp];
      $map = array();
      $arr = file($dump);
      foreach ($arr as $line) {
	array_push($map, $line);
      }
    }
  }

  if (isset($_GET['tileset']) && isset($tiledata[$_GET['tileset']]) && $tiledata[$_GET['tileset']]['showmap']) {
    $til = $_GET['tileset'];
  } else if (isset($_GET['external'])) {
    add_external_tileset($_GET['external']);
    $til = 'external';
  }

  if (isset($_GET['tooltip']) && (intval($_GET['tooltip']) > 0)) $tooltip = 1;
}

if (!isset($til)) $til = 'tty';
if (!isset($dump)) $dump = 'glyph_dump_1176516037.txt';

header('Content-type: text/html; charset=iso-8859-1');

print '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">'."\n";

print '<html>';
print '<head>'."\n";
print '<title>NetHack tileset screenshots</title>'."\n";

printcss($tiledata[$til]);

print '</head><body>'."\n";

print '<h1><a href="http://www.nethack.org/">NetHack</a> tileset screenshots</h1>'."\n";

print '<p><small>Also see <a href="tilecmp.php">tile comparisons</a>';
print ' and <a href="tileimgmap.php">tile image map</a></small>'."\n";

print '<form method="POST" action="'.phpself_querystr().'" name="f1">'."\n";

print '<table>'."\n";
print '<tr>';
print '<td>Tileset</td>';
print '<td><select name="tileset">';
while (list($key, $val) = each($tiledata)) {
  if ($val['showmap']) {
    print '<option value="'.$key.'"';
    if ($key == $til) {
      print ' selected';
    }
    print '>'.$val['name'].'</option>'."\n";
  }
 }
print '</select></td>';
print '</tr>'."\n";

print '<tr>';
print '<td>Level</td>';
print '<td><select name="dump">';
$dumpcount = 0;
$dumpnum = 0;
while (list($key, $val) = each($dumpfiles)) {
  print '<option value="'.$key.'"';
  if ($key == $dump) {
    print ' selected';
    $dumpnum = $dumpcount;
  }
  print '>'.$val.'</option>'."\n";
  $dumpcount++;
 }
print '</select></td>';
print '</tr>'."\n";

print '<tr>';
print '<td>Tile tooltips</td>';
print '<td><input type="checkbox" name="tooltip"';
if (isset($tooltip)) print ' checked';
print '></td>';
print '</tr>';

print '<tr>';
print '<td colspan="2"><input type="Submit" value="Generate"></td>';
print '</tr>';

print '</table>'."\n";

if (isset($_GET['external']))
  print '<input type="hidden" name="external" value="'.$_GET['external'].'">'."\n";

print '</form>'."\n";

if (isset($map) && isset($til)) {

  if (isset($tiledata[$til]['comment'])) {
    print '<p>Comment: <em>'.$tiledata[$til]['comment'].'</em>';
  }

  if ($tiledata[$til]['istile']) {
    print '<p>tileset: <A href="'.$tiledata[$til]['data'].'">file</A>';
    print ', width: '.$tiledata[$til]['width'].', height: '.$tiledata[$til]['height'].', ';
  }

  unset($tmp);
  $tmp['dump'] = strval($dumpnum);
  if (isset($_GET['external']))
    $tmp['external'] = $_GET['external'];
  else if (isset($_POST['external']))
    $tmp['external'] = $_POST['external'];
  else
    $tmp['tileset'] = $til;
  if (isset($tooltip))
    $tmp['tooltip'] = 1;
  print '<A href="'.phpself_querystr($tmp).'">Link to this view</A>';

  if (isset($tooltip)) {
    $tilenames = file('tilenames.txt');
  } else
    $tilenames = NULL;

  print '<div style="position:relative;">'."\n";
  if (!$tiledata[$til]['istile']) print '<pre>';
  printmap($map, $tilenames, $til);
  if (!$tiledata[$til]['istile']) print '</pre>';
  print '</div>'."\n";
}

print '</body></html>';

?>