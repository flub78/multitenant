# Design Notes

Here is a collection of short design notes on several subjects. They usually does not deserve their own documentation page.

## Catching exceptions in controllers store and update methods

These methods rely on user input and even with a careful validation there is still a possibility of incorrect data passing through.

I realize that by saying that I admit that the software can potentially be attacked and that security holes are possible. It reinforce my motivation to validate user input but the question is still valid, should exceptions be caught in these methods?

The default behavior for the server is to return a 500 error and a stack trace in development.

Todo:
	- create a use case that raise an exception
	- check the actual behavior in deployment and dev mode
	
Note:

I need to be cautious to not leak information that could help an attacker.

Current conclusion:

It need to be confirmed, but it is likely useless to catch these exceptions, even if I usually do not feel comfortable to let exceptions being propagated.

## Client side validation

### Before submit client side validation

They can be used to guide the user and turn green (Bootstrap) fields once they are valid. It implies to re-implement laravel validation and to add a class relative to the metadata types and subtypes. One difficulty is to mimick exactlly the Laravel validation methods which can be tricky and time consuming. Easy to do when fields are just required.

### After submit validation

It is a more light weight approach (which does not preclude the previous one). In this case the Bootstrap validation mechanism is just used to mark the fields which have been rejected by Laravel. It is already helping the user.

One difficulty: should invalid fields stay red until the next validation ? It makes sense or we need to implement the "before submit validation" to turn off the red border once the field is valid.

### Temporary conclusion

When using special HTML input types, some navigators already perform client side validation on submit. To some extend it is annoying as the validation is a two step process. First a javascript client side validation, then a server side validation. The problem is that errors are not reported with the same look and feel.

For the moment only the second approach seems to make sense, just identifying the fields rejected by Laravel and turning them red.