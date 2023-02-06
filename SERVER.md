# Core Server Configuration

## EC2 
### Instances
#### Launch Templates

```settings
ID: lt-01f81caa05db38671 
Name: core.skaraudio.com

AMI-ID: ami-0a6f9909653a0f25f
Instance Type: t3.xlarge
Security Groups ID: sg-0ce2de1e4c752fb7f
```
#

### Images
#### AMI's
```settings
ID: ami-0a6f9909653a0f25f
Name: core.skaraudio.com.ami.3.24
```
#

### Load Balancing
#### Load Balancers
```settings
Name: lb-core-skaraudio-com
ARN: arn:aws:elasticloadbalancing:us-east-1:547905847706:loadbalancer/app/lb-core-skaraudio-com/1cb95575a2c8b3cc
DNS Record: lb-core-skaraudio-com-812535844.us-east-1.elb.amazonaws.com
VPC: vpc-094f83dd55b1f0d00

Availability Zones:
- subnet-02b01f146afd97850 - us-east-1a
- subnet-02b01f146afd97850 - us-east-1b

Hosetd Zone: Z35SXDOTRQ7X7K

Listeners: 
- HTTP:80 forwarding to tg-core-skaraudio-com
- HTTP:443 forwarding to tg-core-skaraudio-com

Global Accelerator
- Name: lbcoreskaraudiocomAccelerator
- ARN: arn:aws:globalaccelerator::547905847706:accelerator/96f3afe5-698f-4059-b457-8e8fb5b84d64
- DNS Name: a7b0b3f2e64342859.awsglobalaccelerator.com
- Static IP Set: 
  - IPv4: 75.2.105.171
  - IPv4: 99.83.143.39
```
#### Target Groups
```settings
Name: tg-core-skaraudio-com
VPC: vpc-094f83dd55b1f0d00 
Load balancer: lb-core-skaraudio-com
Health Check: 
  - Protocol: HTTP
  - Unhealthy threshold: 2
  - Path: /
  - Timeout: 2
  - Port: traffic-port
  - Interval: 10 (10 Seconds)
  - Healthy threshold: 2
  - Success codes: 200
```
#

### Auto Scaling
#### Auto Scaling Groups
```settings
Name: core.skaraudio.com.asg
Launch Template: core.skaraudio.com/Version Latest
Group Details: 
Desired capacity: 2
Minimum capacity: 2
Maximum capacity: 3
```

## RDS
### 
```settings
ID: rw-core-skar-audio-1

foobar.pluralize('word') # returns 'words'
foobar.pluralize('goose') # returns 'geese'
foobar.singularize('phenomena') # returns 'phenomenon'
```

## S3
### 
```settings
import foobar

foobar.pluralize('word') # returns 'words'
foobar.pluralize('goose') # returns 'geese'
foobar.singularize('phenomena') # returns 'phenomenon'
```
