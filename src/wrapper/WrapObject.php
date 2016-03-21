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

/**
 * Interface HttpWrapObject
 *
 * Defines a thin wrapper for any http context (like server, session, cookie...).
 *
 * @package JannisFink\routing\wrapper
 */
interface WrapObject {

  /**
   * Function to access a key in a wrapped object.
   *
   * @param $key
   * @return string the value mapping to the key
   * @throws IllegalArgumentException if the key is not present
   */
  public static function get($key);

  /**
   * Mainly for test purposes. Method to set given fields to a default value. WrapObject::get will use the
   * values given to this function, if it was invoked.
   *
   * @param array $default
   */
  public static function setDefault($default);

}
