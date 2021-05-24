# Connect Mysql/mariadb database with PDO

To install the library, run the following command:

```sh
composer require lindomar/pdo-connect
```

To use the library, simply require the composer to autoload, invoke the class and call the method:

Copy the file ***config_database.json.exemple*** to ***/config/config_database.json***

Update with your SMTP server configuration data

```
{
	"HOSTNAME": "",
	"USERNAME": "",
	"PASSWORD": "",
	"DBNAME": ""
}	
```


### Developers
* [Lindomar] - Github
* [RainTPL] -  an easy template engine for PHP that enables designers and developers to work better together, it loads HTML template to separate the presentation from the logic.


License
----
MIT

[//]:#
[RainTPL]: <https://github.com/feulf/raintpl3>
[Lindomar]: <https://github.com/Lindomarc/pdo-connect>