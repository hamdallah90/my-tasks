# My Tasks




## Requirements

- Node.js >= 20.x
- npm >= 6.x
- PHP >= 8.3
- Mysql >= 8.x
- Composer
- A web browser
- Git

## Installation

1. Clone the repository:
    ```sh
    git clone https://github.com/hamdallah90/my-tasks.git
    ```
2. Navigate to the project directory:
    ```sh
    cd my-tasks
    ```
3. Install php dependencies:
    ```sh
    composer install
    ```

4. Install the dependencies:
    ```sh
    npm install
    ```

5. Set up the database:
    ```sh
    mysql -u root -p
    CREATE DATABASE my_tasks;
    exit
    ```

6. Configure the environment variables:
    ```sh
    cp .env.example .env
    ```

7. Update the `.env` file with your database credentials:
    ```
    DB_DATABASE=my_tasks
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

8. Run the database migrations:
    ```sh
    php artisan migrate
    ```

## Alternative using Docker

1. Ensure you have Docker and Docker Compose installed on your machine.

2. Build the Docker containers:
    ```sh
    docker-compose build
    ```

3. Start the Docker containers:
    ```sh
    docker-compose up -d
    ```

4. Set up the database:
    ```sh
    docker-compose exec app php artisan migrate
    ```

5. Open your browser and navigate to `http://localhost:9000` to use the app.

6. To stop the Docker containers:
    ```sh
    docker-compose down
    ```

## Usage

1. Build the application:
    ```sh
    npm run build
    ```

2. Run the application:
    ```sh
    php artisan serve
    ```
3. Open your browser and navigate to `http://localhost:8000` to use the app.


## Contributing

1. Fork the repository.
2. Create a new branch (`git checkout -b feature-branch`).
3. Make your changes.
4. Commit your changes (`git commit -m 'Add some feature'`).
5. Push to the branch (`git push origin feature-branch`).
6. Open a pull request.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.