<?php

namespace Opcache;

class Status {

  public $statsd  = null;
  public $result = [];

  public function __construct($options_or_block=false) {
    // Try to create a statsd handler via block or options
    if (is_callable($options_or_block)) {
      $this->statsd = $options_or_block();
    } elseif(is_array($options_or_block)) {
      $this->create_statsd_handle($options_or_block);
    }
  }

  public function configuration() {
    $raw = opcache_get_configuration();
    $this->result['config'] = $raw;
  }

  public function status($with_scripts = false) {

    // Guard execution if the extension is not loaded.
    if (! extension_loaded("Zend OPcache")) {
      return json_encode([]);
    }

    // Clear out data from prevous run
    $this->result['status'] = null;

    $raw = \opcache_get_status($with_scripts);

    // The scripts output has a really non-optimal format
    // for JSON, the result is a hash with the full path
    // as the key. Let's strip the key and turn it into
    // a regular array.
    if ($with_scripts == true) {

      // Make a copy of the raw scripts and then strip it from
      // the data.
      $scripts = $raw['scripts'];

      $this->result['scripts'] = [];

      // Loop over each script and strip the key.
      // TODO: Test if preserving the key is necessary (i.e, symlinks)
      foreach($scripts as $key => $val) {
        $this->result['scripts'][] = $val;
      }

      // Sort by memory consumption
      usort($this->result['scripts'], function($a, $b) {
        if ($a["memory_consumption"] == $b["memory_consumption"]) return 0;
        return ($a["memory_consumption"] < $b["memory_consumption"]) ? 1 : -1;
      });

    }

    $this->result['status'] = $raw;

    if ($this->statsd != null) {
      $this->send_to_statsd();
    }

    return json_encode($this->result);
  }

  protected function send_to_statsd() {
    foreach($this->result["status"]["memory_usage"] as $k => $v) {
      $this->statsd->gauge($k, $v);
    }

    foreach($this->result["status"]["opcache_statistics"] as $k => $v) {
      $this->statsd->gauge($k, $v);
    }
  }

  protected function create_statsd_handle($opts) {
    // Set default statsd options
    $default = ["host"       => "127.0.0.1",
                "port"       => 8125,
                "timeout"    => null,
                "persistent" => false,
                "namespace"  => "opcache"];


    $opts = array_merge($opts, $default);

    $connection = new \Domnikl\Statsd\Connection\Socket($opts["host"],
                                                        $opts["port"],
                                                        $opts["timeout"],
                                                        $opts["persistent"]);

    $this->statsd = new \Domnikl\Statsd\Client($connection, $opts["namespace"]);

  }

}
