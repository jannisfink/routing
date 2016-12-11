<?php
/**
 * Created by PhpStorm.
 * User: janni
 * Date: 11/12/2016
 * Time: 20:44
 */

namespace Yarf\exc\web;


class HttpUnprocessableEntity extends WebException {

  const STATUS_CODE = 422;

  public function __construct($details = null) {
    parent::__construct("Unprocessable Entity", $details);
  }

}
