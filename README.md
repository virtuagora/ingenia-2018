# Welcome to Virtuagora 2.0 for Ingenia 2018

**WARNING: This repository is still under development for the version 2 of Virtuagora.**
If you need information or support please contact us! 
[@augusthur][2] & [@guillermocroppi][3]

Right now the project is *quite* unstable, but because it is still in development!
Want to know when it will be available? Follow us on Twitter  [@virtuagora][1]

---

### Installation
Open a terminal in the desire folder where you want to `git clone` the repository.
```bash
git clone https://github.com/virtuagora/virtuagora-core.git
```
Install the php dependencies using composer
```bash
php composer install
```
Install the npm dependencies
```bash
npm install
```
After installation, go to `app/settings.php` and check the settings to conect with the MySQL DB.
You need to create a MySQL database called `virtuagora_next`, and add to the `app/settings.php` the `username` and `password` to your localhost instance.
```php
return [
    'settings' => [
		(...)
        'eloquent' => [
            (...)
            'database' => 'virtuagora_next',
            'username' => 'my-username',
            'password' => 'my-secret-password',
            (...)
        ],
      (...)
];
```
Awesome, now start a php server
```bash
raccoon@trashcan: path/to/virtuagora-core$ cd public/
raccoon@trashcan: path/to/virtuagora-core/public$ php -S 0.0.0.0:8000
```
Open a browser and go to 
```
http://localhost:8000/install
```
If everything was set correctly, you should be ready to go!

---

### Development environment
To start running the backend, you just have to start a php server
```bash
raccoon@trashcan: path/to/virtuagora-core$ cd public/
raccoon@trashcan: path/to/virtuagora-core/public$ php -S 0.0.0.0:8000
```
If you're going to **create o modify new Vue.js components**, then run the webpack watch script by running this npm command
```bash
raccoon@trashcan: path/to/virtuagora-core$ npm run watch
```
If you want to build for production (with minification and more) then you have to build this components
```bash
raccoon@trashcan: path/to/virtuagora-core$ npm run build
```
[1]: https://twitter.com/virtuagora
[2]: https://www.twitter.com/augusthur
[3]: https://www.twitter.com/guillermocroppi