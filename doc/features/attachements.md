# Attachments

A file storage mechanism to attach files to elements of table.

## Use cases

As a user
* I store a picture of me in my profile, the scan of a bill in an accounting application, a GPS track of a flight, or any other kind of file.

* I can download a file that I have uploaded or others files for which I have permission.

* I can <span style="color:red">**replace**</span> a file that I have uploaded

* I can delete a file that I have uploaded

* I can see the list of attachments if there are several

* I do not want others user to be able to view my files.
* 
* I want to use a smartphone application to upload pictures directly in the multi tenant WEB application.

As an admin
* I want to do what the users can do but for any user.
  
* I want to set limits to the total storage for a user

## Requirements

* Download URL must be secured, it must not be possible to guess an url to retrieve something.

* It should be possible to attach a file to about anything identified by its table and id. I can attach a file to user 3 or to bill line 3482.

* It must be possible to attach several files to an item or to limit to a certain number.

* In some cases it will be possible to attach many files to an item in others cases only one file will be allowed for one purpose. For example a user picture, uploading another one will replace the previous one.

* It should be possible to control the file types and sizes to avoid that users saturate the storage or use it as personal storage.

* In some case of general purpose storage it may be convenient to structure the storage into sub-directories. (or is it just the purpose?)

* It must be possible to backup and restore the storage per tenant.

* Thumbnails are used to display attachments. On click they should open the file in the browser if it is a supported mime type. If possible, the thumbnails should be dynamically generated. If not, display an icon to show the type of the file, like a pdf icon. If the mime type is totally unknown, just display a file icon and offer to download it.

## Design

![Attachment Classes](images/AttachmentsErd.png) 

## Implementation hints

It is relatively simple to attach files to items in table with big integer primary keys. But primary keys may be string or even more complex indexes, like the concatenation of a date field plus a foreign key ...

Let's limit the feature to table with integer key to start (most of the cases, and may be that the limitation is acceptable).

Referenced_table, referenced_id, user_id and purpose are defined by the application, the others attributes are defined by the user. 
Is it possible to manage that as a set of hidden attributes ? Is it safe ? or should the validation also check that the hidden fields have not been tampered ?

There are cases in which it is legitimate to let to the user the full control on the uploaded files t. In other cases these files may be supporting documents (expense bills, etc.) 
It could be managed in several ways:
* changing the ownership of the files once they have been uploaded making them belong to accounting. It would prevent the user to see them or download them back.
* Have a non_editable attribute for attachment and check it.
* Use the onDelete and onUpdate restrict mechanism to prevent it to happen. It would require a table to reference the frozen attachment (a table named registered_attachements or frozen_attachements with a attachement_id foreign key). Likely a robust approach.

## HTML5 implementation

I had the pleasant surprise to discover that the HTML5 specification includes a mechanism to activate the camera of a smartphone to take a picture and upload it to a server.

```html
<input type="file" id="file" name="file" accept="image/*" capture="camera">
```
The idea is to display attachements
## Local filenames

I want a user to be able to retrieve a file in the server local storage (for debugging and to make backup and restore easier).

In previous implementations the initial filename was not used, which is a good thing especially when I get a file from a smartphone.

Storage structure
* /uploads/
* /uploads/2024
* /uploads/2024/username_yjAHh9R.gif

When the user does not provide a filename, the server generates a name from the table and field to which the file is attached.
