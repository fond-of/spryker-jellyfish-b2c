<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Communication\Plugin\Event\Subscriber;

use FondOfSpryker\Zed\JellyfishB2C\Communication\Plugin\Event\Listener\UserExportListener;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\JellyfishB2CEvents;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2C\Business\JellyfishB2CFacadeInterface getFacade()
 * @method \FondOfSpryker\Zed\JellyfishB2C\JellyfishB2CConfig getConfig()
 */
class JellyfishB2CEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $eventCollection->addListenerQueued(
            JellyfishB2CEvents::ENTITY_SPY_USER_CREATE,
            new UserExportListener()
        );

        $eventCollection->addListenerQueued(
            JellyfishB2CEvents::ENTITY_SPY_USER_UPDATE,
            new UserExportListener()
        );

        return $eventCollection;
    }
}
