<?php

  include_once "../../functions.php";

  date_default_timezone_set($_POST['timezone']); 
  $timezone = date_default_timezone_get();
  echo $timezone;