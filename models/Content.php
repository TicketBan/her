<?php
namespace app\models;

use yii\db\ActiveRecord;

class Content extends ActiveRecord
{
    /*
     * statusCode
     * 100 - error created
     * 101 - error updated
     * 102 - data is empty
     *
     * 200 - limit is over
     *
     * PUBLISH - 1
     * MODER - 0
     * REJECT - 2
     */

    public static function generateUrl ($str)
    {
        $converter = array(
            'a' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',  'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'a',   'Б' => 'b',   'В' => 'v',
            'Г' => 'g',   'Д' => 'd',   'Е' => 'e',
            'Ё' => 'e',   'Ж' => 'zh',  'З' => 'z',
            'И' => 'i',   'Й' => 'y',   'К' => 'k',
            'Л' => 'l',   'М' => 'm',   'Н' => 'n',
            'О' => 'o',   'П' => 'p',   'Р' => 'r',
            'С' => 's',   'Т' => 't',   'У' => 'u',
            'Ф' => 'f',   'Х' => 'h',   'Ц' => 'c',
            'Ч' => 'ch',  'Ш' => 'sh',  'Щ' => 'sch',
            'Ь' => '',  'Ы' => 'y',   'Ъ' => '',
            'Э' => 'e',   'Ю' => 'yu',  'Я' => 'ya',
        );

        $str = strtolower($str);
        $str = strtr($str, $converter);
        $str = preg_replace('/[\s]+/isu', '-', $str);
        $str = trim($str, "-");
        return $str;
    }

