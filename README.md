# php-project-lvl2
<a href="https://codeclimate.com/github/kudrvet/php-project-lvl2/maintainability"><img src="https://api.codeclimate.com/v1/badges/699710253060f1868b7f/maintainability" /></a> <a href="https://codeclimate.com/github/kudrvet/php-project-lvl2/test_coverage"><img src="https://api.codeclimate.com/v1/badges/699710253060f1868b7f/test_coverage" /></a> ![PHP CI](https://github.com/kudrvet/php-project-lvl2/workflows/PHP%20CI/badge.svg?branch=master)
## Setup
via Composer:
1. global installation to use CLI - utilite.

```sh
 composer global require vitaliy/php-project-lvl2
 ```
 2. local installation to use as library.
 ```sh
 composer require vitaliy/php-project-lvl2
 ```
## Description

The gendiff is cli utilite. It show differ bettween two files (Json and Yaml format supported).  
The differ can be shown in different formats.  
Support [ -- pretty(by default)], [-- plain] and [--json] output formatters.

 ## Usage
 
Usage:

   gendiff  (-h|--help)  
   gendiff  (-v|--version)  
   gendiff [--format <fmt>] \<firstFile\> \<secondFile\>

Options:

  -h --help                     Show this screen  
  -v --version                  Show version  
  --format <fmt>                Report format [default: pretty]  
  
  ## Examples
  
### Pretty and plain formatters
[![asciicast](https://asciinema.org/a/h0Nuqcixju6naJnMTda4J4KkT.svg)](https://asciinema.org/a/h0Nuqcixju6naJnMTda4J4KkT)

### Json formatter
[![asciicast](https://asciinema.org/a/meTCbdwrdUrcBE8iAx4LDOtQt.svg)](https://asciinema.org/a/meTCbdwrdUrcBE8iAx4LDOtQt)
  
  
