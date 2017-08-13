<?php

namespace app\helpers;
use app\core\admpages\models\Page;
use app\models\User;
use pavlinter\admparams\models\Params;
use Yii;
use yii\db\Query;

/**
 * SiteHelper
 */
class SiteHelper
{
    /**
     * @return int|string
     */
    public static function userOnline()
    {
        $params = Yii::$app->params;

        $data = Yii::$app->cache->get('_onlineData');
        if ($data === false) {
            //default
            $data = [
                'updateTime' => 0, //тип timestamp
                'updateDate' => null, //типа дата Y-m-d
                'onlineCount' => 0,
                'night' => null, //типа дата Y-m-d H:i:s
                'morning' => null, //типа дата Y-m-d H:i:s
                'updateMorningNight' => null, //тип timestamp
            ];
        }

        $query = new Query();
        $online = $query->from(User::tableName())
            ->andWhere('NOW() <= online')->count();

        $now = time();
        if ($data['updateTime'] <= $now) {

            $count = $params['onlineUser.count'];

            if (strtotime($data['updateDate']) <= $now) {
                $data['updateDate'] = date('Y-m-d', strtotime("+1 days"));
                $count++;
                Params::change('onlineUser.count', $count);
            }

            if ($data['updateMorningNight'] <= $now) {
                $nights = [
                    date("Y-m-d 23:55:00"),
                    date('Y-m-d 23:03:00'),
                    date('Y-m-d 00:25:00', strtotime("+1 days")),
                    date('Y-m-d 01:05:00', strtotime("+1 days")),
                ];
                $mornings = [
                    date("Y-m-d 07:00:00"),
                    date("Y-m-d 08:00:00"),
                    date("Y-m-d 08:32:00"),
                    date("Y-m-d 09:10:00"),
                ];

                $data['morning'] = strtotime($mornings[array_rand($mornings)]);
                $data['night'] = strtotime($nights[array_rand($nights)]);

                //$data['morning'] = strtotime(date("Y-m-d 19:33:00")); //test
                //$data['night'] = strtotime(date("Y-m-d 19:34:00")); //test
                $data['updateMorningNight'] = $data['night'];
            }


            list($rangeBegin, $rangeEnd) = explode('-', $params['onlineUser.randomRange']);
            $random = rand($rangeBegin,$rangeEnd);
            //$random = 0;

            $data['onlineCount'] = $count + $random;
            $data['updateTime'] = $now + $params['onlineUser.updateTime'];
            Yii::$app->cache->set('_onlineData', $data, 0);
        }

        if ($now > $data['morning'] && $now < $data['night']) {
            return $data['onlineCount'] + $online;
        }

        return $online;
    }

    /**
     * @param bool $menuKey
     * @return array|mixed
     */
    public static function getPageMenu($menuKey = false)
    {
        static $m;
        if ($m !== null) {
            if ($menuKey) {
                if (isset($m[$menuKey])) {
                    return $m[$menuKey];
                }
                return [];
            }
            return $m;
        }

        $menus = Page::find()->with(['translations','childs' => function ($q) {
            $q->andWhere(['active' => 1, 'visible' => 1]);
        }])->where(['id_parent' => [1,2,3], 'active' => 1, 'visible' => 1])->orderBy(['weight' => SORT_ASC])->all();

        $m = [
            'menu1' => [],
            'menu2' => [],
            'menu3' => [],
        ];

        $baseUrl = Url::getLangUrl();
        foreach ($menus as $menu) {
            $item = [];
            $item['label'] = $menu->name;
            if ($menu->type === 'main') {
                $item['url'] = $baseUrl;
            } else {
                $item['url'] = $menu->url();
            }

            if ($menu->childs) {
                foreach ($menu->childs as $child) {
                    $item['items'][] = [
                        'label' => $child->name,
                        'url' => $child->url(),
                    ];
                }
            }
            if ($menu->id_parent == 1) {
                $m['menu1'][] = $item;
            } elseif($menu->id_parent == 2) {
                $m['menu2'][] = $item;
            } elseif($menu->id_parent == 3) {
                $m['menu3'][] = $item;
            }
        }

        if ($menuKey) {
            if (isset($m[$menuKey])) {
                return $m[$menuKey];
            }
            return [];
        }

        return $m;
    }

}
