# BTCHook

Setup:
1. Upload Files to host.
2. Rename `config.example.php` to `config.php`.
3. Set information in config file: `$webhook` url, from discord, and mysql information.
4. Visit your host, path to `/installer.php`, run the installer.
5. Delete `installer.php`
6. Set a cronjob on `Listener.php`: `*/5 * * * * php /path/to/Listener.php >/dev/null 2>&1`.
7. You're setup!
