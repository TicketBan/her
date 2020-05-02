<?php
namespace app\controllers;

use app\controllers\AppController;

class FileController extends AppController
{
    public function actionUpload()
    {
        $fileName = 'files[0]';
        $uploadPath = \Yii::getAlias('@app/public_html/images');

        if (!empty($_FILES)) {
            $file = \yii\web\UploadedFile::getInstanceByName($fileName);

            //Print file data
            $time = time();
            $name = $time . rand(1, 99999) . "." . $file->getExtension();

            if ($file->saveAs($uploadPath . '/' . $name)) {
                //Now save file data to database
                $images = ['files' => [['url' => 'images/'.$name]]];

                return json_encode($images, JSON_UNESCAPED_UNICODE);
            }
        }

        return false;
    }

    public function actionDelete()
    {
        $file = \Yii::$app->request->post('file');
        $uploadPath = \Yii::getAlias('@app/public_html/images');

        if (unlink($uploadPath.'/'.$file))
            return json_encode(['message' => 'ok']);
        else
            return json_encode(['message' => 'fail']);
    }
}