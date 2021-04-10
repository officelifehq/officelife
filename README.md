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

- PHP 8.0 or higher,
- the PHP's intl extension,
- a database engine: preferrably mySQL or SQLite. PostegreSQL _should_ work, in theory.
- a http server: Nginx, Apache, Caddy, etc...
- We recommend [Forge](https://forge.laravel.com/) or [Ploi](https://ploi.io) to provision the servers needed to run OfficeLife. Heroku _should_ work too.

## Requirements for development

- Composer,
- Node and Yarn,
- A knowledge of how [Laravel](https://laravel.com), [VueJS](https://vuejs.org/) and [InertiaJS](https://inertiajs.com/) work. OfficeLife is a complex Laravel application, with a lot of queues and cron jobs.

## State of the project (as of April 10th, 2021)

We are not yet ready for production. We've been developing this project for more than 2 years now and we are close to launch a beta version. We expect to launch during the summer (sooner if possible, but... life happens).

* If you find any bugs, please file them by [creating a new issue](https://github.com/officelifehq/officelife/issues).
* Please don't submit new big ideas for now. We want to do a million other things with OfficeLife already. However, we seek feedback on the current features and how we could make them more useful.
* We have a documentation portal, that we slowly build: https://docs.officelife.io. It has a lot of content already, but we plan of adding much more before launching in beta.

## Core team

OfficeLife is made by [@djaiss](https://github.com/djaiss) and [@asbiin](https://github.com/asbiin).

We've made another project called [@monicahq](https://github.com/monicahq/monica).

## License

OfficeLife is open-sourced software licensed under [the BSD 3-Clause license](LICENSE). Don't be a jerk.
