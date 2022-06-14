# Lineage2 Clan Registration and Listing, PHP Plugin/Class

This is a lightweight plugin that allows your players to register their clans and list them on your website for various reasons.

## How to install
1. Place ClanRegistrationModule wherever you need it in your website then open the php page where you want the form to appear then include the script as follows
```php
require_once "ClanRegistrationModule/index.php";
$request = new \ClanRegistrationModule\index();
```
3. Once loaded all you have to do is call the registration form and the list as follows
- Call the style file between your <head></head> tags
```php
<?= $request::getStyle() ?>
```
- Insert registration form
```php
<?= $request::getForm() ?>
```
- Insert the preview table
```php
<?= $request::getList(['subTitle' => ["My awesome subtitle", true]]) ?>
```

## Misc
You can update the tale and form style by opening ClanRegistrationModule/Plugin/Vendor/main.css

##################################################
Copyright ©MMLTech Coding Services
Website: https://mmltools.com

Redistribution of this code is not permitted
Do not touch the code inside Engine unless you 
know what you are doing
##################################################