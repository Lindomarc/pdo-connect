# Notification library via email using phpMailer

This library has the function of sending e-mail using a phpmailer library. Doing this in an uncomplicated way is essential for any system.

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
* [phpMailer] - Lib to send E-mail

License
----
MIT

[//]:#
[phpMailer]: <https://github.com/PHPMailer/PHPMailer>
[Lindomar]: <https://github.com/Lindomarc/pdo-connect>