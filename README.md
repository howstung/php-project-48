# Difference Calculator


### Hexlet tests and linter status:
[![Actions Status](https://github.com/howstung/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/howstung/php-project-48/actions)

### GitHub actions

[![PHP CI](https://github.com/howstung/php-project-48/actions/workflows/action-check.yml/badge.svg)](https://github.com/howstung/php-project-48/actions/workflows/action-check.yml)

### CodeClimate

[![Maintainability](https://api.codeclimate.com/v1/badges/ad3b057f3f15b4424d4b/maintainability)](https://codeclimate.com/github/howstung/php-project-48/maintainability)

[![Test Coverage](https://api.codeclimate.com/v1/badges/ad3b057f3f15b4424d4b/test_coverage)](https://codeclimate.com/github/howstung/php-project-48/test_coverage)


## About

Compares two files ( JSON or YAML ) and shows a difference. Can be used as a library and in CLI

## Setup

```bash
$ git clone https://github.com/howstung/php-project-48.git calc-diff

$ cd calc-diff

$ make install
```

## Usage

### As a library:

```php
<?php

use function Differ\Differ\genDiff;

$diff = genDiff($pathToFile1, $pathToFile2, $format);
print_r($diff);
```

### Usage in CLI:

```
Usage:
  gendiff [options] <firstFile> <secondFile>

Options:
  -h, --help                    Show help screen
  -v, --version                 Show version
  -f, --format <fmt>            Report format [default: stylish]
```


### Demo work:


<details>
<summary>Comparing two JSON files with stylish output</summary>


flat:

[![asciicast](https://asciinema.org/a/KPngcoX2jlQRk0a7paOxvP64T.svg)](https://asciinema.org/a/KPngcoX2jlQRk0a7paOxvP64T)

nested:

[![asciicast](https://asciinema.org/a/8Riq5hiR5qVEZiGbe48pTZLOF.svg)](https://asciinema.org/a/8Riq5hiR5qVEZiGbe48pTZLOF)

</details>


<details>
<summary>Comparing two YAML files with plain output</summary>


flat:

[![asciicast](https://asciinema.org/a/cp7fRcOwnPxLNUIbPoaaixDYm.svg)](https://asciinema.org/a/cp7fRcOwnPxLNUIbPoaaixDYm)

nested:

[![asciicast](https://asciinema.org/a/vZNJwdRSouJMGpqiKxnkfSV4i.svg)](https://asciinema.org/a/vZNJwdRSouJMGpqiKxnkfSV4i)

</details>