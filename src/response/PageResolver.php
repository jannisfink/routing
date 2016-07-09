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


use Yarf\exc\web\HttpMethodNotAllowed;
use Yarf\exc\web\HttpNotFound;
use Yarf\exc\web\WebException;
use Yarf\page\JsonPage;
use Yarf\page\WebPage;
use Yarf\Router;
use Yarf\wrapper\Server;

/**
 * Class PageResolver
 *
 * Class to create the http response from a given web page
 *
 * @package Yarf\response
 */
class PageResolver {

  const DEFAULT_RESPONSE_CODE = 200;

  const DEFAULT_CONTENT_TYPE = "text/html";

  private $webPage;

  private $errorMap;

  private $uriVariables;

  /**
   * @var WebException
   */
  private $thrownWebException;

  /**
   * @var string
   */
  private $rawRequestBody;

  public function __construct(WebPage $webPage = null, array $uriVariables = null, array $errorMap = null) {
    $this->webPage = $webPage;
    $this->errorMap = $errorMap;
  }

  public function evaluateWebPage() {
    if ($this->webPage === null) {
      $this->thrownWebException = new HttpNotFound();
      return;
    }

    $requestMethod = Server::getRequestMethod();
    $reflectionObject = new \ReflectionObject($this->webPage);

    if (!$reflectionObject->hasMethod($requestMethod)) {
      // we currently not support the given http method
      $this->thrownWebException = new HttpMethodNotAllowed();
    }

    try {
      $this->rawRequestBody = $this->webPage->{$requestMethod}(...array_values($this->uriVariables));
    } catch (WebException $e) {
      $this->thrownWebException = $e;
    }
  }

  /**
   * Method to set all necessary fields for the response header,
   * such as the content type or the status code.
   *
   * This function will do nothing if this is a test run
   */
  public function createHeader() {
    if (Router::isTestRun()) {
      return;
    }

    if ($this->thrownWebException === null) {
      http_response_code(self::DEFAULT_RESPONSE_CODE);
    } else {
      http_response_code($this->thrownWebException->getStatusCode());
    }

    $contentType = null;
    if ($this->webPage === null) {
      $contentType = self::DEFAULT_CONTENT_TYPE;
    } else {
      $contentType = $this->webPage->getContentType();
    }
    header("Content-type:" . $contentType);
  }

  /**
   * Method to get the request body from the given web page.
   *
   * @return string the evaluated request body
   */
  public function getRequestBody() {
    if ($this->webPage instanceof JsonPage) {
      return json_encode($this->rawRequestBody);
    } else {
      return $this->rawRequestBody;
    }
  }

}
