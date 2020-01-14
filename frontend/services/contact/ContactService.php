<?php

namespace frontend\services\contact;
use frontend\forms\ContactForm;
use Yii;


class ContactService
{

    //private $supportEmail;
//    public function __construct($adminEmail)

//    {
//        //$this->supportEmail = $supportEmail;
//        $this->adminEmail = $adminEmail;
//    }

    //private $adminEmail;
    //private $sender;

    public function send(ContactForm $form, $adminEmail): void
    {

         Yii::$app->mailer->compose()
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo( Yii::$app->params['supportEmail'])
            ->setSubject('Сообщение с сайта ' . Yii::$app->name)
            ->send();

//        $sent = \Yii::$app->mailer->compose()
//            ->setFrom($form->email)
//            //->setFrom( $this->supportEmail )
//            ->setTo($adminEmail)
//            //->setReplyTo([$this->email => $this->name])
//            ->setSubject($form->subject)
//            ->setTextBody($form->body)
//            ->send();

//          if(!$sent)
//              throw new \RuntimeException('Ошибка отправления');

    }


}