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


use Yarf\exc\web\WebException;
use Yarf\page\JsonPage;
use Yarf\page\TextPage;
use Yarf\page\WebPage;
use Yarf\Router;

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

  private $router;

  private $errorMap;

  private $uriVariables;

  private $evaluated;

  /**
   * @var WebException
   */
  private $thrownWebException;

  /**
   * @var Response
   */
  private $response;

  /**
   * @var string
   */
  private $rawRequestBody;

  /**
   * PageResolver constructor.
   *
   * @param Router $router the router which handles the current request
   * @param WebPage|null $webPage the page to show the user
   * @param string[]|null $uriVariables the variables which were on this route, as an associative array
   * @param string[]|null $errorMap the error map which holds different {@code Yarf\page\error\ErrorPage}
   * to display any thrown exception
   */
  public function __construct(Router $router, WebPage $webPage = null, array $uriVariables = null,
                              array $errorMap = null) {
    $this->router = $router;
    $this->webPage = $webPage;
    $this->errorMap = $errorMap;
    $this->uriVariables = $uriVariables;
    $this->evaluated = false;
  }

  /**
   * Evaluates the given webpage and handles any occuring error
   */
  public function evaluateWebPage() {
    $this->evaluated = true;

    try {
      $renderer = new PageRenderer($this->webPage, $this->uriVariables);
      $this->response = $renderer->evaluatePage();
      $this->rawRequestBody = $this->response->getResult();
    } catch (WebException $e) {
      // FIXME this is ugly
      $this->thrownWebException = $e;
      $this->rawRequestBody = $this->createRequestBodyFromException();
    }
  }

  private function createRequestBodyFromException() {
    if ($this->thrownWebException === null) {
      return "";  // fail silently, as it should not happen
    }

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
    if ($this->router->isTestRun()) {
      return;
    }

    http_response_code($this->getStatusCode());
    foreach ($this->response->getHeaders() as $header => $value) {
      header($header . ":" . $value);
    }
  }

  /**
   * @return string the content type for this page based on the content type of the webpage you want to show
   */
  public function getContentType() {
    if ($this->webPage === null) {
      // FIXME use content type given in request?
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
