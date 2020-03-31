<?php 

  $whatTheUserRequested = $_SERVER['REQUEST_URI'];
  $firstSplit = explode('microblogger/', $whatTheUserRequested);
  $paths = explode('/', $firstSplit[1]);

//  echo $whatTheUserRequested;
//  print_r($paths);
