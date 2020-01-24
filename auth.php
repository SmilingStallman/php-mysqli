<?php

function request_login(){
  header('WWW-Authenticate: Basic realm="Must login to access. If not registered, register at https://unfoldkyle.com/practice-weeks/week-21-php/register.php');
  header('HTTP/1.0 401 Unauthorized');
}

function auth(){
  //if login cookie set, check cookie for auth confirmation and request login if bad
  if(isset($_COOKIE['login'])){
    $cookie = json_decode($_COOKIE['login'], true);

    if(!check_pass($cookie['user'], $cookie['pass'])){
      //bad cookie...destroy it
      setcookie('login', '', time() - 10000000, '/');

      request_login();
      die("Bad login info. Please reload page and try again.");
    }
  }
  //checks auth details correct after HTTP auth login
  else if(check_pass($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])){
    if(check_pass($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])){
      setcookie('login',
                json_encode(['user' => $_SERVER['PHP_AUTH_USER'], 'pass' => $_SERVER['PHP_AUTH_PW']]),
                time() * 12 * 60 * 60,
                '/');
    }
  }
  //if no cookie or auth login
  else{
    request_login();
    die("Must login to access. If not registered, register at https://unfoldkyle.com/practice-weeks/week-21-php/register.php");
  }
}

?>
