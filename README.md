# JetRiv

These instructions will install JetRiv on your local computer. The instructions are based on a Unix command line installation but should be easy to apply to other systems.

Required tools:
* PHP
* MySQL
* Git

Not required tools:
* There are no dependencies so composer is not needed for this install.

Get started (put it anywhere on your system, you could put it in your home directory)
```
cd ~
```

Clone the repo
```
git clone git@github.com:dashboardq/jetriv.git
cd jetriv
```

Set up MySQL database
```
mysql -u admin -p --vertical
mysql> CREATE DATABASE jetriv;
mysql> exit;
```

Set up the environment config values (enter the appropriate MySQL data)
If you are developing, use the "dev" environment, if you are putting this on a website, use the "prod" environment.
```
cp .example.env.php .env.php
# Use whatever editing tool you feel comfortable using.
vim .env.php
# Add the database configuration, your Twitter app OAuth information, and change anywhere it says "example" in the file to the appropriate value.
```

Run the following command to generate the encryption key
```
php ao gen keys
```


Run the migrations
```
php ao mig init
php ao mig up
```

Server the site locally
```
php -S localhost:8080
```

Visit the site in your browser:
http://localhost:8080

Go to the login page and register a new user. You should now be logged into the account. The final step is to connect your twitter account. 


