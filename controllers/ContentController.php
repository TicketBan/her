<?php
namespace app\controllers;

use app\models\Content;

class ContentController extends AppController
{
    public $layout = 'panel';
    public function actionIndex ()
    {
        $data = Content::getStats();

        return $this->render('/content/content', array(
            'publish_count' => $data['publish_count'],
            'moder_count' => $data['moder_count'],
            'reject_count' => $data['reject_count']
        ));
    }

    public function actionCreate ()
    {
        $data = \Yii::$app->request->post();
        return Content::create($data);
    }

    public function actionRead ()
    {
        return Content::read();
    }

    public function actionUpdate ()
    {
        $data = \Yii::$app->request->post();
        return Content::updateContentByUrl($data);
    }

    public function actionGetPublish ()
    {
        return Content::getPublish();
    }

    public function actionGetModer ()
    {
        return Content::getModer();
    }

    public function actionGetReject ()
    {
        return Content::getReject();
    }

    public function actionGetStats ()
    {
        return Content::getStatsJson();
    }

    public function actionGetContentById ()
    {
        $id = \Yii::$app->request->post('id');
        return Content::getContentById($id);
    }

    public function actionGetContentByUrl ($url)
    {
        return Content::getContentByUrl($url);
    }

    /*set Publish */
    public function actionSetPublish ()
    {
        $id = \Yii::$app->request->post('id');
        return Content::setPublish($id);
    }

    /*set Moder*/
    public function actionSetModer ()
    {
        $id = \Yii::$app->request->post('id');
        return Content::setModer($id);
    }

    /*set Reject*/
    public function actionSetReject ()
    {
        $id = \Yii::$app->request->post('id');
        return Content::setReject($id);
    }

    public function actionCheckPublish ()
    {
        $id = \Yii::$app->request->post('ids');
        return Content::checkPublish($id);
    }


    public function actionCheckModer ()
    {
        $id = \Yii::$app->request->post('ids');
        return Content::checkModer($id);
    }

    public function actionCheckReject ()
    {
        $id = \Yii::$app->request->post('ids');
        return Content::checkReject($id);
    }

    public function actionCheckDelete ()
    {
        $id = \Yii::$app->request->post('ids');
        return Content::checkDelete($id);
    }

    public function actionCheckSession ()
    {
        $uid = \Yii::$app->request->post('uid');
        $page_id = \Yii::$app->request->post('page_id');

        return Content::checkSession($uid, $page_id);
    }

    public function actionGetSession ()
    {
        $uid = time().":".(time() / 25);
        $uid = hash('md5', $uid);
        return json_encode(['uid' => $uid]);
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

    public function actionGetInstagramContent () {
        $url = \Yii::$app->request->get('url');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
        $data = curl_exec($ch);
        curl_close($ch);

        $image_count = preg_match_all('/<meta[\s]*property="og:image"[\s]*content="(.*?)"/isu', $data, $image);
        $result['image'] = @$image[1][0] ? @$image[1][0] : null;

        $video_count = preg_match_all('/<meta[\s]*property="og:video"[\s]*content="(.*?)"/isu', $data, $video);
        $result['video'] = @$video[1][0] ? @$video[1][0] : null;

        return json_encode($result);
    }

}