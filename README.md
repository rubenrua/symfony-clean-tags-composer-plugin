Symfony Clean Tags Composer Plugin
==================================

Motivation
----------

It was recently identified that Composer consumes high CPU + memory on packages that have a lot of historical tags. See [composer/composer#7577](https://github.com/composer/composer/issues/7577)

This means the composer+packagist infrastructure has a scalability issue: as time passes, the list of tags per packages grows, and the "Composer experience" degrades. This is significant for `symfony/*` today, and will become also a pain for any other packages over time.

symfony/flex solves this issue with a patch from @nicolas-grekas using a new extra parameter  `extra.symfony.require`: [symfony/flex#378](https://github.com/symfony/flex/pull/378) and [symfony/flex#409](https://github.com/symfony/flex/pull/409)

This project extracts this patch into a separete composer plugin for legacy projects (PHP5 and Symony 2/3)


| | Internal big project | [Sylius/Sylius-Standard](https://github.com/Sylius/Sylius-Standard) | [laravel/laravel](https://github.com/laravel/laravel)
| ----- | ----- | ---- | --- |
| `extra.symfony.require` | "2.8.*" | "^3.4\|^4.1" | "~4.0" |
| Before | Memory: 337.9MB (peak: 1582.09MB), time: 31.84s|  Memory: 384.84MB (peak: 1670.44MB), time: 28.11s | Memory: 265.09MB (peak: 417.44MB), time: 6.57s
| After | Memory: 183.05MB (peak: 286.56MB), time: 11.04s | Memory: 218.76MB (peak: 251.73MB), time: 5.02s| Memory: 210.17MB (peak: 236.37MB), time: 4.38s

Installation
------------

### Step 1: Profile application without the plugin

Open a command console, enter your project directory and execute the following command to profile the current memory and CPU time usage.

```
$ composer update --profile --ignore-platform-reqs --dry-run
....
[833.9MB/199.98s] Memory usage: 833.86MB (peak: 2811.34MB), time: 199.98s
```
Write down it to compare with the final step.

### Step 2: Download the Bundle

Execute the following command to installs the composer plugin:

```
$ composer require rubenrua/symfony-clean-tags-composer-plugin
```

or globally with:

```
$ composer global require rubenrua/symfony-clean-tags-composer-plugin
```

### Step 3: Configure the new extra parameter

Configure `extra.symfony.require` with the same symfony version constraints used in the application. For instance, if you are using symfony 2.8, execute the following command to modify the config composer section:

```
$ composer config extra.symfony.require 2.8.*
```

Also the `SYMFONY_REQUIRE` environment variable can be used instead of `extra.symfony.require`. See [`symfony/symfony` travis configuration for a example](https://github.com/symfony/symfony/commit/940ec8f2d5c562bc1b2424f67ab0cbd1f3c59e51#diff-354f30a63fb0907d4ad57269548329e3).

### Step 4: Profile application with the plugin

Finally profile the current memory and CPU time usage. Execute again the following command:

```
$ composer update --profile --ignore-platform-reqs --dry-run
....
[230.7MB/31.02s] Memory usage: 230.67MB (peak: 387.3MB), time: 31.02s
```

Please, feel free to comment the [issue #3](https://github.com/rubenrua/symfony-clean-tags-composer-plugin/issues/3) with your improvement.

Notes
-----

* MIT license.
* Thank you @nicolasgrekas
