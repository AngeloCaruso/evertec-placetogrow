## FAQ

---

1. **The application is very slow:**
   - If using WSL, try cloning the project within WSL and not running it from Windows.

2. **I can't edit the project from my editor, it gives permission errors:**
   - Ensure the user specified in the `APP_USER` variable is the same one you use on your machine. You can verify this with the command `id -u`.

3. **Why use PCOV instead of xDebug:**
   - PCOV is up to twice as fast as xDebug for generating code coverage reports.

4. **Image preview is not loading / stuck in loading**
   - Please, make sure you have `APP_URL` correctly set, including the port where your app is running. ex: `APP_URL=http://localhost:8001`