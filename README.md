# Premiefier

Premiefier helps you to keep up to date with movie premieres. It will notify you about the upcoming premiere via email three days before. (Notification module is not yet fully implemented.)

## Directories

* _/application_—Back-end of the application powered by [Silex](https://github.com/silexphp/Silex) framework. [Eloquent](https://laravel.com/docs/eloquent) is used as ORM of choice, 'cause it's lighter and simpler to set up than Doctrine.
* _/gulpfile.js_—Gulp workflow extracted from [Frontkit](https://github.com/lamberski/frontkit) (Scaffolding & Gulp workflow for web projects.)
* _/source_—Front-end source files. Check out [Frontkit documentation](https://github.com/lamberski/frontkit/blob/master/README.md) to learn more about structure and files.
* _/public_—Public folder where all requests go. Also all static assets are put there during compilation.

## Front-End

Gulp is used to compile, minify & optimize front-end assets. Whole workflow is extracted from [Frontkit](https://github.com/lamberski/frontkit) (scaffolding & Gulp workflow for web projects). To compile the assets run:

```bash
gulp watch # This task will watch for changes in files and recompile them as needed.
gulp build # Recreate whole project.
gulp # Equivalent of 'gulp build && gulp watch'.
```

Keep in mind that in order to run above commands you need to have `npm`, `gulp` and `bower` installed.  Also, run `npm install && bower install` first to install all dependencies.

## Configuration

Configuration of the app—including database path, API keys, SMTP setup—is based on environment variables to remove all confidential data from the repository. [_config.php_](application/config.php) file is located in _/application_ directory. Below is list of all required variables:

| Key | Description |
| :--- | :--- |
| `DEBUG`   | Whether application should be run in debug mode. |
| `API_KEY` | Rotten Tomatoes API key. You can get one by [registering the app](http://developer.rottentomatoes.com/) on their site. |
| `DB_PATH` | Path to _*.sqlite_ file with the database. |
| `MAIL_*`  | SMTP-related configuration to set Swift Mailer up. You can find more information in [Silex documentation](http://silex.sensiolabs.org/doc/providers/swiftmailer.html). |

## Database

Site is using SQLite to store data. Schema of the database is located in [_schema.sql_](schema.sql).

## Deployment

Premiefier has deployment mechanism already set up. It's using simple yet powerful [Deployer](http://deployer.org/). Servers configuration is loaded from external YAML file _deploy.yml_ (which is not included to the repository). Learn more about serverList() in [Deployer documentation](http://deployer.org/docs/servers).

## License

(MIT License)

Copyright (C) 2016 Maciej Lamberski

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
