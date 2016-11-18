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

namespace Yarf;

use Yarf\exc\web\WebException;
use  Yarf\page\error\ErrorPage;
use Yarf\request\PageMapper;
use Yarf\response\PageResolver;

/**
 * Class Router
 *
 * Base class for any http request. You may want it to be present in you index.php.
 * Configure your webserver so that it will rewrite all requests to this file.
 *
 * Sample usage would be:
 *
 *    $pageMap = [
 *      'hello-world' => HelloWorld::class
 *    ]
 *
 *    Yarf\Router::showPage($pageMap);
 *
 * @package Yarf
 */
class Router {

  private static $defaultErrorMap = array(
    WebException::STATUS_CODE => ErrorPage::class
  );

  /**
   * @var bool
   */
  private $test;

  /**
   * @var array the valid classmap
   */
  private $classMap;

  /**
   * @var array the error map
   */
  private $errorMap;

  /**
   * if this variable is set to {@code true}, this means that this router should do nothing, if it cannot find a page.
   *
   * @var bool
   */
  private $fallThrough;

  /**
   * Router constructor.
   *
   * Constructs a new router.
   *
   * @param $fallThrough bool let this router do nothing for not found pages
   */
  public function __construct($fallThrough = false) {
    $this->fallThrough = $fallThrough;
  }

  /**
   * Main API method to show a page.
   *
   * @param $classMap array an associative array which maps all wanted urls to the given classname
   * @param $errorMap array an array with numeric keys mapping one http statuscode.
   *
   * @deprecated use `route` instead
   */
  public function showPage(array $classMap, array $errorMap = null) {
    $this->route($classMap, $errorMap);
  }

  /**
   * Main API method to show a page.
   *
   * @param $classMap array an associative array which maps all wanted urls to the given classname
   * @param $errorMap array an array with numeric keys mapping one http statuscode.
   */
  public function route(array $classMap, array $errorMap = null) {
    $this->classMap = $classMap;
    $this->errorMap = $errorMap === null ? self::$defaultErrorMap : array_merge(self::$defaultErrorMap, $errorMap);

    $pageMapper = new PageMapper($this->getClassMap());
    $page = $pageMapper->getPage();

    if ($page == null) {
      // do nothing, if no page is found
      return;
    }

    $pageResolver = new PageResolver($this, $page, $pageMapper->getUriVariables(), $this->errorMap);
    $pageResolver->evaluateWebPage();
    $pageResolver->createHeader();
    $echo = $pageResolver->getRequestBody();

    // do not print if this is a test
    if (!$this->test) {
      print $echo;
    }
  }

  /**
   * @return string[] the actually valid class map
   */
  public function getClassMap() {
    return $this->classMap;
  }

  /**
   * For test purposes only. Sets a private field to indicate that this is a test run
   */
  public final function runAsTest() {
    $this->test = true;
  }

  /**
   * Checks, if this run is a test run.
   *
   * @return bool {@code true} for test run, {@code false} else
   */
  public final function isTestRun() {
    return $this->test;
  }

}
