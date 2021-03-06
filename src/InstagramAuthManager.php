<?php

namespace Drupal\social_auth_instagram;

use Drupal\social_auth\AuthManager\OAuth2Manager;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Contains all the logic for Instagram login integration.
 */
class InstagramAuthManager extends OAuth2Manager {

  /**
   * The logger channel.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactoryInterface
   */
  protected $loggerFactory;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The url generator.
   *
   * @var \Drupal\Core\Routing\UrlGeneratorInterface
   */
  protected $urlGenerator;

  /**
   * The Instagram client object.
   *
   * @var \League\OAuth2\Client\Provider\Instagram
   */
  protected $client;
  /**
   * The Instagram access token.
   *
   * @var \League\OAuth2\Client\Provider\Instagram
   */
  protected $token;

  /**
   * The Instagram access token.
   *
   * @var \League\OAuth2\Client\Provider\Instagram
   */
  protected $user;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   Used for logging errors.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   Used for dispatching events to other modules.
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   Used for accessing Drupal user picture preferences.
   * @param \Drupal\Core\Routing\UrlGeneratorInterface $url_generator
   *   Used for generating absoulute URLs.
   */
  public function __construct(LoggerChannelFactoryInterface $logger_factory, EventDispatcherInterface $event_dispatcher, EntityFieldManagerInterface $entity_field_manager, UrlGeneratorInterface $url_generator) {
    $this->loggerFactory         = $logger_factory;
    $this->eventDispatcher       = $event_dispatcher;
    $this->entityFieldManager    = $entity_field_manager;
    $this->urlGenerator          = $url_generator;
  }

  /**
   * Authenticates the users by using the access token.
   *
   * @return $this
   *   The current object.
   */
  public function authenticate() {
    $this->token = $this->client->getAccessToken('authorization_code',
      ['code' => $_GET['code']]);

    return $this->token;
  }

  /**
   * Gets the data by using the access token returned.
   *
   * @return array
   *   User Info returned by the Instagram.
   */
  public function getUserInfo() {
    $this->user = $this->client->getResourceOwner($this->token);
    return $this->user;
  }

  /**
   * Returns the Instagram login URL where user will be redirected.
   *
   * @return string
   *   Absolute Instagram login URL where user will be redirected
   */
  public function getInstagramLoginUrl($data_points) {
    $scopes = $this->checkForScopes($data_points);
    $login_url = $this->client->getAuthorizationUrl($scopes);

    // Generate and return the URL where we should redirect the user.
    return $login_url;
  }

  /**
   * Returns scopes required for data point defined by administator.
   *
   * @return array
   *   scopes for authorization URL.
   */
  protected function checkForScopes($data_points) {
    $scopes = [];

    // Scopes required for data point.
    $scopeForDataPoint = [
      "name"   => '',
      "email"  => '',
    ];

    foreach ($data_points as $data_point) {
      $scope = $scopeForDataPoint[$data_point];
      // If scope is not in array, then add it.
      if (!in_array($scope, $scopes)) {
        array_push($scopes, $scope);
      }
    }

    return $scopes;
  }

  /**
   * Returns the Instagram login URL where user will be redirected.
   *
   * @return string
   *   Absolute Instagram login URL where user will be redirected
   */
  public function getState() {
    $state = $this->client->getState();

    // Generate and return the URL where we should redirect the user.
    return $state;
  }

  /**
   * Determines preferred profile pic resolution from account settings.
   *
   * Return order: max resolution, min resolution, FALSE.
   *
   * @return array|false
   *   Array of resolution, if defined in Drupal account settings
   *   False otherwise
   */
  protected function getPreferredResolution() {
    $field_definitions = $this->entityFieldManager->getFieldDefinitions('user', 'user');
    if (!isset($field_definitions['user_picture'])) {
      return FALSE;
    }

    $max_resolution = $field_definitions['user_picture']->getSetting('max_resolution');
    $min_resolution = $field_definitions['user_picture']->getSetting('min_resolution');

    // Return order: max resolution, min resolution, FALSE.
    if ($max_resolution) {
      $resolution = $max_resolution;
    }
    elseif ($min_resolution) {
      $resolution = $min_resolution;
    }
    else {
      return FALSE;
    }
    $dimensions = explode('x', $resolution);
    return ['width' => $dimensions[0], 'height' => $dimensions[1]];
  }

}
