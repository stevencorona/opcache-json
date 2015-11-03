<?php

require 'vendor/autoload.php';

// By default Statsd output is disabled
// $opcache = new Opcache\Status;

// Or pass in Statsd config with an array
// $opcache = new Opcache\Status(["host" => "localhost", "port" => "8125"]);

// Or configure the Statsd connection with a block
$opcache = new Opcache\Status(function() {

  $c   = new \\Domnikl\Statsd\Connection\UdpSocket("127.0.0.1", "8125");
  return new \Domnikl\Statsd\Client($c, "opcache");

});

// This will output the opcache status AND send it to statsd if previously
// configured
echo $opcache->status(true);

// Run something like:
// php -S 127.0.0.1:3000 demo.php
//
// In your browser, go to http://127.0.0.1:3000, and you should see
// something like this:
//
// {
//   scripts: [
//       {
//         full_path: "/Users/steve/Code/oss/opcache-json/src/opcache/status.php",
//         hits: 0,
//         memory_consumption: 9080,
//         last_used: "Fri Mar 28 08:04:07 2014",
//         last_used_timestamp: 1396008247,
//         timestamp: 1396008244
//       },
//       {
//         full_path: "/Users/steve/Code/oss/opcache-json/demo.php",
//         hits: 56,
//         memory_consumption: 1464,
//         last_used: "Fri Mar 28 08:04:07 2014",
//         last_used_timestamp: 1396008247,
//         timestamp: 1396008133
//       }
//     ],
//     status: {
//       opcache_enabled: true,
//       cache_full: false,
//       restart_pending: false,
//       restart_in_progress: false,
//       memory_usage: {
//       used_memory: 5475616,
//       free_memory: 61614424,
//       wasted_memory: 18824,
//       current_wasted_percentage: 0.028049945831299
//     },
//     opcache_statistics: {
//       num_cached_scripts: 2,
//       num_cached_keys: 4,
//       max_cached_keys: 3907,
//       hits: 145,
//       start_time: 1396008117,
//       last_restart_time: 0,
//       oom_restarts: 0,
//       hash_restarts: 0,
//       manual_restarts: 0,
//       misses: 11,
//       blacklist_misses: 0,
//       blacklist_miss_ratio: 0,
//       opcache_hit_rate: 92.948717948718
//     }
//   }
// }
