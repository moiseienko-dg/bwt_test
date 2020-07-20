<?php

namespace application\controllers;

use application\core\Controller;

class MainController extends Controller {

  public function loginAction() {
    $this->view->layout = 'login';
    $this->view->render('Login');
  }

  public function signupAction() {
    $this->view->layout = 'login';
    $this->view->render('SignUp');
  }

  public function postAction() {
    $this->view->render('Post');
  }


}


 ?>
