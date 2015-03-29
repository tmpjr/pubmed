### API wrapper library to interface with NCBI's PubMed Efetch Server

Getting started
---------------

### Installing via Composer

The recommended way to install PubMed is through [Composer](http://getcomposer.org).

1. Add ``tmpjr/pubmed`` as a dependency in your project's ``composer.json`` file:

        {
            "require": {
                "tmpjr/pubmed": "dev-master"
            }
        }

2. Download and install Composer:

        curl -s http://getcomposer.org/installer | php

3. Install your dependencies:

        php composer.phar install

4. Require Composer's autoloader

    Composer also prepares an autoload file that's capable of autoloading all of the classes in any of the libraries that it downloads. To use it, just add the following line to your code's bootstrap process:

        require 'vendor/autoload.php';

You can find out more on how to install Composer, configure autoloading, and other best-practices for defining dependencies at [getcomposer.org](http://getcomposer.org).

Basic Usage
-----------

```php
<?php

require 'vendor/autoload.php';

// Search By PMID
$api = new PubMed\PubMedId();
$article = $api->query(15221447);
print_r($article);

// Search By Term
$api = new PubMed\Term();
$api->setReturnMax(100); // set max returned articles, defaults to 10
$articles = $api->query('CFTR');
print_r($articles);

```

## License

Licensed under the open MIT license:

http://rem.mit-license.org

