<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\hello_world\HelloWorldSalutation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for the salutation message.
 */
class HelloWorldController extends ControllerBase
{

  /**
   * The salutation service.
   *
   * @var \Drupal\hello_world\HelloWorldSalutation
   */
  protected $salutation;

  /**
   * HelloWorldController constructor.
   *
   * @param \Drupal\hello_world\HelloWorldSalutation $salutation
   *   The salutation service.
   */
  public function __construct(HelloWorldSalutation $salutation)
  {
    $this->salutation = $salutation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container)
  {
    return new static(
      $container->get('hello_world.salutation')
    );
  }

  /**
   * Hello World.
   *
   * @return array
   *   Our message.
   */
  public function helloWorld()
  {
    return [
      '#markup' => $this->salutation->getSalutation(),
    ];
  }

  /**
   * Hello World.
   *
   * @return array
   *   The hello world salutation component.
   */
  public function helloWorldComponent()
  {
    return $this->salutation->getSalutationComponent();
  }

  public function twocolumns()
  {
    $layoutPluginManager = \Drupal::service('plugin.manager.core.layout');
    $layout = $layoutPluginManager->createInstance('layout_twocol');
    $regions = [
      'first' => [
        '#markup' => 'my left content',
      ],
      'second' => [
        '#markup' => 'my right content',
      ],
    ];
    return $layout->build($regions);
  }

  /**
   * Handles the access checking.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user account.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   The access result.
   */
  public function access(AccountInterface $account)
  {
    return in_array('content_editor', $account->getRoles()) ? AccessResult::forbidden('Editors are not allowed') : AccessResult::allowed();
  }

  /**
   * Route callback for hiding the Salutation block.
   *
   * Only works for Ajax calls.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The current request.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   The ajax response.
   */
  public function hideBlock(Request $request)
  {
    if (!$request->isXmlHttpRequest()) {
      throw new NotFoundHttpException();
    }

    $response = new AjaxResponse();
    $command = new RemoveCommand('.block-hello-world');
    $response->addCommand($command);
    return $response;
  }
}
