#!/bin/bash

#Create a copy of the environment variable file.
sudo cp /opt/elasticbeanstalk/deployment/env /opt/elasticbeanstalk/deployment/veva_env

#Set permissions to the custom_env file so this file can be accessed by any user on the instance.
sudo chmod 644 /opt/elasticbeanstalk/deployment/veva_env

#Remove duplicate files upon deployment.
sudo rm -f /opt/elasticbeanstalk/deployment/*.bak
