variable "app_image_ami" {
  description = "AmazonLinux, a custom IAM read from the SSM"
  default     = "ami-04dd4500af104442f"
}

variable "instance_type" {
  description = "instance_type"
  default     = "t2.micro"
}

variable "cidr_block" {
  description = "cidr_block"
  default     = "10.0.0.0/16"
}


variable "public_subnet" {
  description = "public_subnet"
  default     = "10.0.1.0/24"
}

variable "access_key" {
  description = "access_key"
  default     = ""
}
variable "secret_key" {
  description = "secret_key"
  default     = ""
}

variable "aws_region" {
  description = "aws_region"
  default     = "eu-west-1"
}

variable "vpcid" {
  description = "vpcid"
  default     = "vpc-092302abe732a6a0a"
}

variable "subnetid" {
  description = "subnetid"
  default     = "subnet-043be9b7934f862f5"
}

