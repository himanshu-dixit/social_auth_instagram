<?php

namespace Drupal\social_auth_instagram\Settings;

/**
 * Defines the settings interface.
 */
interface InstagramAuthSettingsInterface {

  /**
   * Gets the application ID.
   *
   * @return mixed
   *   The application ID.
   */
  public function getAppId();

  /**
   * Gets the application secret.
   *
   * @return string
   *   The application secret.
   */
  public function getAppSecret();


}
