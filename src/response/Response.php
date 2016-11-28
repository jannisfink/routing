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

namespace Yarf\response;
use Yarf\http\Header;
use Yarf\page\WebPage;


/**
 * Class Response
 *
 * This class holds the http response data.
 *
 * @package Yarf\response
 */
class Response {

  /**
   * @var string[] the headers to apply to the page
   */
  private $headers;

  /**
   * @var mixed the result
   */
  private $result;

  /**
   * Response constructor.
   */
  public function __construct() {
    $this->headers = [];
  }

  public static function createResponseForPage(WebPage $page) {
    $response = new Response();
    $response->addHeader(Header::CONTENT_TYPE, $page->getContentType());
    return $response;
  }

  /**
   * @param string $header name of the header
   * @param string $value value of the header
   */
  public function addHeader($header, $value) {
    $this->headers[$header] = $value;
  }

  /**
   * @return \string[]
   */
  public function getHeaders() {
    return $this->headers;
  }

  /**
   * Set the requests result
   *
   * @param mixed $result
   */
  public function result($result) {
    $this->result = $result;
  }

  /**
   * @return mixed
   */
  public function getResult() {
    return $this->result;
  }

}
