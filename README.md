# Custom Email Templates with the Craft CMS Contact Form Plugin

Use your own HTML email template instead of the Contact Form plugin's default template.

## Requirements

This module requires Craft CMS 3 and the Craft CMS Contact Form plugin.

## Installation

### 1. Download this repository

Download this repository and put the `contactformextras` folder into your project's `/modules` directory.

### 2. Edit your `/config/app.php` file

Tell Craft CMS to load this module:

```php
return [
    'modules' => [
        'contact-form-extras' => [
            'class' => \modules\contactformextras\ContactFormExtras::class,
        ],
    ],
    'bootstrap' => ['contact-form-extras'],
];
```

### 3. Change the fields to match your contact form

Edit this file: `/modules/contactformextras/ContactFormExtras.php`

Change these fields/variables to match your contact form's fields.

```php
$name = $e->submission->fromName;
$email = $e->submission->fromEmail;
$subject = $e->submission->subject;
$message = $e->submission->message;
```

In the same file, modify the variable names to be replaced in the HTML template.

```php
$customEmailTemplate = str_replace('[[name]]', $name, $customEmailTemplate);
$customEmailTemplate = str_replace('[[email]]', $email, $customEmailTemplate);
$customEmailTemplate = str_replace('[[subject]]', $subject, $customEmailTemplate);
$customEmailTemplate = str_replace('[[message]]', $message, $customEmailTemplate);
```

Finally, modify the placeholder field values in the HTML template itself: `/modules/contactformextras/template.html`

And of course, customize this template to your liking!