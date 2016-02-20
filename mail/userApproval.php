<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $user \app\models\User */
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/user-approve', 'token' => $user->password_reset_token]);
?>
<?= Yii::t("app/signup", "Hello {username}, <br />Follow the link below to approve your account:<br />{link}", ['username' => Html::encode($user->username), 'link' => Html::a(Html::encode($resetLink), $resetLink), 'dot' => false]) ?>
