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
use Yarf\page\WebPage;
use Yarf\wrapper\Server;

/**
 * Class PageRenderer
 *
 * This class is responsible for calling a pages render function or the function suitable
 * for the used http method
 *
 * @package Yarf\response
 */
class PageRenderer {

  const FALLBACK_FUNCTION = "render";

  private $webPage;

  private $uriVariables;

  /**
   * PageRenderer constructor.
   * @param WebPage $page the webpage to show
   * @param string[] $uriVariables variables on this route as an associative array
   */
  public function __construct(WebPage $page = null, array $uriVariables) {
    $this->webPage = $page;
    $this->uriVariables = $uriVariables;
  }

  /**
   * @return array|string the evaluated page content
   *
   * @throws WebException if anything goes wrong
   */
  public function evaluatePage() {
    if ($this->webPage == null) {
      throw new HttpNotFound();
    }

    $requestMethod = Server::getRequestMethod();
    $reflectionObject = new \ReflectionObject($this->webPage);

    if (!$reflectionObject->hasMethod($requestMethod)) {
      throw new HttpMethodNotAllowed();
    }

    $renderParameters = $this->getParametersForMethod($reflectionObject->getMethod(self::FALLBACK_FUNCTION));
    try {
      return call_user_func_array([$this->webPage, self::FALLBACK_FUNCTION], $renderParameters);
    } catch (HttpMethodNotAllowed $exc) {
      // empty on purpose (try render first, if its not overridden, fall through)
    }

    $requestMethodParameters = $this->getParametersForMethod($reflectionObject->getMethod($requestMethod));
    return call_user_func_array([$this->webPage, $requestMethod], $requestMethodParameters);

  }

  /**
   * Calculates the parameter order for the given function. Substitues teh parameters with the corresponding uri
   * variables. If no value found for a specific parameter, it will be set to {@code null}.
   *
   * @param \ReflectionMethod $method the method to calculate the parameter order for
   * @return string[] the correct parameter order for the given function
   */
  private function getParametersForMethod(\ReflectionMethod $method) {
    $result = [];

    foreach ($method->getParameters() as $parameter) {
      if (array_key_exists($parameter->getName(), $this->uriVariables)) {
        $result[] = $this->uriVariables[$parameter->getName()];
      } else {
        $result[] = null;
      }
    }

    return $result;
  }

}
