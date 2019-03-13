# Simple REST API
A simple REST API developed with Lumen 5.8

## Server Requirements
* PHP >= 7.1.3
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension

## Installation & Setup
* Clone: `git clone git@github.com:kerick-jeff/simple-rest-api.git`
* Install dependencies with Composer: `composer install`
* Rename `.env.example` to `.env`. Make sure to set appropriate values for:
	* DB_DATABASE
	* DB_USERNAME
	* DB_PASSWORD
	* JWT_SECRET
* *Set application key*: Run `php artisan key:generate`
* *Database migrations*: Run `php artisan migrate`. It will create 4 tables in the database:
	* users
	* posts
	* api_keys
	* api_key_access_events

## Accessing the API
Every request to the API endpoints require an API key.

### Managing API Keys
Generate a new key using `php artisan apikey:generate {name}`.  The name argument is the name of your API key.  All new keys are active by default.

```bash
$ php artisan apikey:generate app1
  
// API key created
// Name: app1
// Key: 0ZdNlr7LrQocaqz74k6usQsOsqhqSIaUarSTf8mxnHuQVh9CvKAfpUy94VvBmFMq
```

Deactivate a key using `php artisan apikey:deactivate {name}`.

```bash
$ php artisan apikey:deactivate app1
  
// Deactivated key: app1
```

Activate a key using `php artisan apikey:activate {name}`.

```bash
$ php artisan apikey:activate app1
  
// Activated key: app1
```
    
Delete a key.  You'll be asked to confirm.  Keys are [soft-deleted] for record keeping.

```bash
$ php artisan apikey:delete app1
  
// Are you sure you want to delete API key 'app1'? (yes/no) [no]:
// > yes
  
// Deleted key: app1
```

List all keys.  The -D or --deleted flag includes deleted keys
    
```bash
$ php artisan apikey:list -D
 
// +----------+----+-------------+---------------------+------------------------------------------------------------------+
// | Name     | ID | Status      | Status Date         | Key                                                              |
// +----------+----+-------------+---------------------+------------------------------------------------------------------+
// | app1     | 5  | deleted     | 2019-03-12 13:54:51 | 0ZdNlr7LrQocaqz74k6usQsOsqhqSIaUarSTf8mxnHuQVh9CvKAfpUy94VvBmFMq |
// | app2     | 1  | deleted     | 2019-03-12 14:34:28 | KuKMQbgZPv0PRC6GqCMlDQ7fgdamsVY75FrQvHfoIbw4gBaG5UX0wfk6dugKxrtW |
// | app3     | 3  | deactivated | 2019-03-12 14:40:34 | IrDlc7rSCvUzpZpW8jfhWaH235vJAqFwyzVWpoD0SLGzOimA6hcwqMvy4Nz6Hntn |
// | app4     | 2  | active      | 2019-03-12 14:42:13 | KZEl4Y2HMuL013xvg6Teaa7zHPJhGy1TDhr2zWzlQCqTxqTzyPTcOV6fIQZVTIU3 |
// +----------+----+-------------+---------------------+------------------------------------------------------------------+
```

## Serve the API
Run `php -S localhost:<PORT> -t public`. Replace PORT with a desired port number. For example: `php -S localhost:8000 -t public`

## API Documentation
The API documentation is published [here](https://documenter.getpostman.com/view/3497755/S17jXChE)
