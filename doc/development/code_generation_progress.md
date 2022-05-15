# Code Generation Progress status


## roles table

    php artisan mustache:generate --compare roles controller        OK
    php artisan mustache:generate --compare roles request           OK
    php artisan mustache:generate --compare roles model             OK
    php artisan mustache:generate --compare roles index             OK
    php artisan mustache:generate --compare roles create            OK
    php artisan mustache:generate --compare roles edit              OK
    php artisan mustache:generate --compare roles english           OK
    php artisan mustache:generate --compare roles factory           OK
    php artisan mustache:generate --compare roles test_model        OK
    php artisan mustache:generate --compare roles test_controller   OK
    php artisan mustache:generate --compare roles test_dusk does not exist
    php artisan mustache:generate --compare roles api               OK        
    php artisan mustache:generate --compare roles test_api          OK

## configurations table

    php artisan mustache:generate --compare configurations controller       OK
    php artisan mustache:generate --compare configurations request          OK
    php artisan mustache:generate --compare configurations edit             OK
    php artisan mustache:generate --compare configurations create           OK
    php artisan mustache:generate --compare configurations index            OK
    php artisan mustache:generate --compare configurations model            OK
    php artisan mustache:generate --compare configurations factory          OK
    php artisan mustache:generate --compare configurations english          OK
    php artisan mustache:generate --compare configurations test_model       OK
    php artisan mustache:generate --compare configurations test_controller  OK
    php artisan mustache:generate --compare configurations test_dusk        generates something
    
## users table

    (users is not a tenant table)
    php artisan mustache:generate --compare users controller
    php artisan mustache:generate --compare users request
    php artisan mustache:generate --compare users model                     not exactly
    php artisan mustache:generate --compare users index                     OK
    php artisan mustache:generate --compare users create                    OK
    php artisan mustache:generate --compare users edit                      OK

## user_roles table

    php artisan mustache:generate --compare user_roles controller           to complete 
        missing support for user_list and role_list
        
    php artisan mustache:generate --compare user_roles request              OK
    php artisan mustache:generate --compare user_roles model
        requires attributes to access the referenced element image
        
    php artisan mustache:generate --compare user_roles index        
        user_name and role_name support missing
    php artisan mustache:generate --compare user_roles create               OK
    php artisan mustache:generate --compare user_roles edit                 OK
    php artisan mustache:generate --compare user_roles test_model           not supported yet
        requires creation of users and roles

## calendar_events table

    php artisan mustache:generate --compare calendar_events controller
        Support for dateFormat missing
    
    php artisan mustache:generate --compare calendar_events request
        missing date_format and regexp
        
    php artisan mustache:generate --compare calendar_events model
    php artisan mustache:generate --compare calendar_events index
    php artisan mustache:generate --compare calendar_events create          Almost
        support for default missing
    php artisan mustache:generate --compare calendar_events edit            Almost
        a few ids missing plus no usage of the computed attributes
        
    php artisan mustache:generate --compare calendar_events factory
    php artisan mustache:generate --compare calendar_events test_model
    php artisan mustache:generate --compare calendar_events test_api          
    
## code_gen_types table

    php artisan mustache:generate --compare code_gen_types controller           OK
    php artisan mustache:generate --compare code_gen_types request              OK          
    php artisan mustache:generate --compare code_gen_types model                OK
    php artisan mustache:generate --compare code_gen_types index                OK  
    php artisan mustache:generate --compare code_gen_types create               OK
    php artisan mustache:generate --compare code_gen_types edit                 OK
    php artisan mustache:generate --compare code_gen_types english              OK
    php artisan mustache:generate --compare code_gen_types factory              OK
    
    php artisan mustache:generate --compare code_gen_types test_model           NO    
    php artisan mustache:generate --compare code_gen_types test_controller      NO
    php artisan mustache:generate --compare code_gen_types test_dusk        to be tested
    php artisan mustache:generate --compare code_gen_types api                  OK
    php artisan mustache:generate --compare code_gen_types test_api             NO
        to validate
        
## code_gen_types_view1

    php artisan mustache:generate --compare code_gen_types_view1 model              OK
    php artisan mustache:generate --compare code_gen_types_view1 test_model         OK
    php artisan mustache:generate --compare code_gen_types_view1 index              OK
    php artisan mustache:generate --compare code_gen_types_view1 english            OK
    php artisan mustache:generate --compare code_gen_types_view1 controller         OK
    php artisan mustache:generate --install code_gen_types_view1 test_controller
    
    php artisan mustache:generate --compare code_gen_types_view1 factory
    php artisan mustache:generate --compare code_gen_types_view1 api
    php artisan mustache:generate --compare code_gen_types_view1 test_api
    
## user_roles_view1

    php artisan mustache:generate --compare user_roles_view1 model
    php artisan mustache:generate --compare user_roles_view1 test_model
    php artisan mustache:generate --compare user_roles_view1 index
    php artisan mustache:generate --compare user_roles_view1 english
    php artisan mustache:generate --compare user_roles_view1 controller
    php artisan mustache:generate --install user_roles_view1 test_controller
    
    php artisan mustache:generate --compare user_roles_view1 factory
    php artisan mustache:generate --compare user_roles_view1 api
    php artisan mustache:generate --compare user_roles_view1 test_api
    
## profiles

    php artisan mustache:generate --compare profiles model              OK                
    php artisan mustache:generate --compare profiles factory            OK              
    php artisan mustache:generate --compare profiles test_model         OK           

    php artisan mustache:generate --compare profiles controller         OK       
    php artisan mustache:generate --compare profiles request            OK            
    php artisan mustache:generate --compare profiles test_controller    OK

    php artisan mustache:generate --compare profiles index              OK  
    php artisan mustache:generate --compare profiles create             OK  
    php artisan mustache:generate --compare profiles edit               OK  
    php artisan mustache:generate --compare profiles english            OK
    
    php artisan mustache:translate --compare profile                    OK

    php artisan mustache:generate --compare profiles api                OK                 
    php artisan mustache:generate --compare profiles test_api           OK

    php artisan mustache:generate --compare profiles test_dusk       

## motds 

	set table=motds
	set element=motd
	
	php artisan mustache:generate --verbose %table% doc
	
    php artisan mustache:generate --compare %table% model                              
    php artisan mustache:generate --compare %table% factory                          
    php artisan mustache:generate --compare %table% test_model                    

    php artisan mustache:generate --compare %table% controller                
    php artisan mustache:generate --compare %table% request                        
    php artisan mustache:generate --compare %table% test_controller    

    php artisan mustache:generate --compare %table% index                
    php artisan mustache:generate --compare %table% create               
    php artisan mustache:generate --compare %table% edit                 
    php artisan mustache:generate --compare %table% english            
    
    php artisan mustache:translate --compare %element%                    

    php artisan mustache:generate --compare %table% api                                 
    php artisan mustache:generate --compare %table% test_api           

    php artisan mustache:generate --compare %table% test_dusk       

    