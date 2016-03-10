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

namespace JannisFink\routing\wrapper;


use JannisFink\routing\exc\IllegalArgumentException;

class ServerTest extends \PHPUnit_Framework_TestCase {

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

}
