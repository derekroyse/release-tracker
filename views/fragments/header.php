<!DOCTYPE html>
<html lang="en-US">

<head>
  <title>Release Tracker</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="stylesheet" href="/css/bootstrap.min.css">
  <link rel="stylesheet" href="/css/custom.css" />

  <script src="/js/vendor/jquery-3.3.1.min.js"></script>
  <script src="/js/vendor/popper.min.js"></script>
  <script src="/js/vendor/bootstrap.min.js"></script>
  <script src="/js/main.js"></script>   
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
          <a class="nav-link" href="#">Add New Titles</a>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="/?go=test:phptest">DB Test</a>
        </li>                    
      </ul>
      <div>
        <?php
          if ($logged_in){
            echo 'Logged in as USERNAME. <a href="?go=logout">(Logout)</a>';
          } else {
            echo '<a href="/views/register">Register</a> or <a href="/views/login">Login</a>';
          }
        ?>       
      </div>
    </div>
  </nav>
  <main id="main-container" class="container" role="main">