# php-api-minimal
## Overview
A minimal PHP API server to be used as a starting point.

## Usage
- Clone or copy the repository
- Copy the "api" folder inside a folder reachable by the internet
- Create new handlers inside the "handlers" folder (you can start from the main.php script). Script inside this folder are included automatically. Define your custom logic here: `$request` contains the request's parameters. `$response` is the object returned to the clients
- (Optional) Edit the `inc/config.php` script by adding your database connection
- Clone the `.env.example` file to `.env` and add the name of your environment

## Request Example
```json
{
    "action": "your_action_here",
    "parameters": {
        "par1": "val1",
        "par2": "val2"
    }
}
```
Payload can be sent:
- into the POST BODY (as is)
- into the query string for GET requests with the name $payload (example.com/api/index.php?payload={...})

## Client Example
Take a look at `client-examples/javascript.html` to see an example of JavaScript usage.

### ApiResponse class
The ApiResponse object has the following fields:
- `array() $results`: your api results
- `array() $errors`: your api errors
- `int $timestamp`: timestamp of the response