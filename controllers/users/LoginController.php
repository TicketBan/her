<?php
namespace app\controllers\users;

use app\controllers\AppController;

class LoginController extends AppController {

    public $layout = 'login';

    public function actionIndex() {
        return $this->render('/layouts/login');
    }
}

?>