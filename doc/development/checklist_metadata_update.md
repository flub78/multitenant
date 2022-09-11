# Metadata Update Checklist

During development, updating metadadat is a current activity. Here are the recommended steps.

## Checklist 

1. Test the modification using the metadata MySql table.
1. Generate the new code and compare with previous one
1. Run the unit tests
1. Modify the tenanttest database according to the validated modifications
1. Check that the code generator still generate the same code
1. Update the migration procedure
1. Migrate the database
1. Fill it again with basic users, roles, etc.
1. Save a new test database

## Example.

{"subtype" : "currency"}  has been forgotten in the code_gen_types table for the price field.

### Test the modification using the metadata MySql table.

![Indexes](images/metadata_create.png?raw=true "Metadata create")

### Generate the new code and compare with the previous one

![Indexes](images/codegen_compare.png?raw=true "Compare the generated code")

### run the unit tests

![Indexes](images/unit_tests_results.png?raw=true "Tests results")

### Modify the tenanttest database

Use json string in comments: 

* {"subtype" : "currency"} 
* delete the metadata table entry

### Check that the code generator still generate the same code



