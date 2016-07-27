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
 * Class HttpInternalServerError
 *
 * To send a HTTP 500 Internal Server Error header back to the client.
 *
 * @package Yarf\exc\web
 */
class HttpInternalServerError extends WebException {

  const STATUS_CODE = 500;

  public function __construct($details) {
    parent::__construct('Internal Server Error', $details);
  }

}
