<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class MainController extends Controller {

  public function indexAction() {
    $this->view->layout = 'default';
    $this->view->render('Main');
  }

  public function loginAction() {
    $this->view->layout = 'login';
		if (!empty($_POST)) {
			if (!$this->model->validate(['email', 'password'], $_POST)) {
				$this->view->message('error', $this->model->error);
			}
			elseif (!$this->model->checkData($_POST['email'], $_POST['password'])) {
				$this->view->message('error', 'Email or Login is incorrect');
			}
			$this->model->login($_POST['email']);
			$this->view->location('/weather');
		}
		$this->view->render('Login');
	}

  public function signupAction() {
    $this->view->layout = 'login';
    if (!empty($_POST)) {
      if (!$this->model->validate(['first-name', 'last-name', 'email', 'password'], $_POST)) {
        $this->view->message('error', $this->model->error);
      }
      elseif (!$this->model->checkEmailExists($_POST['email'])) {
        $this->view->message('error', $this->model->error);
      }
      $this->model->register($_POST);
      $this->view->location('/login');
    }
    $this->view->render('SignUp');
  }

  public function postAction() {
    $pagination = new Pagination($this->route, $this->model->postCount());
    $vars = [
      'pagination' => $pagination->get(),
      'list' => $this->model->postsList($this->route),
    ];
    $this->view->render('Post', $vars);
  }

  public function addAction() {
    $this->view->layout = 'add';
    if (!empty($_POST)) {
      if (!$this->model->addValidate($_POST)){
        $this->view->message('error', $this->model->error);
      }
      $this->model->postAdd($_POST);
      $this->view->message('success', 'feedback added');
    }
    $this->view->render('Add');
  }

  public function weatherAction() {
    $vars = [
      // 'temperature' => $this->model->dataWheather()['temperature'],
      'data' => $this->model->dataWheather(),
    ];
    $this->view->render('Weather', $vars);
  }

}


 ?>
