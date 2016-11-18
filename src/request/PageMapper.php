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

use Yarf\exc\RoutingException;
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
 * @package Yarf\request
 */
class PageMapper {

  /**
   * @var array the page map
   */
  private $pageMap;

  /**
   * @var array variable parts of the url
   */
  private $uriVariables;

  /**
   * PageMapper constructor.
   * @param array $pageMap the valid page map
   */
  public function __construct(array $pageMap) {
    $this->pageMap = $pageMap;
    $this->uriVariables = [];
  }

  /**
   * Gets a web page based on the request url. {@code null} i no such page is found.
   *
   * @return WebPage a web page mapping to the request
   * @throws WebException if anything goes wrong
   */
  public function getPage() {
    $page = $this->traverse($this->pageMap, Server::getRequestUriParts());
    if ($page === null) {
      return null;
    }
    return $this->createWebPageFromClassName($page);
  }

  /**
   * @return array parts of the uri to be replaced by variables
   */
  public function getUriVariables() {
    return $this->uriVariables;
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
    $nextKey = $this->uriKeyExists($uriParts[0], $pageMap);
    if ($nextKey !== null) {
      $pageMapResult = $pageMap[$nextKey];
      if (count($uriParts) === 1 && is_string($pageMapResult)) {
        return $pageMapResult;
      }
      if (is_array($pageMapResult)) {
        return $this->traverse($pageMapResult, array_slice($uriParts, 1));
      }
    }
    return null;
  }

  /**
   * Checks, if there is a key in the page map for the given uri part. If there is a wildcard in the url,
   * it will save the wildcard + it's value
   *
   * @param string $nextUriPart
   * @param array $pageMap
   * @return string|null the key, if there is any, {@code null} else
   * @throws RoutingException if a variable in the url appears twice on a single part
   */
  private function uriKeyExists($nextUriPart, array $pageMap) {
    if (array_key_exists($nextUriPart, $pageMap)) {
      return $nextUriPart;
    }
    $keys = array_keys($pageMap);
    $variableParts = preg_grep('/\{(.*)\}/', $keys);
    $variablePartsCount = count($variableParts);
    if ($variablePartsCount === 0) {
      return null;
    } elseif ($variablePartsCount > 1) {
      throw new RoutingException('Single node has more than one variable key');
    }
    $variablePartRaw = $variableParts[0];
    $variablePart = preg_replace(['/\{/', '/\}/'], '', $variablePartRaw);
    if (array_key_exists($variablePart, $this->uriVariables)) {
      throw new RoutingException('a key named ' . $variablePart . ' appears more than once on a single route');
    }
    $this->uriVariables[$variablePart] = $nextUriPart;
    return $variablePartRaw;
  }

  /**
   * @param $className string the full qualified class name of a web page class
   * @return WebPage the web page instance for this class name
   * @throws HttpInternalServerError if the class name could not be mapped to a class or the class ist no {@code WebPage}
   *
   * TODO use RoutingException instead of HttpInternalServerError?
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
