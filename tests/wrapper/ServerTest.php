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

namespace Yarf\wrapper;


use Yarf\exc\IllegalArgumentException;

class ServerTest extends \PHPUnit_Framework_TestCase {

  public function tearDown() {
    Server::setDefault();
  }

  public function testSetDefault() {
    $testArray = [
      'test' => 'sample value'
    ];
    Server::setDefault($testArray);
    $this->assertEquals(Server::get('test'), 'sample value');
  }

  public function testGetKeyNotExistent() {
    $this->setExpectedException(IllegalArgumentException::class);
    Server::setDefault(null);
    Server::get('test');
  }

  public function testGetRequestUriParts() {
    Server::setDefault([Server::REQUEST_URI => '/this/is/a/simple/test']);
    $this->assertEquals(['this', 'is', 'a', 'simple', 'test'], Server::getRequestUriParts());

    Server::setDefault([Server::REQUEST_URI => '/this/is/a/simple/test/']);
    $this->assertEquals(['this', 'is', 'a', 'simple', 'test'], Server::getRequestUriParts());

    Server::setDefault([Server::REQUEST_URI => '/this/is/a/simple/test?and=1&this=2&are=3&some=4&params=5']);
    $this->assertEquals(['this', 'is', 'a', 'simple', 'test'], Server::getRequestUriParts());

    Server::setDefault([Server::REQUEST_URI => '/this/is/a/simple/test/?and=1&this=2&are=3&some=4&params=5']);
    $this->assertEquals(['this', 'is', 'a', 'simple', 'test'], Server::getRequestUriParts());

    Server::setDefault([Server::REQUEST_URI => '/']);
    $this->assertEquals([''], Server::getRequestUriParts());

    Server::setDefault([Server::REQUEST_URI => '/?test=jop']);
    $this->assertEquals([''], Server::getRequestUriParts());
  }

  public function testGetServerProtocolVersion() {
    Server::setDefault([Server::SERVER_PROTOCOL => 'HTTP/1.1']);
    $this->assertEquals('1.1', Server::getServerProtocolVersion());

    Server::setDefault([Server::SERVER_PROTOCOL => 'http/1.1']);
    $this->assertEquals('1.1', Server::getServerProtocolVersion());

    Server::setDefault([Server::SERVER_PROTOCOL => '1.1']);
    $this->assertEquals('1.1', Server::getServerProtocolVersion());
  }

}
