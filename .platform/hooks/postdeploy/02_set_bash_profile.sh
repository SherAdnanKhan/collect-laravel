#!/bin/bash

echo "source <(sed -E -n 's/[^#]+/export &/ p' /opt/elasticbeanstalk/deployment/veva_env)" >> /home/ec2-user/.bash_profile
