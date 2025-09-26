#!/bin/bash

# Get the current branch name from the environment variable
current_branch=$BRANCH_NAME

echo "Current branch is $current_branch"
# Only allow certain branches
if [ "$current_branch" != "development" ] && [ "$current_branch" != "staging" ]; then
    echo "Error: This script can only be run on the 'development' or 'staging' branches."
    exit 1
fi


# Pull the latest changes from the git repository
git fetch origin $current_branch
git reset --hard origin/$current_branch
git clean -df

# Install/update composer dependecies
composer install
composer dump-autoload

php artisan migrate 


php artiasn config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear