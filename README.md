# be.calibrate.eavesdropper

Overrides the default CiviCRM file log and writes to Redis instead. You can choose a level to log from. E.g only log WARNING's and higher. 
This will make your logging more lightweight and easier to manage.

Side note: Redis in a default setup is non-persistent storage so logs will get lost eventually. For more information about making Redis persistent see https://redis.io/topics/persistence

### Minimum Requirements

- PHP v7.0+
- CiviCRM 5.0
- PhpRedis extension
- Redis 4.0

### Installation

- You can directly clone to your CiviCRM extension directory using<br>
```$ git clone https://github.com/wannesderoy/be.calibrate.eavesdropper```

### Configuration

Put the following constants in your civicrm.settings.php and update accordingly.

- CIVICRM_EAVESDROPPER_REDIS_HOST: your Redis host or IP address.
- CIVICRM_EAVESDROPPER_REDIS_PORT: The port on which Redis is accessible. By default 6379
- CIVICRM_EAVESDROPPER_REDIS_PASSWORD: Add a password if required. By default NULL
- CIVICRM_EAVESDROPPER_SEVERITY_LIMIT: Add a minimum log severity level to write to Redis. You can limit this to only log from Errors for example. By default DEBUG.

```
/**
 * Configure the eavesdropper Redis connection here.
 */
if (!defined('CIVICRM_EAVESDROPPER_REDIS_HOST')) {
  define('CIVICRM_EAVESDROPPER_REDIS_HOST', 'redis' );
}
if (!defined('CIVICRM_EAVESDROPPER_REDIS_PORT')) {
  define('CIVICRM_EAVESDROPPER_REDIS_PORT', 6379 );
}
if (!defined('CIVICRM_EAVESDROPPER_REDIS_PASSWORD')) {
  define('CIVICRM_EAVESDROPPER_REDIS_PASSWORD', '' );
}
if (!defined('CIVICRM_EAVESDROPPER_REDIS_BASE')) {
  define('CIVICRM_EAVESDROPPER_REDIS_BASE', '' );
}
// The maximum severity level of a log to store in Redis.
if (!defined('CIVICRM_EAVESDROPPER_SEVERITY_LIMIT')) {
  define('CIVICRM_EAVESDROPPER_SEVERITY_LIMIT', 'DEBUG' );
}
```

OR

Manage settings: **yoursite.org/civicrm/eavesdropper/settings**.

If the constants are defined the settings on /civicrm/eavesdropper/settings will do nothing. For performance reasons I encourage/recommend to use the constants.

###### Example of how logs look like in redis (via redis commander)

![Screenshot](/images/eavesdropper.png)



### Tips

- Redis commander https://joeferner.github.io/redis-commander & https://hub.docker.com/r/rediscommander/redis-commander/

### Future

- Support for other redis client classes.
- Ability to add and configure TTL's per level.
- ...
