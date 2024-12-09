<p align="center"><img src="https://i.imgur.com/HTWbXeA.png" width="400" alt="Application Logo"></p>

## About the Project

This project is a web-based application that features bulk SMS sending to a selected contact.

This features:
- Built using Laravel framework version 10 and Blade template.
- CRUD functionality for the contacts.
- Utilize TextBee, an open-source Android-based SMS gateway, to send SMS to multiple numbers via API.

### About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects.

### About [TextBee](https://textbee.dev/)

A free open-source Android-based SMS Gateway which provides you with all the features you need to effectively manage your SMS communications.

## Installation

Setting up your development environment on your local machine:

- Clone this repository:
   ```bash
   git clone https://github.com/nicamaezurbano/laravel-and-blade-sending-bulk-sms.git
   ```
- Change to the project directory:
   ```bash
   cd laravel-and-blade-sending-bulk-sms
   ```
- Install the project dependencies:
   ```bash
   composer install
   npm install
   ```
- Copy the .env.example file to .env and configure your environment variables:
   ```bash
   copy .env.example .env
   ```
- Generate an application key:
   ```bash
   php artisan key:generate
   ```
- Create a symbolic link for the storage directory:
   ```bash
   php artisan storage:link
   ```
- Migrate the database:
    ```bash
    php artisan migrate
    ```
- Run the Vite development server, which is useful while developing locally:
    ```bash
    npm run dev
    ```
- Start the development server:
    ```bash
    php artisan serve
    ```
- Open the application in a browser on http://127.0.0.1:8000
