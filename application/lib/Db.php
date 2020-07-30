<?php

namespace application\lib;

use PDO;

class Db {

  private $db;
  private static $instance;

  private function __construct()
  {
    $config = require 'application/config/db.php';
    $this->db = new PDO('mysql:host=' .
    $config['host'] .
    ';dbname=' . $config['name'] . '',
    $config['user'],
    $config['password']
    );
  }

  private function __clone () {}
	private function __wakeup () {}

	public static function getInstance() {
		if (self::$instance === null) {
			self::$instance = new self();
		}
		return self::$instance;
	}

  public function query($sql, $params = []) {
		$stmt = $this->db->prepare($sql);
		if (!empty($params)) {
			foreach ($params as $key => $val) {
				if (is_int($val)) {
					$type = PDO::PARAM_INT;
				} else {
					$type = PDO::PARAM_STR;
				}
				$stmt->bindValue(':'.$key, $val, $type);
			}
		}
		$stmt->execute();
		return $stmt;
  }

  public function row($sql, $params = []) {
    $result = $this->query($sql, $params);
    return $result->fetchAll(PDO::FETCH_ASSOC);
  }

  public function column($sql, $params = []) {
    $result = $this->query($sql, $params);
    return $result->fetchColumn();
  }

}


 ?>
