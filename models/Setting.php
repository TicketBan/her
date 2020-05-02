<?php
namespace app\models;

use yii\db\ActiveRecord;

class Setting extends ActiveRecord
{

    public static function setLimit ($data)
    {
        foreach ($data as $k => $v) {
            $v = floatval($v);
            $setting = Setting::findOne(['key' => $k]);
            $setting->value = $v;
            $setting->update();
        }

        return json_encode(['message' => 'ok'], JSON_UNESCAPED_UNICODE);
    }


    public static function getLimit () {
        $limit = Setting::find()->asArray()->all();
        $data = [];

        foreach ($limit as $v) {
            if ($v['key'] == 'post_limit')
                $data['limit'] = $v['value'];

            if ($v['key'] == 'post_size')
                $data['post_size'] = $v['value'];
        }

        return json_encode($data);
    }
}
