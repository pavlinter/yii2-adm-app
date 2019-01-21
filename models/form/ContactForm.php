<?php

namespace app\models\form;

use app\models\ContactMsg;
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
        if (IS_LOCALHOST) {
            $verifyCode = ['verifyCode', 'captcha'];
        } else {
            $verifyCode = [['verifyCode'], \himiklab\yii2\recaptcha\ReCaptchaValidator::class];
        }

        return [
            [['name', 'body', 'phone'], 'filter', 'filter' => function ($value) {
                return Html::encode(trim($value));
            }],
            [['name', 'email', 'phone', 'body'], 'required'],
            ['email', 'email'],
            $verifyCode
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

        $subject = "Message from {site}";
        $body = 'Contact message';
        $paramsText = [
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'body' => 'Message',
        ];
        $params = [
            'name' => $this->name,
            'email' => $this->email,
            'body' => $this->body,
            'phone' => $this->phone
        ];
        foreach ($paramsText as $placeholder => $label) {
            $body .= "<br/>" . $label . ": {" . $placeholder . "}";
        }
        $params['site'] = Yii::$app->name;
        $params['dot'] = false;

        $subject = Yii::t("app/contact", $subject, $params);
        $body = Yii::t("app/contact", $body, $params);

        if ($model) {
            $model->from_email = $this->email;
            $model->subject = $subject;
            $model->text = $body;
            $model->save(false);
        }
        if (IS_LOCALHOST) {
            return true;
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
