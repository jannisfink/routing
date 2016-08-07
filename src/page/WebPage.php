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

namespace Yarf\page;

use Yarf\exc\web\HttpMethodNotAllowed;
use Yarf\exc\web\WebException;

/**
 * Class WebPage
 *
 * Base class for all web pages.
 *
 * @package Yarf\page
 */
abstract class WebPage {

  /**
   * @const string content type of this website
   */
  const CONTENT_TYPE = null;

  /**
   * WebPage constructor. This constructor is final on purpose to disallow any constructor for
   * the subclasses.
   */
  public final function __construct() {
    // empty on purpose
  }

  /**
   * @return string the content type of this page
   */
  public function getContentType() {
    return static::CONTENT_TYPE;
  }

  /**
   * This function is calld once for each request to check, if the current user has the permission to
   * view the requested page. If this funtion returns {@code false}, the response will be either
   * a `HTTP 403 - Forbidden` or a `404 - Not Found`, depending on the result of `showForbiddenWithoutPermissions`
   *
   * @return bool {@code true}, if the user is allowed to view this page, {@code false} else
   */
  public function checkPermission() {
    return true;
  }

  /**
   * If the user should see a `HTTP 404 - Not Found` when he has no permission to view the current page, this function
   * should return {@code false}. The default behavior is to show `HTTP 403 - Forbidden` in this case.
   *
   * @return bool {@code true} if the user should see a `HTTP 403 - Forbidden`, {@code false} else
   */
  public function showForbiddenWithoutPermission() {
    return true;
  }

  /**
   * This function is used to initialize this pages context. It is an alternative for the construrtor,
   * since the constructor is final
   */
  public function initialize() {
    // empty on purpose
  }

  /**
   * @return mixed the page content
   *
   * @throws WebException if anything goes wrong
   */
  public function render() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP GET method
   * 
   * @return mixed the page content
   *
   * @throws WebException if anything goes wrong
   */
  public function get() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP POST method
   * 
   * @return mixed the page content
   *
   * @throws WebException if anything goes wrong
   */
  public function post() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP PUT method
   * 
   * @return mixed the page content
   *
   * @throws WebException if anything goes wrong
   */
  public function put() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP DELETE method
   * 
   * @return mixed the page content
   *
   * @throws WebException if anything goes wrong
   */
  public function delete() {
    throw new HttpMethodNotAllowed();
  }

  /**
   * Method mapping to the HTTP PATCH method
   * 
   * @return mixed the page content
   *
   * @throws WebException if anything goes wrong
   */
  public function patch() {
    throw new HttpMethodNotAllowed();
  }

}
