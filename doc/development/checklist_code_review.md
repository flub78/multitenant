# Code review Checklist

To be efficient code review must focus on a limited set of objectives.

Note that the goal of the review sessions should be carefully defined before the review. A clear mechanism need to be defined to decide when remarks need to be taken into account.

* The obvious remarks (bugs or minor fixes) must be taken into account immediately
* Technical debt and should to be put in the technical debt backlog 
* Others need to be evaluated to decide if they are worth taken into account.

The backlog should only contains elements for which the decision to work on them has been taken. Use another mechanism to keep track of suggestions or ideas.

## Checklist

* The date of the last review is in the file header
* The reviewer name is in the file header
* Indentation is correct (can be taken care by automatic reformatting if the team can agree on a formatter not too harmful).
* Correct PHPDoc
    * At least a comment to describe the purpose of the file
    * Types of parameters are specified in the function declaration
    * Types of parameters are specified in the PHPDoc comment
    * The purpose of every method is provided in the PHP doc (or self described in the function name)
* The reviewer have checked the result of the static analyzers on the file
* No obvious bugs or missing use cases, 
* Test coverage is correct

* remarks are either
    * rejected
    * or accepted for immediate fix
    * or a story is created for later fix
    * ideas and suggestions are recorded

# When to do code reviews

* By pair programming
* When code is commited
* When a file has never been reviewed (allocate some time for catching up)

# Tools to support code reviews

It is possible to use code reviews tools like github pull requests.

A simple convention to keep track of code reviews is just to insert a comment inside the file:

    /**    
     * @reviewed 2022-01-18 by x
     */

With that it is easy to check if a file has been reviewed or not and if there is not to much time between the latest changes and the latest review. Ideally the latest review should have the same date than the latest change.
     