<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Communication\Plugin;

use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyMapperInterface;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2C\Business\JellyfishB2CFacadeInterface getFacade()
 */
class JellyfishCompanyBusinessUnitCompanyExpanderPlugin extends AbstractPlugin implements JellyfishCompanyBusinessUnitExpanderPluginInterface
{
    /**
     * @var \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyMapperInterface
     */
    protected $jellyfishCompanyMapper;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyMapperInterface $jellyfishCompanyMapper
     */
    public function __construct(
        JellyfishB2CToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        JellyfishCompanyMapperInterface $jellyfishCompanyMapper
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->jellyfishCompanyMapper = $jellyfishCompanyMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    public function expand(JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer
    {

        if ($jellyfishCompanyBusinessUnitTransfer->getId() === null) {
            return $jellyfishCompanyBusinessUnitTransfer;
        }

        $companyBusinessUnitTransfer = $this->companyBusinessUnitFacade->getCompanyBusinessUnitById(
            (new CompanyBusinessUnitTransfer())
                ->setIdCompanyBusinessUnit($jellyfishCompanyBusinessUnitTransfer->getId())
        );

        $jellyfishCompanyTransfer = $this->jellyfishCompanyMapper
            ->fromCompany($companyBusinessUnitTransfer->getCompany());

        $jellyfishCompanyBusinessUnitTransfer->setCompany($jellyfishCompanyTransfer);

        return $jellyfishCompanyBusinessUnitTransfer;
    }
}
