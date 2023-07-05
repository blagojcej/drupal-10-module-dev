<?php

namespace Drupal\hello_world\EventSubscriber;

use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\hello_world\Form\SalutationConfigurationForm;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Log message when the Hello World configuration is changed.
 */
class HelloWorldConfigChangedSubscriber implements EventSubscriberInterface
{
    /**
     * The logger.
     *
     * @var \Drupal\Core\Logger\LoggerChannelInterface
     */
    protected $logger;

    public function __construct(LoggerChannelInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        $events[ConfigEvents::SAVE][] = ['onConfigSave', -100];
        return $events;
    }

    /**
     * Handler for the Save ConfigEvent
     * 
     * @param Drupal\Core\Config\ConfigCrudEvent $event
     * The ConfigCrudEvent
     */
    public function onConfigSave(ConfigCrudEvent $event)
    {
        if ($event->isChanged('salutation')) {
            $config = $event->getConfig();
            $this->logger->info(
                'The Hello World salutation config has been changed to @message.',
                ['@message' => $config->get('salutation')]
            );
        }
    }
}
