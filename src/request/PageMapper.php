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

use Yarf\exc\web\HttpNotFound;
use Yarf\exc\web\WebException;
use Yarf\page\WebPage;

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
   * @return WebPage a webpage mapping to the request
   * @throws WebException if anything goes wrong
   */
  public function getPage() {
    throw new HttpNotFound();
  }

}
