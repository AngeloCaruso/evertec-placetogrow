<p align="center"><a href="#" target="_blank"><img src="https://www.evertecinc.com/wp-content/uploads/2020/07/logo-evertec-footer.png" width="300" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/AngeloCaruso/evertec-placetogrow/actions/workflows/build.yml"><img src="https://github.com/AngeloCaruso/evertec-placetogrow/actions/workflows/build.yml/badge.svg" alt="Build Status"></a>

# Bootcamp PHP 2024

## Microsites Admin

This project is developed for the Evertec PHP Bootcamp 2024. Upon accessing the application, you will find three main modules, with the microsites administration module being the core focus of this development.

## Technologies Used
- PHP with Laravel
- Livewire with Filament components
- Tailwind CSS
- MySQL Database
- Docker and Docker Compose
- PHPUnit
- PCOV for code coverage

## Installation

### TL;DR

1. Copy `.env` file:
   ```bash
   cp .env.example .env
   ```
2. Set required environment variables in `.env` following [Required Variables](#required-environment-variables).
3. For Docker Compose:
   ```bash
   docker compose build && docker compose up -d
   ```
4. For manual installation, follow the steps in the [Manual Installation](#manual-installation) section.

### Pre Installation Config

The development environment for this application has been set up using Docker and Docker Compose. Therefore, it is recommended to use these tools to run the project as the configuration to launch the site is automated for a better experience. At the end of these instructions, you will find the Dockerfile configurations in case you want to run the project manually.

Before running the project, it is necessary to create the `.env` file using the following command:

```bash
cp .env.example .env
```

Once this is done, you will need to configure the necessary variables for the project.

### Required Environment Variables
- `APP_NAME`: This variable will be the name of the site for project purposes. If using Docker installation, this variable is MANDATORY and must not contain spaces as it will be used for container names.
- `APP_URL`: This variable is MANDATORY. It should be the exact base URL (with port) where the file is running.
- `APP_PORT`: Port where the application runs. If changed from the default, it should also be updated in the `APP_URL`.
- `APP_USER`: This variable is MANDATORY. It is crucial to set this variable when using Docker configuration as it specifies the user that will manage files within the container. To find the number to put here, run the command `id -u` on your host machine and put the result in the variable.
- `APP_DB_PORT`: Port where the database container runs. If you want to connect to the database using an external client, use this port.
- `APP_PHPMYADMIN_PORT`: Port where the PHPMyAdmin server runs. This variable usually does not need changing.
- `APP_ADMIN_EMAIL`: Default admin user email.
- `APP_ADMIN_PASSWORD`: Default admin user password.
- `DB_CONNECTION`: It is recommended not to change these variables if running the project with Docker. Otherwise, adapt them to your database credentials.

### Installation with Docker Compose

The project contains a folder named `docker` at its root, which includes all the configurations needed by the `docker-compose.yml` file also located at the root.

The `docker-compose.yml` file defines four main service containers:
- **nginx**: A proxy server to route the application to the port configured in `.env`. The configuration file is located at `docker/nginx/sites-available/default`.
- **php**: A PHP container with all necessary dependencies and extensions installed for the project to function.
- **database**: MySQL database server. This service includes environment variables used to create the root database user and references the configuration file used by MySQL for internal settings.
- **phpmyadmin**: PHPMyAdmin interface connected to the database container using the service name. By default, access to this service is disabled; to use the interface, navigate to `APP_URL/phpmyadmin`.

All services are interconnected using a network with a bridge driver and a general volume. This definition is located at the end of the file.

To start the project, use the following command:

```bash
docker compose build && docker compose up -d
```

This command will first install the dependencies of each service and execute their respective configuration files. During this process, the PHP service will invoke its Dockerfile, which contains a set of instructions to install everything necessary for our server. In general, this Dockerfile will:
1. Use the `php-fpm-alpine` container as a base in version 8.2.
2. Install all necessary PHP extensions.
3. Install the PCOV extension for code coverage.
4. Install Node.js and npm for asset compilation.
5. Install Composer via the official URL.
6. Copy the entrypoint file, which is a post-installation executable that completes the app setup by running Laravel commands.
7. Create the user defined in `.env` as `APP_USER` and grant permissions to the project folders.

Once this is done, the entrypoint will proceed with the post-installation commands:
1. Install required Composer dependencies.
2. Verify that the `.env` file exists and alert if not.
3. Run Laravel migrations.
4. Run Laravel seeders.
5. Configure the local storage of the app.
6. Install Node.js dependencies with npm.
7. Resume execution by launching the server in the background.

At this point, the site should be running correctly on the defined port, initially displaying the admin login screen.

To log in, use the credentials set in the `.env` file with the variables `APP_ADMIN_EMAIL` and `APP_ADMIN_PASSWORD`. This user is automatically created when running the seeders.

### Manual installation

If you prefer to run the project manually, follow the steps that the Dockerfile executes automatically:

1. **Set Up Environment:**
   - Ensure you have PHP 8.2, Node.js, npm, and Composer installed on your machine.
   - Install the necessary PHP extensions: pdo_mysql, mbstring, tokenizer, xml, ctype, json, bcmath, and pcntl.
   - Install PCOV or xDebug for code coverage.
   - Edit the database connection in .env file.

2. **Clone the Repository:**
   ```bash
   git clone https://github.com/your-repository/microsites-admin.git
   cd microsites-admin
   ```

3. **Copy .env File:**
   ```bash
   cp .env.example .env
   ```

4. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

5. **Install Node.js Dependencies:**
   ```bash
   npm install
   ```

6. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

7. **Run Migrations and Seeders:**
   ```bash
   php artisan migrate --seed
   ```

8. **Link Storage:**
   ```bash
   php artisan storage:link
   ```

9. **Compile Assets:**
   ```bash
   npm run dev
   ```

10. **Start the Application:**
    ```bash
    php artisan serve
    ```

*Disclaimer: The project was developed on a Windows machine using Ubuntu via WSL. There might be issues on other machines as these cases have not been tested.*

## FAQ

1. **The application is very slow:**
   - If using WSL, try cloning the project within WSL and not running it from Windows.

2. **I can't edit the project from my editor, it gives permission errors:**
   - Ensure the user specified in the `APP_USER` variable is the same one you use on your machine. You can verify this with the command `id -u`.

3. **Why use PCOV instead of xDebug:**
   - PCOV is up to twice as fast as xDebug for generating code coverage reports.
