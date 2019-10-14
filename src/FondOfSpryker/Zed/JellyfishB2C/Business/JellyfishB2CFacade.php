<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2C\Business\JellyfishB2CBusinessFactory getFactory()
 */
class JellyfishB2CFacade extends AbstractFacade implements JellyfishB2CFacadeInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportUserBulk(array $transfers): void
    {
        $this->getFactory()->createUserExporter();
    }
}
