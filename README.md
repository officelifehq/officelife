<div align="center">

[![Build and test](https://img.shields.io/github/workflow/status/officelifehq/officelife/Build%20and%20test/master)](https://github.com/officelifehq/officelife/actions?query=workflow%3A%22Build+and+test%22)
[![Lines of Code](https://img.shields.io/tokei/lines/github/officelifehq/officelife)](https://sonarcloud.io/dashboard?id=officelife)
[![Code coverage](https://img.shields.io/sonar/coverage/officelife?server=https%3A%2F%2Fsonarcloud.io)](https://sonarcloud.io/project/activity?custom_metrics=coverage&amp;graph=custom&amp;id=officelife)
[![License](https://img.shields.io/github/license/officelifehq/officelife)](https://opensource.org/licenses/BSD-3-Clause)

</div>

# OfficeLife

<div align="center">

  ![Logo](docs/img/logo.png)

</div>

## Requirements for hosting the software

- PHP 7.4 or higher,
- the PHP's intl extension,
- a database engine: mySQL, PostegreSQL SQLite,
- a http server: Nginx, Apache, Caddy, etc
- We recommend [Forge](https://forge.laravel.com/) or [Ploi](https://ploi.io) to provision the servers needed to run OfficeLife.

## Requirements for development

- Composer,
- Node and Yarn,
- A knowledge of how [Laravel](https://laravel.com), [VueJS](https://vuejs.org/) and [InertiaJS](https://inertiajs.com/) work. OfficeLife is a complex Laravel application, with a lot of queues and cron jobs.

## License

OfficeLife is open-sourced software licensed under [the BSD 3-Clause license](LICENSE). Don't be a jerk.
