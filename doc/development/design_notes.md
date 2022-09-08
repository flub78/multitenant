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
