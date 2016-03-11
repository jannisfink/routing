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

namespace JannisFink\routing\page;
use JannisFink\routing\exc\web\HttpMethodNotAllowed;
use JannisFink\routing\exc\web\WebException;

/**
 * Class WebPage
 *
 * Base class for all web pages.
 *
 * @package JannisFink\routing\page
 */
abstract class WebPage {

  /**
   * Method mapping to the HTTP GET method
   *
   * @throws WebException if anything goes wrong
   */
  public function get() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP POST method
   *
   * @throws WebException if anything goes wrong
   */
  public function post() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP PUT method
   *
   * @throws WebException if anything goes wrong
   */
  public function put() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP DELETE method
   *
   * @throws WebException if anything goes wrong
   */
  public function delete() {
    throw new HttpMethodNotAllowed();
  }

}
