<?php
// Copyright 2016 Jannis Fink
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//     http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.

namespace Yarf\wrapper;

use Yarf\exc\IllegalArgumentException;

/**
 * Class Server
 *
 * Singleton to access all parameters stored in $_SERVER
 *
 * @package Yarf\wrapper
 */
class Server implements WrapObject {

  /**
   * all possible fields
   */
  const REQUEST_URI = 'REQUEST_URI';
  const SERVER_PROTOCOL = 'SERVER_PROTOCOL';

  /**
   * @var array the default values
   */
  private static $default;

  /**
   * Function to access a key in a wrapped object.
   *
   * @param $key
   * @return string the value mapping to the key
   * @throws IllegalArgumentException if the key is not present
   */
  public static function get($key) {
    $array = $_SERVER;
    if (static::$default !== null) {
      $array = static::$default;
    }
    if (!array_key_exists($key, $array)) {
      throw new IllegalArgumentException($key . ' does not exist in the server array');
    }
    return $array[$key];
  }

  /**
   * Returns the url in an array. Example:
   *
   * 'example.com/test/url/path' would result in ['test', 'url', 'path']
   *
   * @return array parts of the url in an array
   */
  public static function getRequestUriParts() {
    $requestUri = self::get(self::REQUEST_URI);
    $requestUri = mb_split('\?', $requestUri)[0];
    $parts = mb_split('\/', $requestUri);
    if (count($parts) > 1 && $parts[0] === '') {
      $parts = array_slice($parts, 1);
    }
    if (count($parts) > 1 && $parts[count($parts) - 1] === '') {
      $parts = array_slice($parts, 0, count($parts) - 1);
    }
    return $parts;
  }

  /**
   * @return string a string containing the http version such as '1.1' or '2.0'
   */
  public static function getServerProtocolVersion() {
    $version = self::get(self::SERVER_PROTOCOL);
    return preg_replace('/HTTP\/|http\//', '', $version);
  }

  /**
   * Mainly for test purposes. Method to set given fields to a default value. WrapObject::get will use the
   * values given to this function, if it was invoked.
   *
   * @param array $default
   */
  public static function setDefault(array $default = null) {
    static::$default = $default;
  }
}
