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
 *    JannisFink\routing\Router::showPage($pageMap);
 *
 * @package JannisFink\routing
 */
class Router {

  private static $classMap;

  /**
   * Main API method to show a page.
   *
   * @param array $classMap an associative array which maps all wanted urls to the given classname
   */
  public static function showPage(array $classMap) {
    self::$classMap = $classMap;
  }

  /**
   * @return array the actually valid class map
   */
  public static function getClassMap() {
    return self::$classMap;
  }

}
