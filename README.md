# Task Management API

## Project Overview

This is a Task Management API developed using [CodeIgniter 4](https://codeigniter.com/), PHP, and MySQL. The API allows users to manage tasks, including CRUD operations, with authentication and JWT support. The project can be run both with Docker or manually by cloning the repository.

## Features

- **Task Management**: Create, read, update, and delete tasks.
- **User Authentication**: Register, login, and secure endpoints with JWT.
- **Database Integration**: MySQL database for task storage.
- **Postman Documentation**: Comprehensive API documentation.

## Getting Started

### With Docker

1. **Clone the Repository**

    ```bash
    git clone https://github.com/Balqeesqasem/task-management-api.git
    cd task-management-api
    ```

2. **Build and Start the Docker Containers**

    ```bash
    docker-compose up --build
    ```

3. **Access the Application**

    To access the API, you need to open an extra terminal tab and run the following commands:

    ```bash
    docker-compose exec app /bin/bash
    php spark serve --host=0.0.0.0
    ```

    After running the above commands, the application will be available at [http://localhost:8080](http://localhost:8080).


4. **Stop the Docker Containers**

    ```bash
    docker-compose down
    ```

### Without Docker

1. **Clone the Repository**

    ```bash
    git clone https://github.com/Balqeesqasem/task-management-api.git
    cd task-management-api
    ```

2. **Install Dependencies**

    Ensure you have [Composer](https://getcomposer.org/) installed.

    ```bash
    composer install
    ```

3. **Set Up the Environment**

    Create a `.env` file and set the following environment variables:

    ```
    DB_HOST=localhost
    DB_USER=mena
    DB_PASSWORD=mena123
    DB_NAME=task_management
    ```

4. **Run Migrations**

    ```bash
    php spark migrate
    ```

5. **Start the Application**

    ```bash
    php spark serve --host=0.0.0.0
    ```

    The application will be available at [http://localhost:8080](http://localhost:8080).

## Postman Documentation

You can find the Postman documentation for this API at [this link](https://documenter.getpostman.com/view/11123143/2sAXjSy8tX#36ccd50e-e9cf-469c-890b-71dab2001747).



## Contact

For any questions or feedback, please reach out to [balqeesmohammadq.95@gmail.com](balqeesmohammadq.95@gmail.com).

