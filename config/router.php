<?php 

  $whatTheUserRequested = $_SERVER['REQUEST_URI'];
  $firstSplit = explode('microblogger/', $whatTheUserRequested);
  $parameterArray = explode('/', $firstSplit[1]);

//  echo $whatTheUserRequested;
//  print_r($parameterArray);
