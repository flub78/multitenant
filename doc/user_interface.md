# User Interface

To improve the user interface, the views use sophisticated Javascript modules, most of the based on Jquery

* Jquery, JqueryUI
* Bootstrap
* Datatable to display tabular data
* [Datepicker for date input](https://jqueryui.com/datepicker/)
* [Timepicker for time](https://timepicker.co/) 
* [spectrum color picker](https://github.com/seballot/spectrum)

## Spectrum color picker

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