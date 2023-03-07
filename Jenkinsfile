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
        
        DB_CRED=credentials('multi_db_user')
        DB_HOST="localhost"        
        TRANSLATE_API_KEY=credentials('google_translate_api_key')
        DB_USERNAME="${DB_CRED_USR}"
        DB_PASSWORD="$DB_CRED_PSW"
        DB_DATABASE="multi_jenkins"
    }
    stages {
        stage('Build') { 
            steps {
	      		// Get some code from a GitHub repository
    	  		git 'https://github.com/flub78/multitenant.git'
                
                echo "Source code fetched"
    			echo "APP_URL = $APP_URL"
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
