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
        /*
        The tests are run locally, on a machine with no Internet access. It is relatively safe to use hard coded credentials.
        */
        BASE_URL="http://tenants.com/"
        APP_URL="http://tenants.com/"
        TENANT_URL="http://test.tenants.com/"
        INSTALLATION_PATH="/var/www/html/multi_phpunit"
        SERVER_PATH="/var/www/html/tenants.com"
        VERBOSE="-v"

        DB_HOST="localhost"
        DB_USERNAME="jenkins"
        DB_PASSWORD="jenkins_password"
        DB_DATABASE="multi_jenkins"

        /*
        ansible-playbook ansible/deploy.yml
        ./test.sh
        */
    }

    stages {
        stage('Static analysis') { 
            steps {
    			sh 'phing -f build-phing.xml ci'
            }
        }
        stage('Phpunit') {
            steps {
                //  
        	    echo "Phpunit"
                 sh 'hostname'
                sh 'pwd'
                sh 'id'
                sh 'ls'
    			sh 'ansible-playbook ansible/deploy_from_jenkins.yml'
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

            // recordIssues enabledForFailure: true, tool: spotBugs()
            recordIssues enabledForFailure: true, tool: cpd(pattern: 'build/logs/phpcpd.xml')
            recordIssues enabledForFailure: true, aggregatingResults: true, tool: checkStyle(pattern: 'build/checkstyle.xml')
            recordIssues enabledForFailure: true, tool: pmdParser(pattern: 'build/logs/pmd.xml')
        }
    }
}
