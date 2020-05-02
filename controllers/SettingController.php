<?php
namespace app\controllers;

use app\models\Setting;

class SettingController extends AppController
{
    public function actionSetLimit ()
    {
        $data['post_limit'] = \Yii::$app->request->post('limit');
        $data['post_size'] = \Yii::$app->request->post('post_size');

        return Setting::setLimit($data);
    }

    public function actionGetLimit ()
    {
        return Setting::getLimit();
    }
}