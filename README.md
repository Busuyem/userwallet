# Task Management APIs

1. Clone the repository:
    ```bash
    git clone https://github.com/Busuyem/userwallet.git
    cd userwallet
    ```

2. Set up the environment file:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

3. Configure your database in `.env`.

4. Build the image:
    ```bash
        docker-compose up -d --build
    ```

5.  Start up the container:
    ```bash
        docker-compose up -d
    ```

6. Run migrations:
    ```bash
    docker-compose exec app php artisan migrate
    ```

7. Access the app:
    ```bash
        http://localhost:80
    ```

## API Documentation

### Authentication Endpoints

- Register a new user (params: username, email, password, password_confirmation):
    ```
    POST /api/register
    ```

- Login (params: username, password):
    ```
    POST /api/login
    ```

- Logout a user (auth token required):
    ```
    POST /api/logout
    ```

- Include the token in the Authorization header:
    ```
    Authorization: Bearer {token}
    ```

### Tasks Endpoints

- List all users:
    ```
    GET /api/users
    ```

- Transfer fund (params: from_user_id, to_user_id, amount):
    ```
    PUT /api/transfer
    ```

- Get user wallet balance:
    ```
    PUT /api//balance/{userId}
    ```



