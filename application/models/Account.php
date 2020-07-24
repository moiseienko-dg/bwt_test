<?php

namespace application\models;

use application\core\Model;

class Account extends Model {

  public $error;

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

  public function checkData($email, $password) {
		$params = [
			'email' => $email,
		];
		$hash = $this->db->column('SELECT password FROM accounts WHERE email = :email', $params);
		if (!$hash or !password_verify($password, $hash)) {
			return false;
		}
		return true;
	}

  public function checkEmailExists($email) {
  $params = [
    'email' => $email,
  ];
  if ($this->db->column('SELECT id FROM accounts WHERE email = :email', $params)) {
    $this->error = 'This email is already in use';
    return false;
  }
  return true;
  }

  public function login($email) {
		$params = [
			'email' => $email,
		];
		$data = $this->db->row('SELECT * FROM accounts WHERE email = :email', $params);
		$_SESSION['authorize'] = $data[0];
	}

  public function register($post) {
    $params = [
      'first_name' => $post['first-name'],
      'last_name' => $post['last-name'],
      'email' => $post['email'],
      'sex' => $post['sex'],
      'birthday' => $post['birthday'],
      'password' => password_hash($post['password'], PASSWORD_BCRYPT),
    ];
    foreach ($params as $key => $val) {
      if (empty($val)) {
        $params[$key] = Null;
      }
    }
    $this->db->query("INSERT INTO accounts VALUES
      (DEFAULT, :first_name, :last_name, :email, :sex, :birthday, :password)", $params);
  }

}

 ?>
