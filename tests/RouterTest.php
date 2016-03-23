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

use Yarf\exc\web\HttpNotFound;
use Yarf\wrapper\Server;

class RouterTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    Server::setDefault();
  }

  public function testShowPageSetClassMap() {
    $testArray = array();
    Server::setDefault([Server::REQUEST_URI => '']);
    Router::showPage($testArray);

    $this->assertEquals(Router::getClassMap(), $testArray);
  }

  public function testSetHeader() {
    Router::getOutput(null, new HttpNotFound());
    $this->assertEquals(404, http_response_code());
  }

  public function testGetOutput() {
    $output = Router::getOutput(null, new HttpNotFound());
    $this->assertEquals('<h1>HTTP 404</h1><br><br>Not Found', $output);
  }

}
