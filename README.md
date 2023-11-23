<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

# donater.com.ua

## Local installation

- install make
- install docker
- run make commands
```bash
make env
make up
make folders
make install
make migrate
make front # build front
make vite # run dev
make node # node container for installing new npm packages
make php # php container for installing new composer packages
make clear # clear cache
```
- open http://localhost/
- open http://localhost/my, waiting autologin with test user
- for building new front you need run next commands
```bash
make bash # open container terminal inside docker
npm run build
```
or
```bash
make front
```
- clearing cache
```bash
make clear
```
