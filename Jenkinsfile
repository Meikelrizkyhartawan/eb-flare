node {   
    stage('Clone repository') {
        git credentialsId: 'git', url: 'https://github.com/Xredys-19/eb-flare'
    }
    
    stage('Build image') {
       dockerImage = docker.build("meikel19/eb-flare-test:latest")
    }
    
 stage('Push image') {
        withDockerRegistry([ credentialsId: "dockerhubaccount", url: "" ]) {
        dockerImage.push()
        }
    }    
}