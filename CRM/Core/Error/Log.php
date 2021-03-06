<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
 */

use CRM\Eavesdropper\Client\PhpRedis;

/**
 * Class CRM_Core_Error_Log
 *
 * A PSR-3 wrapper for CRM_Core_Error.
 */
class CRM_Core_Error_Log extends \Psr\Log\AbstractLogger {

  /**
   * @var \CRM\Eavesdropper\Client\PhpRedis;
   */
  protected $redis;

  /**
   * @var \Redis
   */
  protected $client;

  /**
   * String containing the redis host, defaults to redis
   *
   * @var string
   */
  protected $redis_host;

  /**
   * Int containing the redis port, defaults to default redis port 6379
   *
   * @var int
   */
  protected $redis_port;

  /**
   * Redis default database: will select none (Database 0).
   *
   * @var int
   */
  protected $redis_base;

  /**
   * String containing the password to access Redis.
   *
   * @var int
   */
  protected $redis_password;

  /**
   * CRM_Core_Error_Log constructor.
   */
  public function __construct() {
    $this->map = array(
      \Psr\Log\LogLevel::DEBUG => PEAR_LOG_DEBUG,
      \Psr\Log\LogLevel::INFO => PEAR_LOG_INFO,
      \Psr\Log\LogLevel::NOTICE => PEAR_LOG_NOTICE,
      \Psr\Log\LogLevel::WARNING => PEAR_LOG_WARNING,
      \Psr\Log\LogLevel::ERROR => PEAR_LOG_ERR,
      \Psr\Log\LogLevel::CRITICAL => PEAR_LOG_CRIT,
      \Psr\Log\LogLevel::ALERT => PEAR_LOG_ALERT,
      \Psr\Log\LogLevel::EMERGENCY => PEAR_LOG_EMERG,
    );

    if (defined('CIVICRM_REDIS_LOG_HOST') && defined('CIVICRM_REDIS_LOG_PORT')) {
      $this->redis_host = CIVICRM_REDIS_LOG_HOST;
      $this->redis_port = CIVICRM_REDIS_LOG_PORT;
      $this->redis_base = empty(CIVICRM_REDIS_LOG_BASE) ? NULL : CIVICRM_REDIS_LOG_BASE;
      $this->redis_password = empty(CIVICRM_REDIS_LOG_PASSWORD) ? NULL : CIVICRM_REDIS_LOG_PASSWORD;
    }
    else {
      $config = CRM_Core_BAO_Setting::getItem('eavesdropper', 'eavesdropper-settings');
      if ($config != NULL) {
        $config = json_decode(utf8_decode($this->config), TRUE);
        $this->redis_host = $config['eavesdropper_redis_host'];
        $this->redis_port = $config['eavesdropper_redis_port'];
        $this->redis_base = $config['eavesdropper_redis_base'];
        $this->redis_password = $config['eavesdropper_redis_password'];
      }
    }

    $this->redis = new PhpRedis();
    $this->client = $this->redis->getClient($this->redis_host, $this->redis_port, $this->redis_base, $this->redis_password);
  }

  /**
   * Logs with an arbitrary level.
   *
   * @param mixed $level
   * @param string $message
   * @param array $context
   */
  public function log($level, $message, array $context = array()) {
    // FIXME: This flattens a $context a bit prematurely. When integrating
    // with external/CMS logs, we should pass through $context.
    if (!empty($context)) {
      if (isset($context['exception'])) {
        $context['exception'] = CRM_Core_Error::formatTextException($context['exception']);
      }
      $message .= "\n" . print_r($context, 1);

      if (CRM_Utils_System::isDevelopment() && CRM_Utils_Array::value('civi.tag', $context) === 'deprecated') {
        trigger_error($message, E_USER_DEPRECATED);
      }
    }

    if ($this->client) {
      $this->client->rPush("civicrm:logs:eavesdropper:{$level}", serialize($message));
    }
    else {
      // Fallback to default logging.
      CRM_Core_Error::debug_log_message($message, FALSE, '', $this->map[$level]);
    }
  }

}
