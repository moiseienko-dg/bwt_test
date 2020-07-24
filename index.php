<?php

  require 'application/lib/dev.php';
  require 'application/lib/simple_html_dom.php';

  use application\core\Router;

  spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class . '.php');
    if (file_exists($path)) {
      require $path;
    }
  });

  session_start();
  $router = new Router;
  $router->run()
 ?>
