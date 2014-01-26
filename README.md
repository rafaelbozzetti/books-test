# Books-test

Aplicação books-test implementado em Zend Framework 2


## Instalação
``$ git clone git@github.com:rafaelbozzetti/books-test.git /var/www/books-test``

``$ cd /var/www/books-test/``

``$ curl -sS https://getcomposer.org/installer | php``

``$ php composer.phar install``


### Configuração da base de dados

Criação das bases de dados: tallerbooks e tallerbooks_test

``$ mysql -u user -p < module/Books/data/database.sql``


### Estrutura da base, e usuário teste

``> $ mysql -u user -p < module/Books/data/schema.sql``


### Configuração do Apache
```
<VirtualHost *:80>
    ServerName books-test.local
    DocumentRoot /var/www/books-test/public
    <Directory /var/www/books-test/public>
        DirectoryIndex index.php
        AllowOverride All
        Order allow,deny
        Allow from all
    </Directory>
 </VirtualHost>
``` 

Incluir o Alias no /etc/hosts

``127.0.0.1   books-test.local``

Restart o apache

``` /etc/init.d/apache2 restart ```


### Testes unitários


##### Módulo Books
```
PHPUnit 3.7.28 by Sebastian Bergmann.
Configuration read from /home/rafael/www/books-test/module/Books/tests/phpunit.xml
..............
Time: 1.54 seconds, Memory: 10.25Mb
OK (14 tests, 26 assertions)
Generating code coverage report in Clover XML format ... done
Generating code coverage report in HTML format ... done
```

##### Módulo Admin

comming soon...