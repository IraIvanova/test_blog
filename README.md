1. Go to https://github.com/IraIvanova/test_blog - click on the "clone or download" button and copy the link to the repository.
2. Open the command line, go to the directory in which the project will be installed. Enter the git clone reference on your project, and copy the repository to this folder:
git clone https://github.com/IraIvanova/test_blog.git
3. Go  in the command line to the project folder and execute the composer install command and tighten the whole project
You should fill  the fields, fill in the fields No. 3,4,5 the on another fields  just press enter:

Parameters:
1 database_host: 127.0.0.1
2. database_port: null
3. database_name: the name of the database
4. database_user: database user name
5. database_password: password to the database
6. mailer_transport: null
7. mailer_host: null
8. mailer_user: null
   9. mailer_password: null
  10. secret: ThisTokenIsNotSoSecretChangeIt


4. After the project is installed, enter the following in the console:
php bin /console doctrine:database:create - create a database
 
And then enter php bin /console doctrine:schema:create

5. Now we go to the server: in the console we write
 php bin/console server:run - start the server
6. And in the browser we go to the address: http://127.0.0.1:8000
Our project is ready!
