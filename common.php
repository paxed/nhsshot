<?php

function query_str($params, $sep='&amp;', $quoted=0, $encode=1)
{
  $str = '';
  foreach ($params as $key => $value) {
    $str .= (strlen($str) < 1) ? '' : $sep;
    if (($value=='') || is_null($value)) {
      $str .= $key;
      continue;
    }
    $rawval = ($encode) ? rawurlencode($value) : $value;
    if ($quoted) $rawval = '"'.$rawval.'"';
    $str .= $key . '=' . $rawval;
  }
  return ($str);
}

function phpself_querystr($querystr = null)
{
  $ret = $_SERVER['PHP_SELF'];
  $ret = preg_replace('/\/index.php$/', '/', $ret);
  if (!isset($querystr)) parse_str($_SERVER['QUERY_STRING'], $querystr);
  if (is_array($querystr)) {
    if (count($querystr)) {
      $querystr = query_str($querystr);
      if ($querystr) {
        $ret .= '?' . $querystr;
      }
    }
  } else {
    if ($querystr) {
      $ret .= '?' . $querystr;
    }
  }

  return $ret;
}

function mk_nhsym_array($fname)
{
    $data = file($fname);
    $ret = array();
    foreach ($data as $l) {
	if (preg_match('/^(\S+) (.+)$/', $l, $match)) {
	    $ret[] = array('color'=>$match[1], 'char'=>$match[2]);
	}
    }
    return $ret;
}


$tiledata = array(
		  'tty'=>array('name'=>'TTY',
			       'istile'=>0,
			       'showmap'=>1, # if 0, not shown in glyphshot.php
			       'data'=>mk_nhsym_array('nethack-3.4.3-std.def')),
		  'ttydec'=>array('name'=>'TTY (DECgraphics)',
			       'istile'=>0,
			       'showmap'=>1, # if 0, not shown in glyphshot.php
			       'data'=>mk_nhsym_array('nethack-3.4.3-dec.def')),
		  'ttyibm'=>array('name'=>'TTY (IBMgraphics)',
			       'istile'=>0,
			       'showmap'=>1, # if 0, not shown in glyphshot.php
			       'data'=>mk_nhsym_array('nethack-3.4.3-ibm.def')),
		  'vanilla16'=>array('name'=>'Default 16x16 tiles',
				     'comment'=>'The default 16x16 tiles that come with NetHack',
				     'istile'=>1,
				     'showmap'=>1,
				     'data'=>'tileset/nethack-3.4.3-16x16.png',
				     'datawid'=>40, # tiles per line in datafile
				     'width'=>16, 'height'=>16),
		  'vanilla32'=>array('name'=>'Default 32x32 tiles',
				     'comment'=>'The default 32x32 tiles that come with NetHack',
				     'istile'=>1,
				     'showmap'=>1,
				     'data'=>'tileset/nethack-3.4.3-32x32.png',
				     'datawid'=>40, # tiles per line in datafile
				     'width'=>32, 'height'=>32),
		  'abigaba'=>array('name'=>'Abigaba tiles',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/abigaba_nethack.png',
				   'datawid'=>40,
				   'width'=>32, 'height'=>32),
		  'itakura'=>array('name'=>'Itakura tiles',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/itakura_nethack.png',
				   'datawid'=>30, # tiles per line in datafile
				   'width'=>32, 'height'=>32),
		  'lagged'=>array('name'=>'Lagged tiles',
				  'istile'=>1,
				  'showmap'=>1,
				  'data'=>'tileset/lagged_nethack.png',
				  'datawid'=>40,
				  'width'=>12, 'height'=>20),
		  'smartphone'=>array('name'=>'Smartphone tiles',
				      'istile'=>1,
				      'showmap'=>1,
				      'data'=>'tileset/smartphone_nethack.png',
				      'datawid'=>40,
				      'width'=>6, 'height'=>6),
		  'vulturesmap'=>array('name'=>'Vulture\'s eye map tiles',
				       'istile'=>1,
				       'showmap'=>1,
				       'data'=>'tileset/vulturesmap_nethack.png',
				       'datawid'=>40,
				       'width'=>7, 'height'=>14),
		  'absurd'=>array('name'=>'Absurd tiles, scaled',
				  'istile'=>1,
				  'showmap'=>1,
				  'data'=>'tileset/absurd32_nethack.png',
				  'datawid'=>40,
				  'width'=>32, 'height'=>32),
		  'geoduck'=>array('name'=>'Geoduck tiles v2.1, 20x10',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/geoduck_nethack.png',
				   'datawid'=>40,
				   'width'=>10, 'height'=>20),
		  'geoduck3'=>array('name'=>'Geoduck tiles v3.0, 20x12',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/geoduck_nethack_20x12.png',
				   'datawid'=>40,
				   'width'=>12, 'height'=>20),
		  'geoduck4'=>array('name'=>'Geoduck tiles v4.0, 20x12',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/geoduck_nethack_20x12_v4.png',
				   'datawid'=>40,
				   'width'=>12, 'height'=>20),
		  'geoduck41'=>array('name'=>'Geoduck tiles v4.1, 20x12',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/geoduck_nethack_20x12_v41.png',
				   'datawid'=>40,
				   'width'=>12, 'height'=>20),
		  'geoduck42'=>array('name'=>'Geoduck tiles v4.2, 20x12',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/geoduck_nethack_20x12_v42.png',
				   'datawid'=>40,
				   'width'=>12, 'height'=>20),
		  'aoki'=>array('name'=>'Aoki tiles',
				   'istile'=>1,
				   'showmap'=>0,
				   'data'=>'tileset/aoki_nethack.png',
				   'datawid'=>40,
				   'width'=>48, 'height'=>64),
		  'aoki2k5'=>array('name'=>'Aoki 2k5 tiles',
				   'istile'=>1,
				   'showmap'=>0,
				   'data'=>'tileset/aoki-2k5_nethack.png',
				   'datawid'=>40,
				   'width'=>48, 'height'=>64),
		  'NeXTSTEP'=>array('name'=>'NeXTSTEP tiles v1.0',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/nh343-nextstep.png',
				   'datawid'=>40,
				   'width'=>10, 'height'=>10),
		  'kins'=>array('name'=>'Kins tiles',
				   'istile'=>1,
				   'showmap'=>1,
				   'data'=>'tileset/kins32-nh343.png',
				   'datawid'=>40,
				   'width'=>32, 'height'=>32),
);


# maps available in glyphshow.php
$dumpfiles = array(
		   'glyph_dump_1176516037.txt'=>'Dungeons of Doom',
		   'glyph_dump_1176522356.txt'=>'Oracle',
		   'glyph_dump_1176522358.txt'=>'Sokoban',
		   'glyph_dump_1176522360.txt'=>'Minetown',
		   'glyph_dump_1176522361.txt'=>'Medusa',
		   'glyph_dump_1176522362.txt'=>'Gehennom',
		   'glyph_dump_1176573556.txt'=>'Ranger quest',
		   'glyph_dump_1176573557.txt'=>'Bigroom',
		   'glyph_dump_1176653715.txt'=>'Valkyrie Quest',
		   'glyph_dump_1176653718.txt'=>'Valkyrie Quest 2',
		   'glyph_dump_walltest.txt'  =>'Wall Test',
		   'glyph_dump_monobjtest.txt'=>'Monster/Object Test',
);

?>