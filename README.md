# USSD-Project|

This Is The submission of the USSD Min Projects Codes

Overview of the "Investors Contacts Records Keeping System"
The "Investors Contacts Records Keeping System" is a PHP-based web application designed to manage investor contact information and related details effectively. This system allows users to register, add, update, remove, and view investor data, as well as manage information about investors' businesses. It is primarily designed for use within organizations that need to maintain detailed records of their investors and their investments.
Key Features
•	User Registration and Management: Users can register with their phone number and manage their accounts.
•	Investor Management: Add, update, and remove investor details, including contact information and investment shares.
•	Business Management: Add and list businesses associated with investors.
•	The Incoming SMS Part: Here you will be asked to enter the user's credentials whom you want to register to use the system but you in form of sending a message.
Technology Stack
•	PHP: Server-side scripting language used for backend logic.
•	MySQL: Database management system used for storing all user and investor data.
•	Apache: Web server for serving the PHP application.
Database Structure
The application relies on at least two primary tables:
1.	users_information: Stores user data, including phone numbers and hashed passwords.
2.	members_contacts_info: Stores information about investors, such as phone numbers, full names, and shares.
3.	Investors_businesses:This is a table to store data about businesses associated with investors.
Running the Application
To run this application, you will need a local server environment that supports PHP and MySQL. One common setup is using XAMPP, which includes Apache server, MySQL, PHP, and Perl.
Setup Instructions
1.	Install XAMPP: Download and install XAMPP from Apache Friends.
2.	Start Apache and MySQL: Open the XAMPP Control Panel and start both the Apache and MySQL services.
3.	Database Setup:
•	Access phpMyAdmin from your browser (usually available at http://localhost/phpmyadmin).
•	Create a new database for the project which you can call (investorscontactsdb) as mine is called.
•	Import the SQL schema provided with your project files, or manually create the required tables.
4.	Configuration:
•	Adjust the database connection settings in your PHP files to match your local environment (usually found in a config file or within the PHP files that instantiate a database connection).
5.	Access the Application:
Open a web browser and go to http://localhost/your_project_directory to start using the application.
Additional Notes
•	Ensure all PHP files are correctly configured to connect to your MySQL database.
•	Review and handle any PHP warnings or errors that may arise during the initial setup or during typical application usage.
•	Implement proper error handling and security measures, especially for production environments.
*Note:*
• This is the link to view the submission of the project:https://github.com/Glingo123/USSD-Project-Codes
• So In the files submitted I have included all needed files to run the webbased project part, USSD Based part and the Incoming SMS Part.
• And Also I have given the Database in the Files submitted.
