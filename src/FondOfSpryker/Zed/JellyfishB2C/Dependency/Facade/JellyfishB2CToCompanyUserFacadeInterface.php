<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface JellyfishB2CToCompanyUserFacadeInterface
{
    /**
     * @param int $idCompanyUser
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCompanyUserById(int $idCompanyUser): CompanyUserTransfer;
}
