<?php

namespace application\controllers;

use application\core\Controller;

class MainController extends Controller {

  public function indexAction() {
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
			$this->view->location('/post');
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
    $this->view->render('Post');
  }


}


 ?>
