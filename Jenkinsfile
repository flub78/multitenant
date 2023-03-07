/*
 * Jenkinsfile for a Laravel project
 *
 * Stages:
 *   - Build just fetch the project
 *   - Test run the phpunit tests
 *
 * Environment variables
 *   - Laravel configuration
 *     - APP_URL the Laravel application domain
 *     - TENANT_URL domain for the test tenant likely a subdomain of APP_URL
 *     - INSTALLATION_PATH home of the laravel files 
 */
pipeline {
    agent any 
    environment { 
        APP_URL="http://multi.ratus.flub78.net/"
        TENANT_URL="http://test.multi.ratus.flub78.net/"
        INSTALLATION_PATH="/var/lib/jenkins/workspace/Multitenant_pipeline"
        SERVER_PATH="/var/www/multi"
        VERBOSE="-v"
        
        DB_CRED=credentials('multi_user_db')
        API_KEY=credentials('google_translate_api_key')
        DB_HOST="localhost"        
        TRANSLATE_API_KEY="AXXXXXXXXXXXXXXXXXXXXXXn6zz4FJk9_c"
        DB_USERNAME="${DB_CRED_USR}"
        DB_PASSWORD="password"
        DB_DATABASE="multi_jenkins"
    }
    stages {
        stage('Build') { 
            steps {
	      		// Get some code from a GitHub repository
    	  		git 'https://github.com/flub78/multitenant.git'
                
                echo "Source code fetched"
    			echo "APP_URL = $APP_URL"
    			echo "DB_USERNAME=$DB_CRED_USR"
    			echo "DB_PASSWORD=$DB_CRED_PWD"
    			echo "translate_key=$API_KEY"
            }
        }
        stage('Test') {
            steps {
                //  
        	
    			// some block
    			sh 'ansible-playbook ansible/deploy.yml'
    			sh '.test.sh'  
            }
        }
        stage('Deploy') { 
            steps {
                // 
                echo "Deploy step 1"
            }
        }
    }
}
