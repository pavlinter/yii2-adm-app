<?php

namespace app\assets_b;

use Yii;
use yii\web\AssetBundle;


/**
 * Class SocialLikesAsset
 * @link https://uptolike.com
 */
class SocialLikesAsset  extends AssetBundle
{
    public function init()
    {
        $view = Yii::$app->getView();
        $view->registerJs("(function(w,doc) {if (!w.__utlWdgt ) {w.__utlWdgt = true;var d = doc, s = d.createElement('script'), g = 'getElementsByTagName';s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;s.src = ('https:' == w.location.protocol ? 'https' : 'http')  + '://w.uptolike.com/widgets/v1/uptolike.js';var h=d[g]('body')[0];h.appendChild(s);}})(window,document);");
        parent::init();
    }
}
