# Translation

There is an artisan command to translate the Laravel language file of your application. This tool uses the Google Translate API. Here a tutorial [Using Google Translate API tutorial](https://www.sitepoint.com/using-google-translate-api-php/).

You need to create a Google account and authorize the API. It requires an API Key. This service is billed but the cost should stay very low for this kind of usage.

English language files can be translated in any language supported by Google translate (French by default) using: 

    php artisan mustache:translate  --compare configuration 
    php artisan mustache:translate  --compare configuration --lang fr
    
[More than 60 supported languages](https://www.labnol.org/code/19899-google-translate-languages)
