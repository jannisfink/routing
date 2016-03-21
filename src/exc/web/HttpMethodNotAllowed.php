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

namespace Yarf\exc\web;

/**
 * Class HttpMethodNotAllowed
 *
 * To send a HTTP 405 Method Not allowed header back to the client.
 *
 * @package JannisFink\routing\exc\web
 */
class HttpMethodNotAllowed extends WebException {

  public function __construct() {
    parent::__construct('Method not allowed');
  }

  /**
   * @return int the status code for this return type
   */
  public function getStatusCode() {
    return 405;
  }
}
