<?php

/**
 * @var \yii\web\View $this
 * @var $model \app\models\form\UserSettings
 * @var string $content
 */
use app\helpers\Html;
use app\helpers\ModelHelper;
use app\helpers\Url;
use app\models\Complaint;
use app\models\User;
use yii\widgets\ActiveForm;

$this->title = Yii::t("app/title", "Profile Page", ['dot' => false]);

?>


<div class="user-profile">

    <div class="row">
        <div class="col-xs-12 col-sm-3 col-md-3">
            <?php

            $photoData = Yii::$app->display->getFileImg(Yii::$app->user->getId(), 'user', [
                'width' => 200,
                'height' => 200,
            ], [
                'dir' => 'main',
            ]);

            ?>
            <div class="profile-image">

                <?php
                $photoData = Yii::$app->display->getFileImg($model->id, 'user', [
                    'width' => 400,
                    'height' => 400,
                ], [
                    'dir' => 'main',
                ]);
                ?>

                <?php
                echo \app\modules\cropper\widgets\Cropper::widget([
                    'image' => $photoData['display'],
                ]);
                ?>

            </div>
        </div>
        <div class="col-xs-12 col-sm-9 col-md-9">
            <?php $form = ActiveForm::begin([
                'id' => 'profile-settings-form',
                'options' => ['class' => 'pform'],
                'successCssClass' => '',
                'fieldConfig' => [
                    'options' => [
                        'class' => 'form-group',
                        'tag' => 'tr',
                    ],
                    'template' => '<td class="field">{label}</td><td>{input}{hint}{error}</td>',
                ],
            ]); ?>

            <?php \app\widgets\Note::begin([
                'type' => \app\widgets\Note::TYPE_WARNING,
            ]); ?>

            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam enim optio voluptatum. Accusamus amet autem consectetur dolor dolores enim ex itaque laborum, perferendis perspiciatis provident quidem recusandae sint sit ullam.

            <?php \app\widgets\Note::end(); ?>




            <table class="table table-profile">
                <tbody>

                <?= $form->field($model, 'username')->textInput() ?>

                <?= $form->field($model, 'email')->textInput(['readonly' => true]) ?>

                <?= $form->field($model, 'firstname') ?>

                <?= $form->field($model, 'lastname') ?>

                <?= $form->field($model, 'display_type')->dropDownList($model::display_type_list())->label($model->getAttributeLabel('display_type') . ' <span class="display-view">' . $model->getField('display') . '</span>'); ?>

                <?= $form->field($model, 'gender')->dropDownList($model::gender_list()); ?>

                <tr>
                    <td></td>
                    <td>
                        <?= Html::submitButton(Yii::t("user/settings/profile", "Update", ['dot' => false]), ['class' => 'btn btn-primary btn-block btn-lg']) ?>
                        <?= Yii::t("user/settings/profile", "Update", ['dot' => '.']) ?>
                    </td>
                </tr>
                </tbody>



            </table>


            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
