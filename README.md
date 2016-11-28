# Yet another routing framework

[![Build Status](https://travis-ci.org/jannisfink/yarf.svg?branch=master)](https://travis-ci.org/jannisfink/yarf) [![Coverage Status](https://coveralls.io/repos/github/jannisfink/yarf/badge.svg?branch=master)](https://coveralls.io/github/jannisfink/yarf?branch=master)

[![Latest Stable Version](https://poser.pugx.org/yarf/yarf/v/stable)](https://packagist.org/packages/yarf/yarf) [![Latest Unstable Version](https://poser.pugx.org/yarf/yarf/v/unstable)](https://packagist.org/packages/yarf/yarf)

A simple but powerful routing framework for PHP.

# Basic usage

To create a new router responding to the root url of your server, simply do the following:

```php
$router = new \Yarf\Router();

$classMap = [
  "" => IndexPage::class
];

$router->route($classMap);
```

## Page types

There are three basic page types. `HtmlPage`, `JsonPage` and `TextPage`. Each of them is subclassable and meant to return either html, json or plain text. The content type for the http response is set appropriately.

# Features

## Rest support

Create a restful service with this framework is dead simple. Here is a quick example:

```php

use Yarf\response\Response;

class UserApi extends Yarf\page\JsonPage {

  private $user;
  
  public function initialize() {
    $this->user = ...;
  }

  public function get(Response $response) {
    $response->result($this->user);
    return $response;
  }
  
  public function post(Response $response) {
    $this->user->update(...);
    $response->result($this->user);
    return $response;
  }
  
  public function delete(Response $response) {
    $response->result($this->user->delete());
    return $response;
  }

}
```

## URI variables

When configuring your routes, route elements using a scheme like `{variable}` will be interpreted as variable uri parts.

```php

use Yarf\response\Response;

class UserApi extents \Yarf\page\JsonPage {

  public function get(Response $response, $userId) {
    $response->result(UserCache::get($userId));
    return $response;
  }

}

$classMap = [
  "api" => [
    "user" => [
      "{userId}" => UserApi::class
    ]
  ]
]

$router = new Yarf\Router();
$router->route($classMap);
```
