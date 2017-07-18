<?php

namespace Drupal\social_auth_instagram\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Path\PathValidatorInterface;
use Drupal\Core\Routing\RequestContext;
use Drupal\Core\Routing\RouteProviderInterface;
use Drupal\social_auth\Form\SocialAuthSettingsForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Settings form for Social Auth Instagram.
 */
class InstagramAuthSettingsForm extends SocialAuthSettingsForm {

  /**
   * The request context.
   *
   * @var \Drupal\Core\Routing\RequestContext
   */
  protected $requestContext;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Routing\RouteProviderInterface $route_provider
   *   Used to check if route exists.
   * @param \Drupal\Core\Path\PathValidatorInterface $path_validator
   *   Used to check if path is valid and exists.
   * @param \Drupal\Core\Routing\RequestContext $request_context
   *   Holds information about the current request.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RouteProviderInterface $route_provider, PathValidatorInterface $path_validator, RequestContext $request_context) {
    parent::__construct($config_factory, $route_provider, $path_validator);
    $this->requestContext = $request_context;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this class.
    return new static(
    // Load the services required to construct this class.
      $container->get('config.factory'),
      $container->get('router.route_provider'),
      $container->get('path.validator'),
      $container->get('router.request_context')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'social_auth_instagram_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return array_merge(
      parent::getEditableConfigNames(),
      ['social_auth_instagram.settings']
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('social_auth_instagram.settings');

    $form['instagram_settings'] = [
      '#type' => 'details',
      '#title' => $this->t('Instagram Client settings'),
      '#open' => TRUE,
      '#description' => $this->t('You need to first create a Instagram App at <a href="@instagram-dev">@instagram-dev</a>', ['@instagram-dev' => 'https://developers.instagram.com/apps']),
    ];

    $form['instagram_settings']['client_id'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client ID'),
      '#default_value' => $config->get('client_id'),
      '#description' => $this->t('Copy the Client ID of your Instagram App here. This value can be found from your App Dashboard.'),
    ];

    $form['instagram_settings']['client_secret'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Client Secret'),
      '#default_value' => $config->get('client_secret'),
      '#description' => $this->t('Copy the Client Secret of your Instagram App here. This value can be found from your App Dashboard.'),
    ];


    $form['instagram_settings']['data_points'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Data Points To Be Collected'),
      '#default_value' => 'name,email',
      '#description' => $this->t('Define the data point to be stored in database, data points must be separated by comma.'),
    ];

    $form['instagram_settings']['redirect_url'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Valid redirect URIs'),
      '#description' => $this->t('Copy this value to <em>Valid OAuth redirect URIs</em> field of your Instagram App settings.'),
      '#default_value' => $GLOBALS['base_url'] . '/user/login/instagram/callback',
    ];

    $form['instagram_settings']['site_url'] = [
      '#type' => 'textfield',
      '#disabled' => TRUE,
      '#title' => $this->t('Site URL'),
      '#description' => $this->t('Copy this value to <em>Site URL</em> field of your Instagram App settings.'),
      '#default_value' => $GLOBALS['base_url'],
    ];

    return parent::buildForm($form, $form_state);
  }



  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('social_auth_instagram.settings')
      ->set('client_id', $values['client_id'])
      ->set('client_secret', $values['client_secret'])
      ->set('data_points', $values['data_points'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
