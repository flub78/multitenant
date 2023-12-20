# PDF Generation

It is an important feature of this framework. It must be possible to generate pdf document for different reasons; reports, reservation tickets, etc.

There is already a pdf export in datatable that covers the basic array views. But I also need to generate more sophisticated documents including pictures, QR-codes etc.

The basic example of pdf generation will be the the reservation of something for a certain time and date. The certificate will include a validation QR-code with an URL. The scan of the QR-code will bring to a validation controller that will display a success method if the QR-code is valid and an error message if the QR-Code is invalid or has already been validated.

The URL will likely contain a hash string so that it is not possible to guess or forge valid URLs.

## Technologies

### laravel-dompdf

https://www.akilischool.com/cours/laravel-generer-un-pdf-avec-laravel-dompdf

This package generates a pdf from a view or blade template. It is relatively convenient as all which is required to generate a view is already mastered in a WEB application.

### Libre-Office

Generating document using Libre-office is also an option for complicated documents. The libre office API is pretty rich but would imply the use of one of the supported language like Javascript or Python. 

Note that the laravel-dompdf is likely simpler and powerful enough for most use cases.