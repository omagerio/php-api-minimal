# php-api-minimal
## Overview
A minimal PHP API server to be used as a starting point. Includes RedBeanORM (optional).

## Usage
- Clone or copy the repository
- Copy the "api" folder inside a folder reachable by the internet
- Edit the api.php script by defining your custom logic. $request contains the request's parameters. $response is the object returned to the clients.
- (Optional) Edit the `inc/config.php` script by adding your database connection. Update the `env.php` script accordingly (copy from `env.php.example`)
- Send GET or POST requests to yourdomain.com/api/api.php

## Client Example
Take a look at client-examples/javascript.html to see an example of JavaScript usage.