# Authentication Middleware Discussion


- Laravel's core authentication system handles JSON responses for unauthenticated API requests
 
- The message "Unauthenticated" with 401 status code is returned by default when catching AuthenticationException

- Custom JsonAuthenticationMiddleware is not needed since the built-in system already provides proper JSON responses

- The flow goes through: protected route -> auth check -> AuthenticationException -> JSON response

