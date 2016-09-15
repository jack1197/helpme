# Help Me! Class queueing
This web app is designed for classrooms where students work independantly, but often require regular help from tutors. From personal experience, this can get somewhat out of hand, so i designed a web app to help solve the problem

## To Microsoft Student Accelerator Markers:
This app uses php and a templating engine to generate pages, page templates can be found in /resources/views, but the best way to view is probably to just view-source on azure.

Some setup is required to make this run on a web server, this has already been done with my azure web app (http://jw-helpme.azurewebsites.net).
The main steps involve setting the \public directory as the root of the website, and running "composer install" in the \ directory to setup packages. Database properties can be configured in the .env file, an example is in .env.example or .env.deploy. after composer sets up the required packages, "php artisan key:generate" and "php artisan migrate" must be run to setup the encryption system and database respectively.

