# Custom Email Templates with the Craft CMS Contact Form Plugin

Use your own HTML email template instead of the Contact Form plugin's default template.

## Requirements

This module requires Craft CMS 3 and the Craft CMS Contact Form plugin.

## Installation

### 1. Download this repository

Download this repository and move the `contactformextras` folder into your Craft CMS `/modules` directory.

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

### 3. Edit your `composer.json` file

Add the following code to your `composer.json` file so Craft CMS can find this module:

```php
"autoload": {
  "psr-4": {
    "modules\\contactformextras\\": "modules/contactformextras/src/"
  }
}
```

Then run this command in the terminal: `composer dump-autoload`

### 4. Change the fields to match your contact form

Open the main module file (`/modules/contactformextras/ContactFormExtras.php`) and change the fields/variables to match your contact form's fields.

```php
$name = $e->submission->fromName;
$email = $e->submission->fromEmail;
$subject = $e->submission->subject;
$message = $e->submission->message;
```

In the same file, modify the variables to be replaced in the HTML template.

```php
$customEmailTemplate = str_replace('[[name]]', $name, $customEmailTemplate);
$customEmailTemplate = str_replace('[[email]]', $email, $customEmailTemplate);
$customEmailTemplate = str_replace('[[subject]]', $subject, $customEmailTemplate);
$customEmailTemplate = str_replace('[[message]]', $message, $customEmailTemplate);
```

### 5. Edit the email template file

Finally, modify the placeholder field values in the HTML template itself: `/modules/contactformextras/template.html`

And of course, you can customize this template as much as you like!