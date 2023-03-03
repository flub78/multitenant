pipeline {
    agent any 
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
        	withEnv(['BASE_URL="http://tenants.com/"', 
        		'APP_URL="http://tenants.com/"',
        		'TENANT_URL="http://test.tenants.com/"',
        		'INSTALLATION_PATH="/var/www/html/multi_phpunit"',
        		'SERVER_PATH="/var/www/html/tenants.com"',
        		'VERBOSE="-v"',
        		'TRANSLATE_API_KEY="AIzaSyCqErGhYlWrro1f3wo8dU-On6zz4FJk9_c"',
        		'DB_HOST="localhost"',
        		'DB_USERNAME="root"',
        		'DB_PASSWORD="cpasbelo"',
        		'DB_DATABASE="multi_jenkins"']) {
    			// some block
    			echo "phpunit $BASE_URL"
    			sh 'ansible-playbook ansible/deploy.yml'
    			sh '.test.sh'  
			}

                echo "phpunit ..." 
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
