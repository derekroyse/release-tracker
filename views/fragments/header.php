<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Release Tracker</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" type="text/css" href="/css/bootstrap/bootstrap.min.css">
  
  <link rel="stylesheet" type="text/css" href="/css/datatables/dataTables.bootstrap4.css"/>
  <link rel="stylesheet" type="text/css" href="/css/datatables/fixedHeader.bootstrap4.min.css"/>
  <link rel="stylesheet" type="text/css" href="/css/datatables/responsive.bootstrap4.min.css"/>
  <link rel="stylesheet" type="text/css" href="/css/custom.css" />

  <script type="text/javascript" src="/js/jquery-3.3.1.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap/popper.min.js"></script>
  <script type="text/javascript" src="/js/bootstrap/bootstrap.min.js"></script>  
  <script type="text/javascript" src="/js/datatables/jquery.dataTables.js"></script>
  <script type="text/javascript" src="/js/datatables/dataTables.bootstrap4.js"></script>
  <script type="text/javascript" src="/js/datatables/dataTables.fixedHeader.min.js"></script>
  <script type="text/javascript" src="/js/datatables/dataTables.responsive.min.js"></script>
  <script type="text/javascript" src="/js/datatables/responsive.bootstrap4.min.js"></script>
  <script type="text/javascript" src="/js/main.js"></script>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/">Home</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">My List <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Released</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/?go=list:master"">Add New Titles</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="/?go=test:phptest">DB Test</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/?go=test:apitest">API Test</a>
        </li>                           
      </ul>
      <div>
        <?php
          if ( array_key_exists('logged_in', $_SESSION) && $_SESSION['logged_in']){
            echo 'Logged in as ' . $_SESSION['username'] . '. <a href="?go=account:logout">(Logout)</a>';
          } else {
            echo '<a href="?go=account:register">Register</a> or <a href="?go=account:login">Login</a>';
          }
        ?>       
      </div>
    </div>
  </nav>
  <main id="main-container" class="container" role="main">