#!/bin/bash
#download the gitlab runner
curl -L "https://packages.gitlab.com/install/repositories/runner/gitlab-runner/script.rpm.sh" | sudo bash
#install using yum
sudo yum install gitlab-runner -y
sudo yum update -y
#install the docker on the machine
sudo amazon-linux-extras install docker -y
#enable the docker to interact
sudo systemctl enable docker
#add the user to access the gitlab
sudo usermod -a -G docker ec2-user
#Register to build the integration btwenn GIT-LAB and the AWS EC2
sudo gitlab-runner register --description “ec2-terraform-ci/cd-test”  --config /etc/gitlab-runner/config.toml --non-interactive --url https://gitlab.com/ --tag-list aws,user-api --locked --registration-token QzKSxM5pCRPuiwCyun-x --executor shell
# restart the docker service to start 
sudo systemctl restart docker.service
#Give permission to the docker to fetch from docker hub repository
sudo chmod 666 /var/run/docker.sock