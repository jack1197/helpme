# Help Me! Class queueing
This web app is designed for classrooms where students work independantly, but often require regular help from tutors. From personal experience, this can get somewhat out of hand, so i designed a web app to help solve the problem

## To Microsoft Student Accelerator Markers:
This app uses php and a templating engine to generate pages, page templates can be found in /resources/views, but the best way to view is probably to just view-source on azure.

Some setup is required to make this run on a web server, this has already been done with my azure web app (http://jw-helpme.azurewebsites.net).
The main steps involve setting the \public directory as the root of the website, and running "composer install" in the \ directory to setup packages. Database properties can be configured in the .env file, an example is in .env.example or .env.deploy. after composer sets up the required packages, "php artisan key:generate" and "php artisan migrate" must be run to setup the encryption system and database respectively.

##Usage

Go to the tutor page to create a new class, give it a name and classroom. When "create class" is pressed, the class session is created, and the student join code is displayed.

Students can join by typing the join code into the student page, along with their name and desk numbers for quick identification

when a student needs help, they can type their reason into the 'help reason' box, and click 'Help Me!' (They can cancel this request with "Oh, Nevermind...")

Students who request help will show up in the order that they requested it on the tutors screen, who can then dismiss them after their issues have been resolved. 

Students or tutors can leave the class at any time, and tutors can delete the class at anytime, which automatically disconnects all students. Additional tutors can join a class session by using the tutor code in the tutor settings box. 
