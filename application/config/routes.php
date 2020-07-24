<?php

  return [
    '' => [
      'controller' => 'main',
      'action' => 'index',
    ],
    'login' => [
      'controller' => 'account',
      'action' => 'login',
    ],
    'signup' => [
      'controller' => 'account',
      'action' => 'signup',
    ],
    'weather' => [
      'controller' => 'main',
      'action' => 'weather',
    ],
    'post' => [
      'controller' => 'main',
      'action' => 'post',
    ],
    'post/{page:\d+}' => [
      'controller' => 'main',
      'action' => 'post',
    ],
    'add' => [
      'controller' => 'main',
      'action' => 'add',
    ],
  ];

 ?>
