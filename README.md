# be.calibrate.eavesdropper

![Screenshot](/images/eavesdropper.png)

## Introduction

CiviCRM Eavesdropper: Overrides the default file log and writes to Redis instead.

## Installation

- You can directly clone to your CiviCRM extension directory using<br>
```$ git clone https://github.com/wannesderoy/be.calibrate.eavesdropper```

## Requirements

- PHP v7.0+
- CiviCRM 5.0
- PhpRedis extension
- Redis 4.0

## Configuration

Put the following constants in your civicrm.settings.php

```
/**
 * Configure the eavesdropper Redis connection here.
 */
if (!defined('CIVICRM_DB_LOG_HOST')) {
  define('CIVICRM_REDIS_LOG_HOST', 'redis' );
}
if (!defined('CIVICRM_DB_LOG_PORT')) {
  define('CIVICRM_REDIS_LOG_PORT', 6379 );
}
if (!defined('CIVICRM_DB_LOG_PASSWORD')) {
  define('CIVICRM_REDIS_LOG_PASSWORD', '' );
}
if (!defined('CIVICRM_DB_LOG_BASE')) {
  define('CIVICRM_REDIS_LOG_BASE', '' );
}
```

OR

Manage settings: **yoursite.org/civicrm/eavesdropper/settings**.

If the constants are defined the settings on /civicrm/eavesdropper/settings will do nothing. For performance reasons I encourage/recommend to use the constants.

## Tips

- Redis commander https://joeferner.github.io/redis-commander & https://hub.docker.com/r/rediscommander/redis-commander/
- 

## Future

- Support for other redis client classes.
- ...
