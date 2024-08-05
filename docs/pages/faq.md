## FAQ

---

1. **The application is very slow:**
   - If using WSL, try cloning the project within WSL and not running it from Windows.

2. **I can't edit the project from my editor, it gives permission errors:**
   - Ensure the user specified in the `APP_USER` variable is the same one you use on your machine. You can verify this with the command `id -u`.

3. **Why use PCOV instead of xDebug:**
   - PCOV is up to twice as fast as xDebug for generating code coverage reports.

4. **Image preview is not loading / stuck in loading (New 🔥)**
   - Please, make sure you have `APP_URL` correctly set, including the port where your app is running. ex: `APP_URL=http://localhost:8001`

5. **Seeders throwing a error related to roles and permissions (New 🔥)**
   - It is probably that you see an error when trying to run seeders if you're pulling changes from stage 2. If this is the case, please, make sure to clear cache before running the seeders. The package Laravel Permissions uses cache to improve response times so this can lead into errors trying to load new roles.

   - You can read more about this in their documentation [Laravel Permissions](https://spatie.be/docs/laravel-permission/v6/advanced-usage/cache).

6. **Docker Considerations with New Features (New 🔥)**
   - With the release of each stage, it is recommended to stop all containers (`docker compose down`) and rebuild them (`docker compose build && docker compose up -d`).