<?php

use app\helpers\Url;
use pavlinter\doT\DoT;
use pavlinter\doT\DoTAsset;
use yii\helpers\Json;

/* @var $event \pavlinter\urlmanager\UrlManagerEvent */
/* @var $urlManager \app\components\UrlManager */
$urlManager = $event->sender;
$moduleName = $urlManager->getModuleName();

if (!Yii::$app->user->isGuest) {
    \app\models\User::setOnline();
}

if (in_array($moduleName, [null, 'user', 'admpages'])) {
    $view = Yii::$app->getView();
    DoTAsset::register($view);
    $global = [
        'isGuest' => Yii::$app->user->isGuest,
        'url' => [
            'notification' => Url::to(['/user/settings/load-notification']),
            'notificationViewed' => Url::to(['/user/settings/viewed-notification']),
            'common' => Url::to(['/ajax/common']),
        ],
    ];
    $view->registerJs('var global = ' . Json::encode($global). ';', $view::POS_HEAD);

    if (!Yii::$app->user->isGuest) {

        $view->on($view::EVENT_END_BODY, function ($event) {

            ?>
            <?php DoT::begin(['id' => 'notifi_ticket_msg']); ?>
            <li class="media {{? !it.viewed }}active{{?}}" id="{{=it.unique}}">
                <a href="javascript:void(0);" class="notifi-viewed action-viewed" data-id="{{=it.id}}"><i class="fa {{? it.viewed }}fa-circle-o{{??}}fa-circle{{?}}"></i></a>
                <a href="{{=it.href}}" class="link-block clearfix">
                    <div class="media-left relative">{{=it.inlineHtml || ''}}<img src="{{=it.src}}" class="media-object" alt="" /></div>
                    <div class="notifi-media-body">
                        <h6 class="media-heading">{{=it.username}}</h6>
                        <p>{{=it.msg}}</p>
                        <div class="text-muted f-s-11">{{=it.date}}</div>
                    </div>
                </a>
            </li>
            <?php DoT::end(); ?>

            <?php DoT::begin(['id' => 'check']); ?>
            <li class="media {{? !it.viewed }}active{{?}}" id="{{=it.unique}}">
                <a href="javascript:void(0);" class="notifi-viewed action-viewed" data-id="{{=it.id}}"><i class="fa {{? it.viewed }}fa-circle-o{{??}}fa-circle{{?}}"></i></a>
                <a href="{{=it.href}}" class="link-block clearfix">
                    <div class="media-left relative">
                        {{=it.icon}}
                    </div>
                    <div class="notifi-media-body">
                        <p>{{=it.msg}}</p>
                        <div class="text-muted f-s-11">{{=it.date}}</div>
                    </div>
                </a>
            </li>
            <?php DoT::end(); ?>


            <?php

        });
    }

}