<?php 

  $whatTheUserRequested = $_SERVER['REQUEST_URI'];
  $firstSplit = explode('microblogger/', $whatTheUserRequested);
<<<<<<< HEAD
  $parameterArray = explode('/', $firstSplit[1]);

//  echo $whatTheUserRequested;
//  print_r($parameterArray);
=======
  $paths = explode('/', $firstSplit[1]);

//  echo $whatTheUserRequested;
//  print_r($paths);
>>>>>>> 22c0563214fddd1b0cc339779620ce097b8999d2
