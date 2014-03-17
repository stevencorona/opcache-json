{
config: {
directives: {
opcache.enable: true,
opcache.enable_cli: false,
opcache.use_cwd: true,
opcache.validate_timestamps: true,
opcache.inherited_hack: true,
opcache.dups_fix: false,
opcache.revalidate_path: false,
opcache.log_verbosity_level: 1,
opcache.memory_consumption: 67108864,
opcache.interned_strings_buffer: 4,
opcache.max_accelerated_files: 2000,
opcache.max_wasted_percentage: 0.05,
opcache.consistency_checks: 0,
opcache.force_restart_timeout: 180,
opcache.revalidate_freq: 2,
opcache.preferred_memory_model: "",
opcache.blacklist_filename: "",
opcache.max_file_size: 0,
opcache.error_log: "",
opcache.protect_memory: false,
opcache.save_comments: true,
opcache.load_comments: true,
opcache.fast_shutdown: false,
opcache.enable_file_override: false,
opcache.optimization_level: 4294967295
},
version: {
version: "7.0.3-dev",
opcache_product_name: "Zend OPcache"
},
blacklist: [ ]
},
status: {
opcache_enabled: true,
cache_full: false,
restart_pending: false,
restart_in_progress: false,
memory_usage: {
used_memory: 5466600,
free_memory: 61632000,
wasted_memory: 10264,
current_wasted_percentage: 0.015294551849365
},
opcache_statistics: {
num_cached_scripts: 1,
num_cached_keys: 1,
max_cached_keys: 3907,
hits: 2,
start_time: 1395064750,
last_restart_time: 0,
oom_restarts: 0,
hash_restarts: 0,
manual_restarts: 0,
misses: 7,
blacklist_misses: 0,
blacklist_miss_ratio: 0,
opcache_hit_rate: 22.222222222222
},
scripts: {
/var/www/index.php: {
full_path: "/var/www/index.php",
hits: 1,
memory_consumption: 1536,
last_used: "Mon Mar 17 10:01:45 2014",
last_used_timestamp: 1395064905,
timestamp: 1395064881
}
}
}
}
