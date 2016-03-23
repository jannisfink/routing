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

use Yarf\exc\web\HttpInternalServerError;
use Yarf\exc\web\HttpNotFound;
use Yarf\exc\web\WebException;
use Yarf\page\WebPage;
use Yarf\wrapper\Server;

/**
 * Class PageMapper
 *
 * Gets a page map and extends it, if necessary
 *
 * @package JannisFink\routing\request
 */
class PageMapper {

  /**
   * @var array the page map
   */
  private $pageMap;

  /**
   * PageMapper constructor.
   * @param array $pageMap the valid page map
   */
  public function __construct(array $pageMap) {
    $this->pageMap = $pageMap;
  }

  /**
   * Gets a web page based on the request url.
   *
   * @return WebPage a web page mapping to the request
   * @throws WebException if anything goes wrong
   */
  public function getPage() {
    $page = $this->traverse($this->pageMap, Server::getRequestUriParts());
    if ($page === null) {
      throw new HttpNotFound();
    }
    return $this->createWebPageFromClassName($page);
  }

  /**
   * @param $pageMap array the page map for recursive calls
   * @param $uriParts array the uri parts
   * @return string|null matching page class name, if there is any, {@code null} else
   */
  private function traverse(array $pageMap, array $uriParts) {
    if (!count($uriParts)) {
      return null;
    }
    $nextUriPart = $uriParts[0];
    if (array_key_exists($nextUriPart, $pageMap)) {
      $pageMapResult = $pageMap[$nextUriPart];
      if (count($uriParts) === 1 && is_string($pageMapResult)) {
        return $pageMapResult;
      }
      return $this->traverse($pageMapResult, array_slice($uriParts, 1));
    }
    return null;
  }

  /**
   * @param $className string the full qualified class name of a web page class
   * @return WebPage the web page instance for this class name
   * @throws HttpInternalServerError if the class name could not be mapped to a class or the class ist no {@code WebPage}
   */
  private function createWebPageFromClassName($className) {
    try {
      $class = new \ReflectionClass($className);
      if ($class->isSubclassOf(WebPage::class)) {
        $constructor = $class->getConstructor();
        if (!$constructor || ($constructor && $constructor->isPublic())) {
          return new $className;
        }
      }
    } catch (\ReflectionException $e) {
      // empty on purpose
    }
    throw new HttpInternalServerError();
  }

}
