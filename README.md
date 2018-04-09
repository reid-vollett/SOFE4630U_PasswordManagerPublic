# Cloud Computing Password Manager
```
Authors:
Edward King 100553812
Reid Vollett 100396565
Evan Urquhart 100554128
```
- An example of this project can be found at http://passwordmanager-env.us-east-2.elasticbeanstalk.com/

- This will also get rid of any issues while running the project by testing it at the link above

- This project was developed on a Windows environment. 

- The project uses [Materialize CSS](http://materializecss.com) for CSS webpage formatting.

# Table of Contents

- [Windows (Local Environment Setup)](#windows-setup)
- [Extra Information](#extra-information)

# Windows Setup (Local Setup Only)

Note: This setup is only required if you wish to run it locally, otherwise access the site hosted on AWS

- Install [XAMPP](https://www.apachefriends.org/download.html)
- Install [Git](https://git-scm.com/download/win)
- Install [Composer](https://getcomposer.org/download/)
- Open XAMPP, and configure the ports as follows for Apache and MySQL:
```
Apache Ports: 80, 443
MySQL  Ports: 3306
```
- Clone this GitHub repo into your XAMPP htdocs folder (ie. C:/XAMPP/htdocs/SOFE4630U_PasswordManager ) 
- Open a terminal and run:
```
composer install
```
- To access the project homepage, open a browser and navigate to http://localhost/SOFE4630U_PasswordManager/index.php.

# Extra Information

- CSS used for webpage development can be found [Here](http://materializecss.com)
