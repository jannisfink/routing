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

namespace Yarf\http;

/**
 * Class Header
 *
 * This class is a collection of possible http headers to be used by this package
 *
 * @package Yarf\http
 */
final class Header {

  const ACCESS_CONTROL_ALLOW_ORIGIN = "Access-Control-Allow-Origin";
  const ACCEPT_PATCH = "Accept-Patch";
  const ACCEPT_RANGES = "Accept-Ranges";
  const AGE = "Age";
  const ALLOW = "Allow";
  const ALT_SVC = "Alt-Svc";
  const CACHE_CONTROL = "Cache-Control";
  const CONNECTION = "Connection";
  const CONTENT_DISPOSITION = "Content-Disposition";
  const CONTENT_ENCODING = "Content-Encoding";
  const CONTENT_LANGUAGE = "Content-Language";
  const CONTENT_LENGTH = "Content-Length";
  const CONTENT_LOCATION = "Content-Location";
  const CONTENT_RANGE = "Content-Range";
  const CONTENT_TYPE = "Content-Type";
  const DATE = "Date";
  const ETAG = "ETag";
  const EXPIRES = "Expires";
  const LAST_MODIFIED = "Last-Modified";
  const PRAGMA = "Pragma";
  const PROXY_AUTHENTICATE = "Proxy-Authenticate";
  const PUBLIC_KEY_PINS = "Public-Key-Pins";
  const REFRESH = "Refresh";
  const RETRY_AFTER = "Retry-After";
  const SERVER = "Server";
  const SET_COOKIE = "Set-Cookie";
  const STATUS = "Status";
  const STRICT_TRANSPORT_SECURITY = "Strict-Transport-Security";
  const TRAILER = "Trailer";
  const TRANSFER_ENCODING = "Transfer-Encoding";
  const TSV = "TSV";
  const UPGRADE = "Upgrade";
  const VARY = "Vary";
  const VIA = "Via";
  const Warning = "Warning";
  const WWW_AUTHENTICATE = "WWW-Authenticate";
  const X_FRAME_OPTIONS = "X-Frame-Options";

  /**
   * Header constructor.
   *
   * Do not allow instances of this class
   */
  private function __construct() {
    // empty on purpose
  }

}
