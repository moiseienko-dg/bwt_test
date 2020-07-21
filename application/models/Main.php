<?php

namespace application\models;

use application\core\Model;

class Main extends Model {

  public function validate($input, $post) {
    $required_fields = [
      'first-name' => 'First name',
      'last-name' => 'Last name',
      'email' => 'Email',
      'password' => 'Password'
    ];
    $rules = [
      'first-name' => [
        'pattern' => '#^[a-z0-9]{3,15}$#',
        'message' => 'First name consists of latin alphabet from 3 to 15 letters',
      ],
      'last-name' => [
        'pattern' => '#^[a-z0-9]{3,15}$#',
        'message' => 'Last name consists of latin alphabet from 3 to 15 letters',
      ],
      'email' => [
        'pattern' => '#^([a-z0-9_.-]{1,20}+)@([a-z0-9_.-]+)\.([a-z\.]{2,10})$#',
        'message' => 'Email should looks like somename@somemail.com',
      ],
      'password' => [
        'pattern' => '#^[a-z0-9]{10,30}$#',
        'message' => 'Password need at least 10 symbol of latin alphabet or numbers',
      ],
    ];
    foreach ($input as $val) {
      if (empty($post[$val]) and in_array($val, array_keys($required_fields))) {
        $this->error = $required_fields[$val] . ' is required field';
        return false;
      }
      elseif (!isset($post[$val]) or !preg_match($rules[$val]['pattern'], $post[$val])) {
        $this->error = $rules[$val]['message'];
        return false;
      }
    }
    return true;
  }

}


 ?>
