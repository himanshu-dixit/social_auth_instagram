<?php

namespace Drupal\social_auth_instagram\Settings;

use Drupal\social_api\Settings\SettingsBase;

/**
 * Defines methods to get Social Auth Facebook app settings.
 */
class InstagramAuthSettings extends SettingsBase implements InstagramAuthSettingsInterface {

  /**
   * Application ID.
   *
   * @var string
   */
  protected $appId;

  /**
   * Application secret.
   *
   * @var string
   */
  protected $appSecret;


  /**
   * The default access token.
   *
   * @var string
   */
  protected $defaultToken;

  /**
   * The redirect URL for social_auth implmeneter.
   *
   * @var string
   */
  protected $oauthRedirectUrl;

  /**
   * {@inheritdoc}
   */
  public function getAppId() {
    if (!$this->appId) {
      $this->appId = $this->config->get('app_id');
    }
    return $this->appId;
  }

  /**
   * {@inheritdoc}
   */
  public function getAppSecret() {
    if (!$this->appSecret) {
      $this->appSecret = $this->config->get('app_secret');
    }
    return $this->appSecret;
  }


}
