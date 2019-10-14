<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Business;

interface JellyfishB2CFacadeInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportUserBulk(array $transfers): void;
}
