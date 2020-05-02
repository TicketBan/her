<?php
namespace app\controllers;

use app\models\User;

class UserController extends AppController
{
    public function actionIndex ()
    {
        if(empty(\Yii::$app->session->get('user_name')) && !\Yii::$app->session->get('is_client'))
            return \Yii::$app->getResponse()->redirect('/login');

        $client = User::getList();
        return $this->render('client', array('pagename' => 'Пользователи', 'client' => $client));
    }

    public function actionAccess ()
    {
        $data = json_decode(\Yii::$app->request->post('data'), true);
        return User::saveAccess($data);
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionRegistration ()
    {
        $data = json_decode(\Yii::$app->request->post('data'), true);
        return User::registration($data);
    }

    public function actionLogin ()
    {
        $data = json_decode(\Yii::$app->request->post('data'), true);
        return User::login($data);
    }

    public function actionReset ()
    {
        $data = json_decode(\Yii::$app->request->post('data'), true);
        return User::reset($data);
    }

    public function actionResetEmail ()
    {
        $data = json_decode(\Yii::$app->request->post('data'), true);
        return User::resetEmail($data);
    }

    public function actionExit ()
    {
        \Yii::$app->session->set('user_name', '');
        \Yii::$app->session->set('user_id', '');
        return \Yii::$app->getResponse()->redirect('/login');
    }
}