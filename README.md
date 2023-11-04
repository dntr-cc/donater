<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">

[![Github actions Build](https://github.com/setnemo/laravel-template/workflows/dev/badge.svg)](//github.com/setnemo/laravel-template/actions)

</p>

## Laravel template

Modern php application with automatically deploy via ssh by GitHub Actions

## Steps for using

1. [Fork](https://github.com/setnemo/laravel-template/fork) this project and clone it
2. `cp -r laravel-template YOUR_NAME && cd YOUR_NAME/ && rm -rf .git`
3. Use `make up` for run docker containers
4. Run `make bash` for open docker container with application
5. Run `composer update`
6. Create new repo on GitHub, run `git init`, add remote, push it to GitHub
7. [Generate](https://docs.github.com/en/authentication/connecting-to-github-with-ssh/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent) new SSH key for access to server
8. If this is the README of your fork, click [this link](../../settings/secrets/actions) to go to the "Secrets" page.
9. Create new secret hash for waiting finish of current deploy process, create .env and update DEV_HASH (using by [app/Http/Middleware/Development.php](app/Http/Middleware/Development.php))
10. Add new secrets:
    - DEPLOY_URL: url of your website
    - DEV_HASH: secret dev hash for access to deploy route
    - DEPLOY_HOST: server's IP for SSH connection
    - DEPLOY_PATH: path to website on server, `/var/www/html/something`
    - DEPLOY_PORT: server's port for SSH connection
    - DEPLOY_USER: server's username for SSH connection
    - DEPLOY_KEY_PASS: server's SSH public key passphrase
11. Push new code to main branch and check GitHub Actions status 

## How it's work

Main idea is to create basic deploy (via rsync) to remote server over SSH with waiting finish of current deploy process.
GitHub Action is very simple, you can check [.github/workflows/dev.yml](.github/workflows/dev.yml). Step 'Check previous deploy'
call specific url /deploy, which has response status. If we have deploy process, script waiting finish of process. For deploy
we use burnett01/rsync-deployments@5.2, which use rsync for pushing files to remote server, where supervisor has [cron tak](supervisor/deploy.conf)
This cron task run every 60 sec and apply migrations, remove deploy pid file, etc.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
