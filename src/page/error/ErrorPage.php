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

use Yarf\exc\web\WebException;

/**
 * Class ErrorPage
 *
 * Class to show any error to the client
 *
 * @package Yarf\page\error
 */
class ErrorPage {

  /**
   * Returns a html view for the thrown exception
   *
   * @param WebException $exception the exception thrown and cause for this page to be shown
   * @return string html representation of the given exception
   */
  public function html(WebException $exception) {
    $message = $exception->getMessage();
    $statusCode = $exception->getStatusCode();
    $details = $exception->getDetails() === null ? "" : $exception->getDetails();
    return "
      <!DOCTYPE html>
      <html>
        <head><title>$message</title></head>
        <body>
          <h1>HTTP $statusCode: $message</h1>
          <p>$details</p>
        </body>
      </html>
    ";
  }

  /**
   * Returns a json view for the thrown exception
   *
   * @param WebException $exception the exception thrown and cause for this page to be shown
   * @return string json representation of the given exception
   */
  public function json(WebException $exception) {
    return [
      "statusCode" => $exception->getStatusCode(),
      "message" => $exception->getMessage(),
      "details" => $exception->getDetails()
    ];
  }

  /**
   * Returns a text view for the thrown exception
   *
   * @param WebException $exception the exception thrown and cause for this page to be shown
   * @return string text representation of the given exception
   */
  public function text(WebException $exception) {
    $message = $exception->getMessage();
    $statusCode = $exception->getStatusCode();
    $details = $exception->getDetails() === null ? "" : $exception->getDetails();

    return "HTTP $statusCode: $message\n$details";
  }

}
