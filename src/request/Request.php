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

namespace Yarf\request;

/**
 * Class Request
 *
 * This class represents a request. It gives the user access to the request body and GET/POST variables
 *
 * @package Yarf\request
 */
class Request {

  /**
   * @var array the url variables
   */
  private $get;

  /**
   * @var array the request body variables
   */
  private $post;

  /**
   * @var string the request body as plain string
   */
  private $body;

  /**
   * Request constructor.
   * @param array|null $get the get parameters to use, will default to {@code $_GET}
   * @param array|null $post the post (request body variables) to use, will default to {@code $_POST}
   * @param string|null $body the request body as plain string
   */
  public function __construct($get = null, $post = null, $body = null) {
    if ($get === null) {
      $get = $_GET;
    }
    if ($post === null) {
      $post = $_POST;
    }
    // `php_sapi_name` for detecting if thid is a test run. This is kind of ugly
    if ($body === null && php_sapi_name() !== "cli") {
      $body = file_get_contents("php://stdin");
    }

    $this->get = $get;
    $this->post = $post;
    $this->body = $body;
  }

  /**
   * Returns a parameter given via url variables or post variables.
   * If a key exists both in url an in post variables, the url variable
   * will be returned.
   *
   * @param string $key name of the parameter
   * @return string|null the parameter value, {@code null} if the key does not exist
   */
  public function get($key) {
    $get = $this->getParam($key, $this->get);
    return $get !== null ? $get : $this->getParam($key, $this->post);
  }

  /**
   * @return array the request body as associative array
   */
  public function getJson() {
    return json_decode($this->body, true);
  }

  private function getParam($key, $array) {
    if (!array_key_exists($key, $array)) {
      return null;
    }
    return $array[$key];
  }

}
