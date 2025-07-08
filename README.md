# AGENT.md PHP

![Test Status](https://github.com/raphaelstolt/agent-md-php/workflows/test/badge.svg)
[![Version](http://img.shields.io/packagist/v/stolt/agent-md-php.svg?style=flat)](https://packagist.org/packages/stolt/agent-md-php)
![Downloads](https://img.shields.io/packagist/dt/stolt/agent-md-php)
![PHP Version](https://img.shields.io/badge/php-8.1+-ff69b4.svg)
[![PDS Skeleton](https://img.shields.io/badge/pds-skeleton-blue.svg?style=flat)](https://github.com/php-pds/skeleton)

This library and its CLI supports you in creating, reading, and validating [AGENT.md](https://agent.md/) Markdown files via PHP.

## What's AGENT.md?

`AGENT.md` is a [proposed](https://agent.md/) universal configuration file format for software agents, LLM-powered tools, 
and AI assistants.

## Installation and usage

```bash
composer require stolt/agent-md-php
```

### Available CLI commands

The following list shows the currently available CLI commands to interact with an AGENT.md file.

```bash
php bin/agent-md list

agent-md-php 0.0.1

Available commands:
  info        Display parsed AGENT.md sections
  init        Create a boilerplate AGENT.md file
  migrate     Migrate legacy agent config files to AGENT.md
  validate    Validate the AGENT.md file
```

### Running tests

``` bash
composer test
```

### License

This library is licensed under the MIT license. Please see [LICENSE.md](LICENSE.md) for more details.

### Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for more details.

### Contributing

Please see [CONTRIBUTING.md](.github/CONTRIBUTING.md) for more details.
