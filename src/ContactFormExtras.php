<?php
/**
 * Contact Form Extras module for Craft CMS 3.x
 *
 * Use your own HTML email template with the Craft CMS Contact Form plugin.
 *
 * @link      https://lukepeters.me
 * @copyright Copyright (c) 2020 Luke Peters
 */

namespace modules\contactformextras;

use modules\contactformextras\assetbundles\contactformextras\ContactFormExtrasAsset;

use Craft;
use craft\events\RegisterTemplateRootsEvent;
use craft\events\TemplateEvent;
use craft\i18n\PhpMessageSource;
use craft\web\View;

use yii\base\Event;
use yii\base\InvalidConfigException;
use yii\base\Module;

use craft\contactform\events\SendEvent;
use craft\contactform\Mailer;

/**
 *
 * @author    Luke Peters
 * @package   ContactFormExtras
 * @since     1.0.0
 *
 */
class ContactFormExtras extends Module
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this module class so that it can be accessed via
     * ContactFormExtras::$instance
     *
     * @var ContactFormExtras
     */
    public static $instance;

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct($id, $parent = null, array $config = [])
    {
        Craft::setAlias('@modules/contactformextras', $this->getBasePath());
        $this->controllerNamespace = 'modules\contactformextras\controllers';

        // Translation category
        $i18n = Craft::$app->getI18n();
        /** @noinspection UnSafeIsSetOverArrayInspection */
        if (!isset($i18n->translations[$id]) && !isset($i18n->translations[$id.'*'])) {
            $i18n->translations[$id] = [
                'class' => PhpMessageSource::class,
                'sourceLanguage' => 'en-US',
                'basePath' => '@modules/contactformextras/translations',
                'forceTranslation' => true,
                'allowOverrides' => true,
            ];
        }

        // Set this as the global instance of this module class
        static::setInstance($this);

        parent::__construct($id, $parent, $config);
    }

    public function init()
    {
        parent::init();
        self::$instance = $this;

        // Intercept the email before it sends
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
            $e->message->setHtmlBody($customEmailTemplate);
        });

        Craft::info(
            Craft::t(
                'contact-form-extras',
                '{name} module loaded',
                ['name' => 'Contact Form Extras']
            ),
            __METHOD__
        );
    }
}
