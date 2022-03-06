# Attachments

A file storage mechanism.

## Use cases

As a user
* I want to store a picture of me in my record
* I want to store the scan of a bill in an accounting application
* I want to upload a GPS track of a flight
* I want to be able to download a file that I have uploaded or others files to which I have permission
* I want to replace a file that I have uploaded
* I want to delete them
* I want to see the list of attachments if there are several

As an admin
* I want to be able to do what the users are allowed to do but for any user.
* I want to be able to set limits to the total storage of a user

And later

* I want to use a smartphone application to upload pictures directly in the multi tenant WEB application.

## Requirements

* Download URL must be secured, which means that it must not possible to guess URL from another file from a legitimate URL. You may be allowed to download your bills, but not the ones of another user.

* It should be possible to attach a file to about anything identified by its table and id. I can attach a file to user 3 or to bill line 3482.

* It must be possible to attach several files to an item or to limit to certain number.

* In some cases it will be possible to attach many files to an item in others cases only one file will be allowed for one purpose. For example a user picture, uploading another one will replace the previous one.

* It should be possible to control the file types and sizes to avoid that users saturate the storage or use it for something else.

* In some case of general purpose storage it may be convenient to structure the storage into sub-directories.

* It must be possible to backup and restore the storage per tenant.


## Design

    # a model to manage attachments 
    class Attachement {
        id
        reference_table name of the table item to which the object is attached
        reference_id
        name visible file name
        hash internal file name, used in URL and API to download, replace, delete
        description
        mime_type
        purpose a string to describe the purpose of this file inside the application ex: user_picture 
    }
    
    # rules to check that uploads are legitimate
    class AttachmentRule {
        id
        reference_table
        boolean multiple
        allowed_mime_types
        allowed_purpose
        max_size
    }
    
    # Attachment manager
    class AttachmentManager {
        max_total_storage
    }