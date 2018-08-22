SymfonyCleanTagsComposerPlugin
==============================

It was recently identified that Composer consumes high CPU + memory on packages that have a lot of historical tags. See [composer/composer#7577](https://github.com/composer/composer/issues/7577)

This means the composer+packagist infrastructure has a scalability issue: as time passes, the list of tags per packages grows, and the "Composer experience" degrades. This is significant for symfony/* today, and will become also a pain for any other packages over time.

symfony/flex solves this issue with a patch from @nicolas-grekas using a new extra parameter  `extra.symfony.require`: [symfony/flex#378](https://github.com/symfony/flex/pull/378) and [symfony/flex#409](https://github.com/symfony/flex/pull/409)

This project extracts this patch into a separete composer plugin for legacy projects (PHP5 and Symony 2/3)


| | Internal big project | [Sylius/Sylius-Standard](https://github.com/Sylius/Sylius-Standard) | [laravel/laravel](https://github.com/laravel/laravel)
| ----- | ----- | ---- | --- |
| `extra.symfony.require` | "2.8.*" | "^3.4\|^4.1" | "~4.0" |
| Before | Memory: 337.9MB (peak: 1582.09MB), time: 31.84s|  Memory: 384.84MB (peak: 1670.44MB), time: 28.11s | Memory: 265.09MB (peak: 417.44MB), time: 6.57s
| After | Memory: 183.05MB (peak: 286.56MB), time: 11.04s | Memory: 218.76MB (peak: 251.73MB), time: 5.02s| Memory: 210.17MB (peak: 236.37MB), time: 4.38s


Thank you @nicolasgrekas