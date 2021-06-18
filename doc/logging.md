# Logging

## Usage

Logging is done with the Log facade.

    use Illuminate\Support\Facades\Log;
    
and invoked by

    Log::debug('xxx.create');
    
by default logs are written in storage/logs/laravel.log


## Recommendations

It is recommended to log

* All users login and logout
* All model creation, modification and delete call (all the calls that change the database). 
If possible log the modified data  data before and after).

It is also recommended to store the user who performed the action.

If all database changes are logged by the model, it is useless to clutter the log files with controller entries. 

## Model logging

The ModelWithLogs class is a thin layer between model classes and their parent. This layer just create a log entry for
all actions that modify the database. Reflection is used to keep it generic. All the Model class which inherit from this class automatically
see their routines logged.

Note that inheritance is used to add the feature to existing classes, but it only works when they inherit directly from Model. It makes the modification not really convenient when the model classes inherit indirectly.
TODO: Find a smarter implementation.

## Logs files rotation

The log file rotation on a Linux server can be handled by the operating system. No needs
to implement anything at the application level.

    https://www.jesusamieiro.com/how-to-rotate-the-laravel-logs/