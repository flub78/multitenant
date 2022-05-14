# User Interface

To improve the user interface, the views use sophisticated Javascript modules, most of the based on Jquery

* Jquery, JqueryUI
* Bootstrap
* [Datatable to display tabular data](https://datatables.net/)
* [Datepicker for date input](https://jqueryui.com/datepicker/)
* [Timepicker for time](https://timepicker.co/) 
* [spectrum color picker](https://github.com/seballot/spectrum)

## Datatable

Nice javascript module to enhance user experience when tabular data are displayed. Add pagination, filtering, sorting, PDF export, ect.

Most datatable are instancied into footer.blade.php.

Todo: Localize the strings, currently French is hardcoded

    
## Spectrum color picker

    https://bgrins.github.io/spectrum/
    

### To only display named colors

    $(document).ready(function(){

        $("#showPaletteOnly").spectrum({
            showPaletteOnly: true,
            showPalette:true,
            hideAfterPaletteSelect:true,
            color: 'blanchedalmond',
            change: function(color) {
                printColor(color);
            },
            palette:["red", "green", "blue"],
        });
    });

function printColor(color) {
alert(color.toName());
   //var text = "You chose... " + color.toHexString();    
   //$(".label").text(text);
    
}
</script>

# HTML and data types

## Phone number

* <input type="tel">

### Validation

I am not sure that it is a good idea to be too strict about phone number validation. Doing so would likely prevent the possibility  to have extension, multiple phone numbers, to add comments about when they should be used, ect.

So the tradoff is between a simple string with almost no validation in which everything is possible or a strict schema very limited and getting complex when trying to support more cases.

Note that being to flexible could also limit the capacity of the devices to call the phone number....



