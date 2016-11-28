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


class RequestTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var Request
   */
  private $request;

  public function setUp() {
    $get = ["test" => "tut"];
    $post = ["tut" => "test"];
    $body = '{"test": "tut", "tut": [1, 2, 3]}';
    $this->request = new Request($get, $post, $body);
  }

  public function testGetJson() {
    $bodyParsed = ["test" => "tut", "tut" => [1, 2, 3]];
    $this->assertEquals($bodyParsed, $this->request->getJson());
  }

  public function testGetNonexistingKey() {
    $this->assertNull($this->request->get("nonexisting"));
  }

  public function getGetVariable() {
    $this->assertEquals("tut", $this->request->get("test"));
  }

  public function getPostVariable() {
    $this->assertEquals("test", $this->request->get("tut"));
  }

}
