<?php

namespace Drupal\social_auth_instagram\Plugin\Network;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\social_auth_instagram\InstagramAuthPersistentDataHandler;
use Drupal\social_api\Plugin\NetworkBase;
use Drupal\social_api\SocialApiException;
use Drupal\social_auth_instagram\Settings\InstgramAuthSettings;
use Symfony\Component\DependencyInjection\ContainerInterface;
use League\OAuth2\Client\Provider\Instagram;

/**
 * Defines a Network Plugin for Social Auth Instagram.
 *
 * @package Drupal\simple_fb_connect\Plugin\Network
 *
 * @Network(
 *   id = "social_auth_instagram",
 *   social_network = "Instagram",
 *   type = "social_auth",
 *   handlers = {
 *     "settings": {
 *       "class": "\Drupal\social_auth_instagram\Settings\InstagramAuthSettings",
 *       "config_id": "social_auth_instagram.settings"
 *     }
 *   }
 * )
 */
class InstagramAuth extends NetworkBase implements InstagramAuthInterface {

  /**
   * The Instagram Persistent Data Handler.
   *
   * @var \Drupal\social_auth_instagram\InstagramAuthPersistentDataHandler
   */
  protected $persistentDataHandler;

  /**
   * The logger factory.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $loggerFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('social_auth_instagram.persistent_data_handler'),
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('config.factory'),
      $container->get('logger.factory')
    );
  }

  /**
   * InstagramAuth constructor.
   *
   * @param \Drupal\social_auth_instagram\InstagramAuthPersistentDataHandler $persistent_data_handler
   *   The persistent data handler.
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param array $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory object.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger factory.
   */
  public function __construct(InstagramAuthPersistentDataHandler $persistent_data_handler,
                              array $configuration,
                              $plugin_id,
                              array $plugin_definition,
                              EntityTypeManagerInterface $entity_type_manager,
                              ConfigFactoryInterface $config_factory,
                              LoggerChannelFactoryInterface $logger_factory) {

    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $config_factory);

    $this->persistentDataHandler = $persistent_data_handler;
    $this->loggerFactory = $logger_factory;
  }

  /**
   * Sets the underlying SDK library.
   *
   * @return \Instagram\Instagram
   *   The initialized 3rd party library instance.
   *
   * @throws SocialApiException
   *   If the SDK library does not exist.
   */
  protected function initSdk() {

    $class_name = '\League\OAuth2\Client\Provider\Instagram';
    if (!class_exists($class_name)) {
      throw new SocialApiException(sprintf('The Instagram Library for the league oAuth not found. Class: %s.', $class_name));
    }
    /* @var \Drupal\social_auth_instagram\Settings\InstagramAuthSettings $settings */
    $settings = $this->settings;

    if ($this->validateConfig($settings)) {
      // All these settings are mandatory.
      $league_settings = [
        'clientId'          => $settings->getAppId(),
        'clientSecret'      => $settings->getAppSecret(),
        'redirectUri'       => $GLOBALS['base_url'] . '/user/login/instagram/callback'
      ];

      return new Instagram($league_settings);
    }
    return FALSE;
  }

  /**
   * Checks that module is configured.
   *
   * @param \Drupal\social_auth_instagram\Settings\InstagramAuthSettings $settings
   *   The Instagram auth settings.
   *
   * @return bool
   *   True if module is configured.
   *   False otherwise.
   */
  protected function validateConfig(InstagramAuthSettings $settings) {
    $app_id = $settings->getAppId();

    if (!$app_id || !$app_secret ) {
      $this->loggerFactory
        ->get('social_auth_instagram')
        ->error('Define App ID and App Secret on module settings.');
      return FALSE;
    }

    return TRUE;
  }

}
