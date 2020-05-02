<?php
namespace app\controllers\client;

use app\controllers\AppController;
use app\models\Content;

class PostController extends AppController
{
    public $layout = 'main';

    public function actionIndex ($url = null)
    {
        if (!empty($url))
            $data = json_decode(Content::getContentByUrl($url), true);

        if (!empty (@$_COOKIE['uid']))
            $uid = hash('sha256', @$_COOKIE['uid']);
        else
            $uid = 0;

        if (!empty($data) and (@$data['publish'] == 1)) {
            \Yii::$app->view->params['meta_title'] = $data['title'];
            \Yii::$app->view->params['meta_author'] = $data['author'];
            \Yii::$app->view->params['meta_text'] = $data['text'];
            \Yii::$app->view->params['meta_image'] = $data['image'];

            return $this->render('/content/post',
                [
                    'title' => $data['title'],
                    'author' => $data['author'],
                    'text' => $data['text'],
                    'url' => $data['url'],
                    'page_id' => $data['page_id'],
                    'time' => $data['time'],
                ]
            );
        } else if ($url == '') {
            return $this->render('/content/post');
        } else if (@$data['publish'] == 0) {
            if (@$data['page_csrf'] != $uid) {
                return $this->render('/content/moder');
            } else {
                \Yii::$app->view->params['meta_title'] = $data['title'];
                \Yii::$app->view->params['meta_author'] = $data['author'];
                \Yii::$app->view->params['meta_text'] = $data['text'];
                \Yii::$app->view->params['meta_image'] = $data['image'];

                return $this->render('/content/post',
                    [
                        'title' => $data['title'],
                        'author' => $data['author'],
                        'text' => $data['text'],
                        'url' => $data['url'],
                        'page_id' => $data['page_id'],
                        'time' => $data['time'],
                    ]
                );
            }
        } else if ($data['publish'] == 2) {
            if ($data['page_csrf'] != $uid) {
                return $this->render('/content/reject');
            } else {
                \Yii::$app->view->params['meta_title'] = $data['title'];
                \Yii::$app->view->params['meta_author'] = $data['author'];
                \Yii::$app->view->params['meta_text'] = $data['text'];
                \Yii::$app->view->params['meta_image'] = $data['image'];

                return $this->render('/content/post',
                    [
                        'title' => $data['title'],
                        'author' => $data['author'],
                        'text' => $data['text'],
                        'url' => $data['url'],
                        'page_id' => $data['page_id'],
                        'time' => $data['time'],
                    ]
                );
            }
        } else if ($data['publish'] == 4) {
            return $this->render('/content/limit');
        } else if ($data['publish'] == 5) {
            return $this->render('/content/size');
        } else {
            return $this->render('/content/404');
        }
    }

    public function actionCreate ()
    {
        $data = \Yii::$app->request->post('data');
        return Content::create($data);
    }

    public function actionGetContentByUrl ()
    {
        $id = \Yii::$app->request->post('id');

        return Content::getContentById($id);
    }

    public function actionUpdate ()
    {
        $data = \Yii::$app->request->post('data');
        return Elementshouse::updateContentById($data);
    }

    public function actionSearch ()
    {
        $data = \Yii::$app->request->post('data');
        return Content::search($data);
    }

    public function actionDelete ()
    {
        $id = \Yii::$app->request->post('id');
        return Content::deleteContentById($id);
    }

}