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
        APP_URL="http://tenants.com/"
        TENANT_URL="http://test.tenants.com/"
        INSTALLATION_PATH="/var/www/html/multi_phpunit"
        SERVER_PATH="/var/www/html/tenants.com"
        VERBOSE="-v"
        DB_HOST="localhost"
        TRANSLATE_API_KEY="AXXXXXXXXXXXXXXXXXXXXXXn6zz4FJk9_c"
        DB_USERNAME="root"
        DB_PASSWORD="password"
        DB_DATABASE="multi_jenkins"
    }
    stages {
        stage('Build') { 
            steps {
	      		// Get some code from a GitHub repository
    	  		git 'https://github.com/flub78/multitenant.git'
                //
                
                echo "Source code fetched"
            }
        }
        stage('Test') {
            steps {
                //  
        	
    			// some block
    			echo "phpunit $APP_URL"
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
