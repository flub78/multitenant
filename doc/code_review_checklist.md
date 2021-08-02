# Code review Checklist

In order to be efficient code review must focus on a limited set of objectives.

Note that the goal of the review sessions should be carefully defined before the review. A clear mechanism need to be defined to decide when remarks need to be taken into account.

* Some remarks (bugs or obvious minor fixes) need to be taken into account immediately
* Some are considered technical debt and need to be put in the technical debt backlog 
* Some need to be evaluated to decide if they are worth taken into account.

A backlog should only contains elements for which the decision to do them has been taken. A backlog should only contains elements to be ordered, not elements to park there forever. For keeping track of suggestions or ideas another mechanism must be used).

## Checklist

* Review date
* Reviewer
* Correct indentation (can be taken care by automatic reformatting is the team can agree on a formatter not too harmful).
* Correct PHPDoc
* At least a comment to describe the purpose of the file
* The reviewer have checked the result of the static analyzers on the file
* No obvious bugs, missing use cases, 
* Associated test coverage is correct
* remarks are either
    * rejected
    * or accepted for immediate fix
    * or a story is created for later fix
    * ideas and suggestions are recorded
    