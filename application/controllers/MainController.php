<?php

namespace application\controllers;

use application\core\Controller;
use application\lib\Pagination;

class MainController extends Controller {

  public function indexAction() {
    $this->view->layout = 'default';
    $this->view->render('Main');
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
    $this->view->layout = 'weather';
    $vars = [
      'data' => $this->model->dataWheather(),
    ];
    $this->view->render('Weather', $vars);
  }

}


 ?>
