<?php
/* @var $this yii\web\View */
use yii\helpers\Json;

/* @var $redirect string */

//facebook authorization
$this->title = Yii::t("app/authclient", "Loading...", ['dot' => false]);
?>
<div class="site-auth">
    <?= $this->title ?>
</div>
<?php \richardfan\widget\JSRegister::begin(['position' => \yii\web\View::POS_LOAD,]) ?>
<script>
    function popupWindowRedirect(url)
    {
        if (window.opener && !window.opener.closed) {
            window.opener.location = url;
            window.opener.focus();
            window.close();
        } else {
            window.location = url;
        }
    }
    popupWindowRedirect(<?= Json::htmlEncode($redirect) ?>);
</script>
<?php \richardfan\widget\JSRegister::end() ?>