    public static function read ()
    {
        //$client_id = \Yii::$app->session->get('user_id');
        $data = Contnet::find()->orderBy(['id' => SORT_DESC])->asArray()->all();
        return json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    public static function create ($data)
    {
        if(!empty($data))
        {
            foreach ($data as $key => $value)
            {
                $arr[$key] = urldecode($value);
            }

            $data = $arr;
            $ip = $_SERVER['REMOTE_ADDR'];
            $uid = trim($data['uid']);//mb_strlen(

            $check_limits = Content::find()->where(['uid_cookie' => $uid])->orderBy(['created_at' => SORT_DESC])->asArray()->all();
            $limit_count = json_decode(Setting::getLimit(), true);
            $limit_param = $limit_count['limit'];

            if (sizeof($check_limits) > $limit_param) {
                $day = abs($check_limits[0]['created_at'] - $check_limits[$limit_param]['created_at']);

                $ip_status = 0;
                $last_ip = $check_limits[0]['ip'];
                if ($last_ip == $check_limits[1]['ip'] or $last_ip == $check_limits[2]['ip'])
                    $ip_status = 1;

                if ($day < 86400 and $ip_status == 1)
                    return json_encode(array('message' => 'fail', 'error' => 'Превышен лимит на количество статей в сутки', 'statusCode' => '200', 'publish' > '4'), JSON_UNESCAPED_UNICODE);
            }

            $post_size_limit = $limit_count['post_size'];
            $data_size = (float) mb_strlen(implode(' ', $data), '8bit') / 1024 / 1024;

            if ($post_size_limit < $data_size)
                return json_encode(array('message' => 'fail', 'error' => 'Слишком большой размер статьи', 'statusCode' => '200', 'publish' > '5'), JSON_UNESCAPED_UNICODE);

            $time = time();
            $url = strtolower(self::generateUrl($data['title']).'-'.date('D-m-Y', $time));

            if (self::getCountContentByUrl($url) > 0)
                $url = $url."-".hash('crc32', rand(1, 99999999));

            /** META IMAGE COVER */
            $image_count = preg_match_all('/<img[\s]*src="(.*?)"/isu', $data['text'], $image);
            $image = @$image[1][0];

            $content = new Content();
            $content->id_hash = hash('crc32', md5($url).$time);
            $content->image = $image;
            $content->title = $data['title'];
            $content->text = $data['text'];
            $content->uid_cookie = $uid;
            $content->url = $url;
            $content->author = $data['author'];
            $content->publish = 0;
            $content->ip = $ip;
            $content->created_at = $time;
            $content->updated_at = $time;

            if ($content->insert()) {
                return json_encode(array('message' => 'ok', 'url' => $content->url, 'page_id' => $content->id_hash), JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(array('message' => 'fail', 'error' => 'error created', 'statusCode' => '100'), JSON_UNESCAPED_UNICODE);
            }

        } else {
            return json_encode(array('message' => 'fail', 'error' => 'data is empty', 'statusCode' => '102'), JSON_UNESCAPED_UNICODE);
        }
    }

    public static function updateContentByUrl ($data)
    {
        if(!empty($data))
        {
            foreach ($data as $key => $value)
            {
                $arr[$key] = urldecode($value);
            }

            $data = $arr;

            $time = time();

            $content = Content::findOne(['id_hash' => $data['page_id']]);

            if (Content::find()->where(['id_hash' => $data['page_id']])->andWhere(['uid_cookie' => $data['uid']])->count() > 0) {
                $content->title = $data['title'];
                $content->text = $data['text'];
                $content->author = $data['author'];
                $content->publish = 0;
                $content->updated_at = $time;

                if ($content->update()) {
                    return json_encode(array('message' => 'ok', 'id' => $content->id), JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode(array('message' => 'fail', 'error' => 'error updated', 'statusCode' => 101), JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(array('message' => 'fail', 'error' => 'post not found', 'statusCode' => 104), JSON_UNESCAPED_UNICODE);
            }

        } else {
            return json_encode(array('message' => 'fail', 'error' => 'data is empty', 'statusCode' => 102), JSON_UNESCAPED_UNICODE);
        }
    }

    public static function updatePublishStatusById ($data)
    {
        $id = intval($data['id']);
        $publish = intval($data['publish']);

        $content = Content::findOne(['id' => $id]);
        $content->publish = $publish;
        return json_encode($content, JSON_UNESCAPED_UNICODE);

    }

    public static function setPublish ($id)
    {
        $id = intval($id);

        $content = Content::findOne(['id' => $id]);
        $content->publish = 1;
        $content->update();
        return json_encode(['message' => 'ok'], JSON_UNESCAPED_UNICODE);
    }

    public static function setReject ($id)
    {
        $id = intval($id);

        $content = Content::findOne(['id' => $id]);
        $content->publish = 2;
        $content->update();
        return json_encode(['message' => 'ok'], JSON_UNESCAPED_UNICODE);
    }

    public static function setModer ($id)
    {
        $id = intval($id);

        $content = Content::findOne(['id' => $id]);
        $content->publish = 0;
        $content->update();
        return json_encode(['message' => 'ok'], JSON_UNESCAPED_UNICODE);
    }

    public static function checkPublish ($ids)
    {
        $ids = json_decode($ids, true);

        foreach ($ids as $k => $id) {
            $content = Content::findOne(['id' => $id]);
            $content->publish = 1;
            $content->update();
        }
        return json_encode(['message' => 'ok'], JSON_UNESCAPED_UNICODE);
    }

    public static function checkModer ($ids)
    {
        $ids = json_decode($ids, true);

        foreach ($ids as $k => $id) {
            $content = Content::findOne(['id' => $id]);
            $content->publish = 0;
            $content->update();
        }
        return json_encode(['message' => 'ok'], JSON_UNESCAPED_UNICODE);
    }

    public static function checkReject ($ids)
    {
        $ids = json_decode($ids, true);

        foreach ($ids as $k => $id) {
            $content = Content::findOne(['id' => $id]);
            $content->publish = 2;
            $content->update();
        }
        return json_encode(['message' => 'ok'], JSON_UNESCAPED_UNICODE);
    }

    public static function checkDelete ($ids)
    {
        $ids = json_decode($ids, true);

        foreach ($ids as $k => $id) {
            $stmt = Content::find()->where(['id' => $id])->one();
            $stmt->delete();
        }

        return json_encode(['message' => 'ok']);
    }

    public static function checkSession ($uid, $page_id)
    {
        $uid = trim($uid);
        $page_id = trim($page_id);

        $check = Content::find()->where(['uid_cookie' => $uid])->andWhere(['id_hash' => $page_id])->count();

        if ($check)
            return json_encode(['checked' => 'true']);
        else
            return json_encode(['checked' => 'false']);

    }

    public static function getContentById ($id)
    {
        $id = intval($id);
        $data = Content::find()->where(['id' => $id])->asArray()->one();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function getContentByUrl ($url)
    {
        $data = Content::find()->where(['url' => $url])->asArray()->one();

        if (!empty($data)) {
            $arr = [];
            $arr['image'] = $data['image'];
            $arr['title'] = $data['title'];
            $arr['text'] = $data['text'];
            $arr['author'] = $data['author'];
            $arr['url'] = $data['url'];
            $arr['page_id'] = $data['id_hash'];
            $arr['time'] = $data['updated_at'];
            $arr['publish'] = $data['publish'];
            $arr['page_csrf'] = hash('sha256', $data['uid_cookie']);

            return json_encode($arr, JSON_UNESCAPED_UNICODE);
        } else
            return json_encode(['message' => '404'], JSON_UNESCAPED_UNICODE);
    }

    public static function getCountContentByUrl ($url)
    {
        $count = Content::find()->where(['url' => $url])->count();
        return $count;
    }

    public static function getStatsJson ()
    {
        $publish = Content::find()->where(['publish' => 1])->count();
        $moder = Content::find()->where(['publish' => 0])->count();
        $reject = Content::find()->where(['publish' => 2])->count();

        return json_encode(['publish' => $publish, 'moder' => $moder, 'reject' => $reject], JSON_UNESCAPED_UNICODE);
    }


    public static function getPublish ()
    {
        $data = Content::find()->where(['publish' => 1])->orderBy(['id' => SORT_DESC])->asArray()->all();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function getModer ()
    {
        $data = Content::find()->where(['publish' => 0])->orderBy(['id' => SORT_DESC])->asArray()->all();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function getReject ()
    {
        $data = Content::find()->where(['publish' => 2])->orderBy(['id' => SORT_DESC])->asArray()->all();
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public static function search ($data)
    {
        $data = trim($data);
        $data = Content::find()->where(['like', 'title', $data])->orWhere(['like', 'text', $data])->asArray()->all();
        return json_encode($data, JSON_UNESCAPED_UNICODE);

    }

    public static function deleteContentById ($id)
    {
        if (!empty($id)) {
            $stmt = Content::find()->where(['id' => $id])->one();
            if ($stmt->delete()) {
                return json_encode(['message' => 'ok']);
            }
        }
    }

    /* COUNT */
    public static function getStats () {
        $data = [];
        $data['publish_count'] = Content::find()->where(['publish' => 1])->count();
        $data['moder_count'] = Content::find()->where(['publish' => 0])->count();
        $data['reject_count'] = Content::find()->where(['publish' => 2])->count();

        return $data;
    }
}
