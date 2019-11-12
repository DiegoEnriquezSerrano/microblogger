<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <!-- Required meta tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="main.css">
    <title>oh yeah?!</title>
  </head>
<body>

  <nav class="navbar">
    <a class="navbar-brand" href="/">oh yeah?</a>
    <button class="navbar-toggler" type="button" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-icon"></span>
    </button>

    <div id="navbarSupportedContent">
      <form id="searchForm">
        <input type="hidden" name="page" value="search">
        <input type="text" name="q" id="search" placeholder="Search posts">
        <button type="submit" id="searchButton"></button>
      </form>
    </div><!--'#navbarSupportedContent'-->
    <div id="loginModalButtonHolder">
    
    <?php if (isset($_SESSION['id'])) { ?>
      <a class="btn btn-outline-success my-2 my-sm-0" href="?function=logout">Logout</a>
    <?php } else { ?>
      <button id="loginModalButton" class="loginModalButtons">Login/Sign&nbsp;Up</button>
    <?php } ?>
    </div><!--#loginModalButtonHolder-->
  </nav><!--'.navbar'-->