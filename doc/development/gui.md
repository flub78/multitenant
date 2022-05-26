# Graphical User Interface

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

# Responsive Display

Even if the long term objective is to also create an Android application to provide the same service than the WEB application, one relatively easy way to achieve it is to make the graphical interface responsive. Which means able to adapt to screen width and resolution. Basing the display on Bootstrap is a way to achieve that. Note that having a real Android application still make sense at least to take advantage of the specific devices of a smartphone like the camera or GPS.

## Basic GUI elements

Most of the pages of the application will contain:

* An optional header banner
* An horizontal navbar displayed has a button to open a vertical menu on a phone.
* A breadcrumb navigation (or any other way to make where he is obvious to the user)
* A leftsize contextual menu
* A content Area
* A footer

The content Area will contain
* Filtering information
* Sums and averages on the displayed data (be careful, these information should be computed on all the selection whatever the number of pages). On some data like account operations, it may makes more sense to display the whole totla of the account rather than partial sums on the displayed operations.
* Tabular data (datatable).
* Optional graphical data (same data displayed on a diagram).

### Lesson learned from previous projects.

It is usually counter productive to pack too much information in the data table display. It makes it difficult to read on a smartphone and it is usually rarely used
on large screen.

Should be present on the table display only 
* main information on every element
* information that can to be compared between elements
* information that can potentialy be used to make a fuzzy search. 

All others information should be reserved to the element detailed information view. Note that I have often disregarded the show view (display of everything that the system knows about one resource) and I have more often used blotted table views. I consider that it was an error wich is now more critical if you want to propose an easy access from a smartphone.

Same thing, it is not necessarily a good idea to multiply action buttons on the table view. Better to have a select box and global actions on the selection.

In fact the choice between selection checkbox and global actions and individual actions on each item heavily depends on the most frequent use cases. Is it more frequent a apply action to one element, or globaly.

For example, phpmyadmin supports global editing and then open a form with several resources displayed sequentially. It is a little weird.

Note that AWS has no actions attached to a resource, only global actions applied to the selection. But systematically a way to access to the show view.

And the show view itself has an actions dropdown menu for actions to be applied to this unique resource.

My previous projet had small icons for button action to edit and delete. Compared to a select checkbox coumn, it was not really taking significantly more space but there was no support for group actions.