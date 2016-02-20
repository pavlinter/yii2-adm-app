<?php

namespace app\modules\cloud\models;

use Yii;
use yii\base\Model;

/**
 * CroppicForm
 */
class CroppicForm extends Model
{
    /**
     * @var \yii\web\UploadedFile file attribute
     */
    public $file;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'extensions' => 'gif, jpg, png', 'skipOnEmpty' => false],
        ];
    }
}
