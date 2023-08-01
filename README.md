# Sample Vue Application README

## Introduction
Sample Vue Application is a simple one-page web application developed using Laravel and Vue.js. The application provides users with basic features such as Authentication and User Account Management, along with a simple Blog Post feature. The following document will guide you on how to setup the project and run the application.

## Technologies Used
- Backend: Laravel
- Frontend: Vue.js
- Libraries: SweetAlert, Axios, Bootstrap
- Version Control: Git

## Features
### Authentication
- Register: Users can create an account by providing their first name, last name, address, email, and password.
- Login/Logout: Users can securely log in and out of their account.
- Forgot Password: Users can recover their account using the Forgot Password feature.

### User Account Management
- Change Password: Users can update their account password.
- Change Email: Users can update their account email.
- Update Profile: Users can update their first name, last name, and address.

### Blog Post
- CRUD Operations: Users can Create, Read, Update, and Delete blog posts with the following details: title, message, type (news, update, task).

## Installation & Setup
Before proceeding, ensure that you have Git, PHP, Composer, and Node.js installed on your machine.

1. Clone the repository
    ```
    git clone <repository-url>
    ```

2. Navigate to the project directory and then to the sample-api folder
    ```
    cd sample-vue-application/sample-api
    ```

3. Install PHP dependencies
    ```
    composer install
    ```

4. Install JavaScript dependencies
    ```
    npm install
    ```

5. Generate a new application key
    ```
    php artisan key:generate
    ```

6. Run the database migrations
    ```
    php artisan migrate
    ```

7. Seed the database
    ```
    php artisan db:seed
    ```

8. Start the backend server
    ```
    php artisan serve
    ```

9. Navigate back to the project directory for front end setup
    ```
    cd ..
    ```

10. Start the front end server
    ```
    npm run serve
    ```

The application should now be accessible at `http://localhost:8000`.

## Support
If you encounter any issues while using this application or have any suggestions, please open an issue on this GitHub repository.
