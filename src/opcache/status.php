<?php

namespace Opcache;

class Status {

  $this->result = [];

  public function __construct() {
  }

  public function configuration() {
    $raw = opcache_get_configuration();
    $this->result['config'] = $raw;
  }

  public function status($with_scripts = false) {
    $raw = opcache_get_status($with_scripts);

    // The scripts output has a really non-optimal format
    // for JSON, the result is a hash with the full path
    // as the key. Let's strip the key and turn it into
    // a regular array.
    if ($with_scripts == true) {

      // Make a copy of the raw scripts and then strip it from
      // the data.
      $scripts = $raw['scripts'];
      unset($raw['scripts']);

      $this->result['scripts'] = [];

      // Loop over each script and strip the key.
      // TODO: Test if preserving the key is necessary (i.e, symlinks)
      foreach($scripts as $key => $val) {
        $this->result['scripts'][] = $val;
      }

    }

    $this->result['status'] = $raw;
  }
}
