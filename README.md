# Git lab runner on the EC2 machine script
```
#!/bin/bash
curl -L "https://packages.gitlab.com/install/repositories/runner/gitlab-runner/script.rpm.sh" | sudo bash
sudo yum install gitlab-runner -y
sudo yum update -y
sudo amazon-linux-extras install docker -y
sudo systemctl enable docker
sudo usermod -a -G docker ec2-user
sudo gitlab-runner register --description “ec2-terraform-ci/cd-test”  --config /etc/gitlab-runner/config.toml --non-interactive --url https://gitlab.com/ --tag-list aws,user-api --locked --registration-token QzKSxM5pCRPuiwCyun-x --executor shell
sudo systemctl restart docker.service
sudo chmod 666 /var/run/docker.sock
```
