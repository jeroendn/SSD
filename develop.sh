#!/bin/bash

# Check if the script is running on Windows
if [ -z "$WINDIR" ]; then
    # Running on Linux or Unix-like system
    echo "Running on Linux"

    # Stop and remove existing containers
    docker-compose down

    # Start the containers
    docker-compose up -d --build

else
    # Running on Windows
    echo "Running on Windows"

    # Stop and remove existing containers using PowerShell
    docker-compose down

    # Start the containers using PowerShell
    docker-compose up -d --build
fi
