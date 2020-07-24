<?php

namespace application\models;

use application\core\Model;

class Main extends Model {

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

  public function addValidate($post) {
    $textLen = strlen($post['description']);
    $secret_key = '6LeV5rQZAAAAABSZF9RAp7ScOvTmlz5n9Ev7_WLb';
    $response_key = $post['g-recaptcha-response'];
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$response_key";
    $response = file_get_contents($url);
    $response = json_decode($response);
    $params = [
      'email' => $post['email'],
    ];
    $data = $this->db->row('SELECT * FROM accounts WHERE email = :email', $params);
    if (empty($data)) {
      $this->error = 'No such email';
      return false;
    }
    elseif ($post['first-name'] != $_SESSION['authorize']['first_name']) {
      $this->error = 'This is not your name';
      return false;
    }
    elseif ($data[0]['email'] != $_SESSION['authorize']['email']) {
      $this->error = 'This is not your email';
      return false;
    }
    elseif ($textLen < 10 or $textLen > 500) {
      $this->error = 'Text have to be from 10 to 500 symbols';
      return false;
    }
    elseif (!$response->success) {
      $this->error = 'Check captcha';
      return false;
    }
    return true;
  }

  public function postAdd($post) {
		$params = [
			'first_name' => $post['first-name'],
			'description' => $post['description'],
      'email' => $post['email'],
		];
		$this->db->query('INSERT INTO posts VALUES (DEFAULT, :first_name, :description, :email)', $params);
	}

  public function postsList($route) {
		$max = 10;
		$params = [
			'max' => $max,
			'start' => ((($route['page'] ?? 1) - 1) * $max),
		];
		return $this->db->row('SELECT * FROM posts ORDER BY id DESC LIMIT :start, :max', $params);
	}

  public function postCount() {
    return $this->db->column('SELECT COUNT(id) FROM posts');
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

  public function dataWheather() {
    $html = file_get_html('http://www.gismeteo.ua/city/daily/5093/');
    return $html->find('div[class="widget__container"]', 0);
  }

}

 ?>
