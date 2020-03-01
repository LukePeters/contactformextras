<?php
namespace modules\CustomEmailTemplate;

use Craft;

use craft\contactform\events\SendEvent;
use craft\contactform\Mailer;
use yii\base\Event;

class CustomEmailTemplate extends \yii\base\Module
{

    public function init()
    {
        // Set a @modules alias pointed to the modules/ directory
        Craft::setAlias('@modules', __DIR__);

        // Set the controllerNamespace based on whether this is a console or web request
        if (Craft::$app->getRequest()->getIsConsoleRequest()) {
            $this->controllerNamespace = 'modules\\console\\controllers';
        } else {
            $this->controllerNamespace = 'modules\\controllers';
        }

        parent::init();

        // Custom initialization code goes here...
        Event::on(Mailer::class, Mailer::EVENT_BEFORE_SEND, function(SendEvent $e) {

            // Get the form values
            $name = $e->submission->fromName;
            $email = $e->submission->fromEmail;
            $subject = $e->submission->subject;
            $message = $e->submission->message;
            
            // Load the custom email template
            $customEmailTemplate = file_get_contents(__DIR__ . "/template.html");

            // Insert form values into the custom email template
            $customEmailTemplate = str_replace('[[name]]', $name, $customEmailTemplate);
            $customEmailTemplate = str_replace('[[email]]', $email, $customEmailTemplate);
            $customEmailTemplate = str_replace('[[subject]]', $subject, $customEmailTemplate);
            $customEmailTemplate = str_replace('[[message]]', $message, $customEmailTemplate);
            
            // Replace the email text body and HTML body with our custom template
            $e->message->setTextBody($customEmailTemplate);
            $e->message->setHtmlBody($customEmailTemplate);
        });
    }
}