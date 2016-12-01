# Changelog

## 0.0.6

- fix a bug that uri variables not get filled correctly

## 0.0.5

- fix npe which happens for parameters without type

## 0.0.4

- Use `php://input` instead of `php://stdin` for non blocking requests

## 0.0.3

- Make it possible to access files given in `$_FILES`

## 0.0.2

- Add response parameter of type `\Yarf\response\Response` which is the required return value of the `\Yarf\page\WebPage` methods

## 0.0.1

- Initial release
