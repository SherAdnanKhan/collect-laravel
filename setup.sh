#!/bin/bash

LARADOCK_VERSION="v10.0"

GREEN="\033[1;32m"
NOCOLOR="\033[0m"
RED="\033[1;31m"

if [ -d "./laradock" ]; then
    read -p "Laradock folder already exists. Overwrite? [y/N]: " overwrite
    if [ "$overwrite" != "y" ]; then
        echo -e "${RED}Stopping!${NOCOLOR}"
        exit
    fi
fi

rm -rf ./laradock

echo -e "Cloning laradock..."
git clone https://github.com/Laradock/laradock.git --quiet
if [[ $? != 0 ]]; then
    echo -e "${RED}Error!${NOCOLOR}"
    exit
fi

cd laradock
git checkout "tags/$LARADOCK_VERSION" --quiet
cd ..
rm -rf laradock/.git

shopt -s dotglob

echo -e "Copying laradock files..."
yes | cp -rf .laradock/* laradock

echo -e "${GREEN}Complete!${NOCOLOR}"
