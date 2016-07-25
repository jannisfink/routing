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

  /**
   * @var bool
   */
  private static $test;

  /**
   * @var array the valid classmap
   */
  private static $classMap;

  /**
   * Main API method to show a page.
   *
   * @param $classMap array an associative array which maps all wanted urls to the given classname
   * @param $errorMap array an array with numeric keys mapping one http statuscode.
   */
  public static function showPage(array $classMap, array $errorMap = null) {
    self::$classMap = $classMap;

    $pageMapper = new PageMapper(self::getClassMap());
    $page = $pageMapper->getPage();

    $pageResolver = new PageResolver($page, $pageMapper->getUriVariables(), $errorMap);
    $pageResolver->createHeader();
    $echo = $pageResolver->getRequestBody();

    // do not print if this is a test
    if (!self::$test) {
      print $echo;
    }
  }

  /**
   * @return array the actually valid class map
   */
  public static function getClassMap() {
    return self::$classMap;
  }

  /**
   * For test purposes only. Sets a private field to indicate that this is a test run
   */
  public static final function runAsTest() {
    self::$test = true;
  }

  /**
   * Checks, if this run is a test run.
   *
   * @return bool {@code true} for test run, {@code false} else
   */
  public static final function isTestRun() {
    return self::$test;
  }

}
