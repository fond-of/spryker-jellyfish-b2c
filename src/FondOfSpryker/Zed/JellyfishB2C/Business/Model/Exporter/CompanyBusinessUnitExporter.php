<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Business\Model\Exporter;

use FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeInterface;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\Log\LoggerTrait;

class CompanyBusinessUnitExporter implements ExporterInterface
{
    use LoggerTrait;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface
     */
    protected $jellyfishCompanyBusinessUnitMapper;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @var \FondOfSpryker\Zed\JellyfishB2C\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface[]
     */
    protected $jellyfishCompanyBusinessUnitExpanderPlugins;

    /**
     * @var \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @param \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     * @param \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper
     * @param \FondOfSpryker\Zed\JellyfishB2C\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface[] $jellyfishCompanyBusinessUnitExpanderPlugins
     * @param \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface $adapter
     */
    public function __construct(
        JellyfishB2CToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade,
        JellyfishCompanyBusinessUnitMapperInterface $jellyfishCompanyBusinessUnitMapper,
        array $jellyfishCompanyBusinessUnitExpanderPlugins,
        AdapterInterface $adapter
    ) {
        $this->jellyfishCompanyBusinessUnitMapper = $jellyfishCompanyBusinessUnitMapper;
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
        $this->jellyfishCompanyBusinessUnitExpanderPlugins = $jellyfishCompanyBusinessUnitExpanderPlugins;
        $this->adapter = $adapter;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $transfers
     *
     * @return void
     */
    public function exportBulk(array $transfers): void
    {
        foreach ($transfers as $transfer) {
            if (!$this->canExport($transfer)) {
                continue;
            }

            $this->exportById($transfer->getId());
        }
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transfer
     *
     * @return bool
     */
    protected function canExport(TransferInterface $transfer): bool
    {
        return $transfer instanceof EventEntityTransfer &&
            count($transfer->getModifiedColumns()) > 0 &&
            $transfer->getName() === 'spy_company_business_unit';
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function map(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): JellyfishCompanyBusinessUnitTransfer
    {
        return $this->jellyfishCompanyBusinessUnitMapper->fromCompanyBusinessUnit($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\JellyfishCompanyBusinessUnitTransfer
     */
    protected function expand(
        JellyfishCompanyBusinessUnitTransfer $jellyfishCompanyBusinessUnitTransfer
    ): JellyfishCompanyBusinessUnitTransfer {
        foreach ($this->jellyfishCompanyBusinessUnitExpanderPlugins as $jellyfishCompanyBusinessUnitExpanderPlugin) {
            $jellyfishCompanyBusinessUnitTransfer = $jellyfishCompanyBusinessUnitExpanderPlugin
                ->expand($jellyfishCompanyBusinessUnitTransfer);
        }

        return $jellyfishCompanyBusinessUnitTransfer;
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function exportById(int $id): void
    {
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($id);

        $companyBusinessUnitTransfer = $this->companyBusinessUnitFacade
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);

        if ($companyBusinessUnitTransfer === null || $companyBusinessUnitTransfer->getIdCompanyBusinessUnit() === null) {
            return;
        }

        $jellyfishCompanyBusinessUnitTransfer = $this->map($companyBusinessUnitTransfer);
        $jellyfishCompanyBusinessUnitTransfer = $this->expand($jellyfishCompanyBusinessUnitTransfer);

        $this->adapter->sendRequest($jellyfishCompanyBusinessUnitTransfer);
    }
}
