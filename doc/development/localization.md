# Localization

The following languages are supported:

* en-US
* fr-FR

## Laravel localization

Localization files are under `resources/lang`.

### Validation messages

Validation messages are under `resources/lang/en/validation.php` and `resources/lang/fr/validation.php`.

They must contains the validation error messages but also the attributes names. Do not forget to add them every time tha ta new model is added.

```php
'attributes' => [
    'name' => 'nom',
    'email' => 'adresse e-mail',
    'password' => 'mot de passe',
    // Add other field translations as needed
],
```

## Javascript localization
