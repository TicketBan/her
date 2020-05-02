<?php
namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord {


    public static function registration ($data)
    {
        foreach ($data as $value)
        {
            $arr[$value['name']] = $value['value'];
        }

        $data = $arr;

        if (!empty($data['login']) && !empty($data['password'])) {
            $date_at = time();

            if (!User::find()->where(['login' => $data['login']])->count()) {

                $client = new User();
                $client->login = $data['login'];
                $client->password = \Yii::$app->getSecurity()->generatePasswordHash($data['password']);
                $client->created_at = $date_at;
                $client->updated_at = $date_at;
                $client->access = 1;

                if ($client->insert()) {
                    return json_encode(array('message' => 'ok'));
                } else {
                    return json_encode(array('message' => 'fail', 'error' => 'Ошибка при регистрации.'), JSON_UNESCAPED_UNICODE);
                }
            } else {
                return json_encode(array('message' => 'fail', 'error' => 'Пользовтель с таким ИНН уже зарегистрирован.'), JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode(array('message' => 'fail', 'error' => 'Необходимо заполнить все поля.'), JSON_UNESCAPED_UNICODE);
        }

    }

    public static function login ($data)
    {
        foreach ($data as $value)
        {
            $arr[$value['name']] = $value['value'];
        }

        $data = $arr;

        if (!empty($data['login']) && !empty($data['password'])) {

                if (User::find()->where(['login' => $data['login']])->count()) {
                    $hash_db = User::find()->asArray()->where(['login' => $data['login']])->all();
                    if (\Yii::$app->getSecurity()->validatePassword($data['password'], $hash_db[0]['password'])) {
                        \Yii::$app->session->set('user_name', $data['login']);
                        \Yii::$app->session->set('user_id', $hash_db[0]['id']);
                        \Yii::$app->session->set('is_access', '1');
                        return json_encode(array('message' => 'ok'));
                    } else {
                        return json_encode(array('message' => 'fail', 'error' => 'Не правильный логин или пароль.'), JSON_UNESCAPED_UNICODE);
                    }
                } else {
                    return json_encode(array('message' => 'fail', 'error' => 'Такой пользователь не найден.'), JSON_UNESCAPED_UNICODE);
                }

        } else {
            return json_encode(array('message' => 'fail', 'error' => 'Необходимо заполнить все поля.'), JSON_UNESCAPED_UNICODE);
        }

    }

    public static function isAdmin ()
    {
        if ((\Yii::$app->session->get('is_access') == 1) && !empty(\Yii::$app->session->get('user_name')))
            return true;
    }

    public static function reset ($data)
    {
        if (sizeof($data)) {
            foreach ($data as $value) {
                $arr[$value['name']] = $value['value'];
            }

            $data = $arr;
            $client = User::findOne(['id' => \Yii::$app->session->get('user_id')]);
            $client->password = \Yii::$app->getSecurity()->generatePasswordHash($data['password']);

            if ($client->update()) {
                return json_encode(array('message' => 'ok'), JSON_UNESCAPED_UNICODE);
            } else {
                return json_encode(array('message' => 'ok', 'error' => 'Ошибка при обновлении пароля.'), JSON_UNESCAPED_UNICODE);
            }
        } else {
            return json_encode(array('message' => 'fail', 'error' => 'Заполните пароль.'), JSON_UNESCAPED_UNICODE);
        }
    }
}

?>