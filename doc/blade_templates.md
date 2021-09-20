# Blade templates

The project uses the blade template engine to generates the views. It is flexible and already fully integrated in Laravel.

However I want to have parametric views and avoid complex HTML pages in which blade directives and data are embedded and hard to find. It implies to generate automatically the structure either through templating or call of PHP functions to generate the HTML construct.

Doing it that way it will be easier to insure that the generated HTML is valid, correctly indented, etc.

Initially I try to extend the blade directives in AppServiceProvider, it is elegant but it accept only a unique string as parameter. Attempts to encode the parameters as a comma separated values of json string have only been partially successful.

So I started to do the same thing by creating a set of static building methods in the BladeHelper class. It works, but the syntax is not as elegant than the blade directive approach. With this approach it is easy to handle even the more complex cases.

One possible drawback, is that the call to methods is dynamic, so we do not take much advantage of the view caching mechanism. I still have to evaluate the performance impact to call may be 100 PHP functions to render a wiew. It could perfectly be not significant in most cases. If it it is the mechanism can be improved, as all information is know at compile time, maybe by relying on a previous step of templating (mustache) that would generate the blade templates based on each data type. 