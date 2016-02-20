<?php
/* @var $this yii\web\View */
/* @var $settings array */
\app\modules\admunderconst\UnderConstAsset::register($this);
$this->registerJs('
    $(".und-close").on("click", function(){
        $(this).closest(".und-alert").hide();
    });
');
?>
<div class="und-alert">
    <span class="und-text"><?= Yii::t('app', 'Enabled Under Construction', ['dot' => false]); ?></span>
    <a class="und-close" href="javascript:void(0);">&times;</a>
</div>





