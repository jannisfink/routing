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

class SampleWebPage extends WebPage {
  // empty on purpose
}

class WebPageTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var WebPage
   */
  private $samplePage;

  public function setUp() {
    $this->samplePage = new SampleWebPage();
  }

  public function testGet() {
    $this->setExpectedException(HttpMethodNotAllowed::class);
    $this->samplePage->get();
  }

  public function testPost() {
    $this->setExpectedException(HttpMethodNotAllowed::class);
    $this->samplePage->post();
  }

  public function testPut() {
    $this->setExpectedException(HttpMethodNotAllowed::class);
    $this->samplePage->put();
  }

  public function testDelete() {
    $this->setExpectedException(HttpMethodNotAllowed::class);
    $this->samplePage->delete();
  }

}
