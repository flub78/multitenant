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
    agent { label 'multi' }
    environment { 
        APP_URL="http://multi.ratus.flub78.net/"
        TENANT_URL="http://test.multi.ratus.flub78.net/"
        INSTALLATION_PATH="/var/lib/jenkins/workspace/Multitenant_pipeline"
        SERVER_PATH="/var/www/multi"
        VERBOSE="-v"
        
        // DB_CRED=credentials('multi_db_user')
        DB_HOST="localhost"        
        // TRANSLATE_API_KEY=credentials('google_translate_api_key')
        DB_USERNAME="${DB_CRED_USR}"
        DB_PASSWORD='$DB_CRED_PSW'
        DB_DATABASE="multi_jenkins"
    }

    stages {
        stage('Static analysis') { 
            steps {
    			sh 'phing -f build-phing.xml ci'
                pmd canRunOnFailed: true, pattern: 'build/logs/pmd.xml'            
            }
        }
        stage('Phpunit') {
            steps {
                //  
        	    echo "Phpunit"
    			// some block
    			// sh 'ansible-playbook ansible/deploy.yml'
    			// sh '.test.sh'  
            }
        }
        stage('Dusk') { 
            steps {
                // 
                echo "Dusk"
            }
        }
    }

    post {
        always {
            // junit testResults: '**/target/surefire-reports/TEST-*.xml'

            // recordIssues enabledForFailure: true, tool: checkStyle()
            // recordIssues enabledForFailure: true, tool: spotBugs()
            // recordIssues enabledForFailure: true, tool: cpd(pattern: '**/target/cpd.xml')
            recordIssues enabledForFailure: true, tool: pmdParser(pattern: 'build/logs/pmd.xml')
        }
    }
}
