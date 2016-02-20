<?php

namespace app\models;

use pavlinter\admeconfig\models\EmailConfig;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $phone;
    public $body;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'body', 'phone'], 'filter', 'filter' => function ($value) {
                return Html::encode(trim($value));
            }],
            // name, email, subject and body are required
            [['name', 'email', 'phone', 'body'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t("model/contact", "Name"),
            'email' => Yii::t("model/contact", "Email"),
            'phone' => Yii::t("model/contact", "Phone"),
            'body' => Yii::t("model/contact", "Message"),
            'verifyCode' => Yii::t("model/contact", "Verification Code"),
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param  string  $email the target email address
     * @return boolean whether the email was sent
     */
    public function sendEmail()
    {

        $model = new ContactMsg();

        $subject = Yii::t("app/contacts", "Message from site", ['dot' => false, 'name' => $this->name, 'email' => $this->email, 'body' => $this->body, 'phone' => $this->phone]);
        $body = Yii::t("app/contacts", "Contact message<br/>Name: {name}<br/>Email: {email}<br/>Phone: {phone}<br/>Message: {body}", ['dot' => false, 'name' => $this->name, 'email' => $this->email, 'body' => $this->body, 'phone' => $this->phone]);

        if ($model) {
            $model->from_email = $this->email;
            $model->subject = $subject;
            $model->text = $body;
            $model->save(false);
        }

        $valid = EmailConfig::eachEmail(function ($email) use ($subject, $body) {
            return Yii::$app->mailer->compose()
                ->setTo($email)
                ->setFrom(Yii::$app->params['adminEmailName'])
                //->setFrom([$this->email => Yii::t("app/contacts", "CORE IT - Website From {name}", ['dot' => false, 'name' => $this->name])])
                ->setReplyTo($this->email)
                ->setSubject($subject)
                ->setHtmlBody($body)
                ->send();
        });

        if ($valid === false) {
            return false;
        }
        return true;
    }
}
