<?php

namespace application\models;

use application\core\Model;

class Main extends Model {

  public $error;

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

  public function dataWheather() {
    $html = file_get_html('http://www.gismeteo.ua/city/daily/5093/');
    return $html->find('div[class="widget__container"]', 0);
  }

}

 ?>
