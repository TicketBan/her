<?php
namespace app\controllers\users;

use app\controllers\AppController;

class RegistrationController extends AppController {

    public $layout = 'registration';

    public function actionIndex() {
        return $this->render('/layouts/registration');
    }
}

?>