provider "aws" {
  access_key = "${var.access_key}"
  secret_key = "${var.secret_key}"
  region     = "${var.aws_region}"
}

data "template_file" "init" {

  template = "${file("gitlab-runner.sh")}"

}

resource "aws_vpc" "prod-vpc" {
    cidr_block = "${var.cidr_block}"
    enable_dns_support = "true"
    enable_dns_hostnames = "true" 
    enable_classiclink = "false"
   instance_tenancy = "default"    

}

#resource "aws_subnet" "prod-subnet-public" {
#    vpc_id = "${var.vpcid}"
#    cidr_block = "${var.public_subnet}"
#    map_public_ip_on_launch = "true" //it makes this a public subnet
#    availability_zone = "eu-west-1a"
#}

resource "aws_security_group" "witcherSG" {
  name        = "witcherSG"
  description = "SG used to configure inbound/outbound traffic for Witcher"
  vpc_id      = "${var.vpcid}"

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["${var.cidr_block}"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  tags= {   
    Project = "Witcher"   
    name = "wit-sg"   
  
  }
}

resource "aws_launch_configuration" "witcher-lc" {
  image_id             = "${var.app_image_ami}"
  instance_type        = "${var.instance_type}"
  security_groups      = ["${aws_security_group.witcherSG.id}"]
  key_name             = "witcher-test"
  user_data            = "${data.template_file.init.rendered}"
  
  
 lifecycle {
    create_before_destroy = true
  }

}

resource "aws_autoscaling_group" "witcher-asg" {

  name 				   = "witcher-asg"
  launch_configuration = "${aws_launch_configuration.witcher-lc.id}"
  vpc_zone_identifier = ["${var.subnetid}"]
  min_size            = 1
  max_size            = 1
  desired_capacity    = 1

  tags = [
    {
      key                 = "Project"
      value               = "Witcher"
      propagate_at_launch = true
    }
  ]
}