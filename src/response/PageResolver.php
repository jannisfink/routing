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
use Yarf\page\TextPage;
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

  private $evaluated;

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
    $this->uriVariables = $uriVariables;
    $this->evaluated = false;
  }

  public function evaluateWebPage() {
    $this->evaluated = true;

    if ($this->webPage === null) {
      $this->thrownWebException = new HttpNotFound();
      $this->rawRequestBody = $this->createRequestBodyFromException();
      return;
    }

    try {
      $renderer = new PageRenderer($this->webPage, $this->uriVariables);
      return $renderer->evaluatePage();
    } catch (WebException $e) {
      $this->thrownWebException = $e;
      $this->rawRequestBody = $this->createRequestBodyFromException();
    }
  }

  private function createRequestBodyFromException() {
    $statusCode = $this->thrownWebException->getStatusCode();
    $errorPage = array_key_exists($statusCode, $this->errorMap) ? $this->errorMap[$statusCode] :
      $this->errorMap[WebException::STATUS_CODE];
    $errorPage = new $errorPage();

    switch ($this->getContentType()) {
      case JsonPage::CONTENT_TYPE:
            return $errorPage->json($this->thrownWebException);
      case TextPage::CONTENT_TYPE:
            return $errorPage->text($this->thrownWebException);
      default:
            return $errorPage->html($this->thrownWebException);
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

    http_response_code($this->getStatusCode());
    header("Content-type:" . $this->getContentType());
  }

  /**
   * @return string the content type for this page based on the content type of the webpage you want to show
   */
  public function getContentType() {
    if ($this->webPage === null) {
      return self::DEFAULT_CONTENT_TYPE;
    } else {
      return $this->webPage->getContentType();
    }
  }

  /**
   * @return int the status code for this page, based on a thrown exception or a default one, if no exception was thrown
   */
  public function getStatusCode() {
    if ($this->thrownWebException === null) {
       return self::DEFAULT_RESPONSE_CODE;
    } else {
      return $this->thrownWebException->getStatusCode();
    }
  }

  /**
   * Method to get the request body from the given web page.
   *
   * @return string the evaluated request body
   */
  public function getRequestBody() {
    if (!$this->evaluated) {
      $this->evaluateWebPage();
    }

    if ($this->webPage instanceof JsonPage) {
      return json_encode($this->rawRequestBody);
    } else {
      return $this->rawRequestBody;
    }
  }

}
