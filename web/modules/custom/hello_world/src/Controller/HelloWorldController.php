<?php

namespace Drupal\hello_world\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hello_world\HelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
}
