# Models

Models are the interface with the persistence layer.

The inherits from Laravel eloquent model which provide all the necessary basic features. They are complemented by specific accessors.

## Basic common methods

Every table with can be referenced by another table should contain:

### image

A method to display the element in human readable, non ambiguous way. Note that it is not necessarily simple. For example first name plus last name is not sufficient to identify people in a non ambiguous way.

The table primary key is non ambiguous but not human readable. In this specific case maybe that first name plus last name plus the user id inside comma can make the trick. Ex "Jean Dupont (23567)"

### selector

A method to generate a selector. We can imagine several kinds: a simple HTML select, or a Jquery component able to filter elements by typing substrings.


## Test factories

To automate the test generation we also need a few generic method

### create_one_element

To create one element form an integer seed. Calling the method twice with the same seed should generate
the same element.

### equals

To check the identity between two elements.

### duplicate

Create a deep copy of an element

### modify 

To modify an element according to a modification seed. It should be possible to modify only one field.

Ex
    $seed = 3456;      
    $user->modify($seed, "name")

### differences

Should return a human readable string highlighting the differences between two elements. 

### Test example

    $seed = 17;
    $elt = Model::create_one_element($seed);
    
    $elt2 $elt.duplicate();
    
    $this->assertEquals($elt, $elt2);
    
    $field = Model::field_by_index(2);
    $elt2->modify($seed, $field);
    
### Question

Does it make sense to run over and over tests which have been automatically generated to test code which has been automatically generated ? Once the generator has been validated they should always pass.

1. The question is only valid as long as everything has been generated and nothing has been modified. I will ask it again once the generator is able to generate a large percentage of the code which can stay unmodified.
1. It is not really the philosophy of this code generator. It should be an help to generate most of the simple cases, but I do not intend to make it so complex that it can generate all the code.

In the event I reach a level of large percentage of the code generated.

* A test does not only test code but also the environment and context. So it may be interesting to check that not only the generated code is correct but also that MySql is running, that tests and schema are still compatible, etc.

* And once the generated code is modified, it makes sense to test it as manual written code.

So instead of asking if these tests should be run, the question is rather when should test be executed.

All the tests should be run before a deployment, but it is possible to have several jenkins jobs, some for smke tests and the feature under development and others to run before release.
  

    