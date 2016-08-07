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


use Yarf\exc\web\HttpForbidden;
use Yarf\exc\web\HttpNotFound;
use Yarf\page\HtmlPage;
use Yarf\wrapper\Server;

class PageWithNoPermission extends HtmlPage {
  public function checkPermission() {
    return false;
  }
}

class PageWithNoPermissionNotFound extends PageWithNoPermission {
  public function showForbiddenWithoutPermission() {
    return false;
  }
}

class PageRendererTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    Server::setDefault([]);
  }

  public function setUp() {
    Server::setDefault([Server::REQUEST_METHOD => "get"]);
  }

  public function testThrowForbiddenIfNoPermission() {
    $this->setExpectedException(HttpForbidden::class);

    $renderer = new PageRenderer(new PageWithNoPermission(), []);
    $renderer->evaluatePage();
  }

  public function testThrowNotFounfIfNoPermissionAndSet() {
    $this->setExpectedException(HttpNotFound::class);

    $renderer = new PageRenderer(new PageWithNoPermissionNotFound(), []);
    $renderer->evaluatePage();
  }

}
