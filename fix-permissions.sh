#!/bin/bash

## declare an array variable
declare -a arr=("./includes/profile/uploads/"
				"./includes/create-business-card/uploads/"
				"./export/")

## now loop through the above array
for i in "${arr[@]}"
do
   echo "Setting ownership of: $i"
   sudo chown www-data:www-data -R $i
   sudo chmod 755 -R $i
done

sudo touch "./cardition_api/temp.jpg"
sudo chown $USER:www-data "./cardition_api/temp.jpg"
sudo chmod 755 "./cardition_api/temp.jpg"