pipeline {
    agent any 
    stages {
        stage('Build') { 
            steps {
	      		// Get some code from a GitHub repository
    	  		git 'https://github.com/flub78/multitenant.git'
                //
                sh 'ls' 
                echo "Source code fetched"
            }
        }
        stage('Test') { 
            steps {
                // 
                echo "Test step 1" 
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
