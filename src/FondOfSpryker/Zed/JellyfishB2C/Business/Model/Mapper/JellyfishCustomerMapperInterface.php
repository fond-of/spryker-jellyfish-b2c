<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\JellyfishCustomerTransfer;

interface JellyfishCustomerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCustomerTransfer
     */
    public function fromCustomer(
        CustomerTransfer $customerTransfer
    ): JellyfishCustomerTransfer;
}
