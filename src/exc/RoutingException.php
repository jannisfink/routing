<?php
/**
 * Created by PhpStorm.
 * User: jannis
 * Date: 24.03.16
 * Time: 12:33
 */

namespace Yarf\exc;


class RoutingException extends BaseException {

  public function __construct($message) {
    parent::__construct($message);
  }

}
