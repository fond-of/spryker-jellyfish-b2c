<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Communication\Plugin\Event\Listener;

use FondOfSpryker\Zed\JellyfishB2C\Dependency\JellyfishB2CEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2C\Business\JellyfishB2CFacadeInterface getFacade()
 */
class UserExportListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * Specification
     *  - Listeners needs to implement this interface to execute the codes for more
     *  than one event at same time (Bulk Operation)
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName): void
    {
        if ($eventName === JellyfishB2CEvents::ENTITY_SPY_USER_CREATE) {
            $this->getFacade()->exportUserBulk($transfers);
        }

        if ($eventName === JellyfishB2CEvents::ENTITY_SPY_USER_UPDATE) {
            $this->getFacade()->exportUserBulk($transfers);
        }
    }
}
