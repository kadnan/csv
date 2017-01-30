##**Code Test**

Once the files are copied, please first do a "composer dumpautoload".

####Modifications

#####Models
*   **Student**
    I deleted the course_id field, because a student should be able to attend more than one single course. That's why I created a pivot table called course_student.
    
*   **Course**
    I deleted the university field, favoring a university_id field, in order to establish a direct connection between a Course and a University.
    
*   **University**

    New model, used to replace the old university_id field in the Course model. Appropriate to have a centralized place to create new Universities.


#####Relationships
*   **Student**
    Changed the One to Many relationship with a Course to a Many To Many because a student can attend several courses, not just one.
    
*   **Course**
    Changed the relationship with Student to favor a Many to Many relationship. Also, added a One to Many relationship to the University model to support the change made in the changed migration.
    
*   **University**
    New model, has a One to Many relationship with the Course.

    
#####Seeds
Modified the seeds to handle the new relationships structure. Also,fixed a bug regarding rand() on address_id and course_id, because when the random was 0, it was throwing an error because the related model couldn't be found.

#####API
All routes are secured with token authentication. This is why you will see in the User model that I added an api_token field. Also, in order to test this in the frontend I manually logged a user in the ExportController.

#####Frontend
I used VueJS to make all interactions. You will find all the necessary files in resources/assets/js. The only third party package that  I've used was for the modal alert (SweetAlert).

#####Tests
You will see in the unit folder some tests that support all the export types needed. Also, I created model factories to ease the test setup.

#####Extras
I created a command called "csv:delete-old" that deletes all files from yesterday. This was designed in order to delete all old files that don't belong in the server once the file is downloaded.
It should run on a server using a daily cron.


