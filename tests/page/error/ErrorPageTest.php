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

namespace Yarf\page\error;


use Yarf\exc\web\HttpBadRequest;

class ErrorPageTest extends \PHPUnit_Framework_TestCase {

  private $exceptionWithoutDetails;

  private $exceptionWithDetails;

  private $errorPage;

  public function setUp() {
    $this->errorPage = new ErrorPage();
    $this->exceptionWithoutDetails = new HttpBadRequest();
    $this->exceptionWithDetails = new HttpBadRequest("Some details");
  }

  public function testTextWithoutDetails() {
    $result = $this->errorPage->text($this->exceptionWithoutDetails);

    $this->assertEquals("HTTP 400: Bad Request\n", $result);
  }

  public function testTextWithDetails() {
    $result = $this->errorPage->text($this->exceptionWithDetails);

    $this->assertEquals("HTTP 400: Bad Request\nSome details", $result);
  }

  public function testJsonWithoutDetails() {
    $result = $this->errorPage->json($this->exceptionWithoutDetails);

    $this->assertEquals(["statusCode" => 400, "message" => "Bad Request", "details" => null], $result);
  }

  public function testJsonWithDetails() {
    $result = $this->errorPage->json($this->exceptionWithDetails);

    $this->assertEquals(["statusCode" => 400, "message" => "Bad Request", "details" => "Some details"], $result);
  }

  public function testHtmlWithoutDetails() {
    $result = $this->errorPage->html($this->exceptionWithoutDetails);

    $expected = "
      <!DOCTYPE html>
      <html>
        <head><title>Bad Request</title></head>
        <body>
          <h1>HTTP 400: Bad Request</h1>
          <p></p>
        </body>
      </html>
    ";
    $this->assertEquals($expected, $result);
  }

  public function testHtmlWithDetails() {
    $result = $this->errorPage->html($this->exceptionWithDetails);

    $expected = "
      <!DOCTYPE html>
      <html>
        <head><title>Bad Request</title></head>
        <body>
          <h1>HTTP 400: Bad Request</h1>
          <p>Some details</p>
        </body>
      </html>
    ";

    $this->assertEquals($expected, $result);
  }

}
