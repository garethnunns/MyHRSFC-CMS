<?php

if(isset($_GET['s']) && !empty($_GET['s'])) {
  $atozcontent = file_get_contents('atozcontent.php');
  $anchorPositions = array();
  $anchorNames = array();

#FIND POSITIONS OF ANCHORS
  $curOffset = 0;
  while(true) {
    $curPosS = strpos($atozcontent, '<a name = "', $curOffset);
    if($curPosS === false) break;
    $curPosF = strpos($atozcontent, '"></a>', $curOffset);

    $anchorPositions[] = $curPosF+6;
    $anchorNames[] = substr($atozcontent, $curPosS+11, $curPosF-$curPosS-11);

    $curOffset = $curPosF+6;
  }


  $occurences = array();

#FIND POSITIONS OF MATCHES
  $curOffset = 0;
  while(true) {
    $curPos = strpos($atozcontent, $_GET['s'], $curOffset);
    if($curPos === false) break;
    $occurences[] = $curPos;

    $curOffset = $curPos+1;
  }

#CONVERT POSITIONS OF MATCHES TO PREVIOUS ANCHOR NAME
  for($i=0;$i<count($occurences);$i++) {
    for($j=count($anchorPositions)-1;$j>=0;$j--) {
      if($occurences[$i] > $anchorPositions[$j]) {
        $occurences[$i] = $anchorNames[$j];
        break;
      }
    }
  }

  $occurences = array_unique($occurences);

  print_r($occurences);



}

?>