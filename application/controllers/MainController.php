<?php

namespace application\controllers;

use application\core\Controller;

class MainController extends Controller {

  public function indexAction() {
    $this->view->render('Main');
  }

  public function loginAction() {
    $this->view->layout = 'login';
    $this->view->render('Login');
  }

  public function signupAction() {
    $this->view->layout = 'login';
    if (!empty($_POST)) {
      if (!$this->model->validate(['first-name', 'last-name', 'email', 'password'], $_POST)) {
        $this->view->message('error', $this->model->error);
      }
    }
    $this->view->render('SignUp');
  }

  public function postAction() {
    $this->view->render('Post');
  }


}


 ?>
