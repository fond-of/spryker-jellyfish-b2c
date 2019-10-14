<?php

namespace FondOfSpryker\Zed\JellyfishB2C\Business;

use FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface;
use FondOfSpryker\Zed\Jellyfish\Dependency\Service\JellyfishToUtilEncodingServiceInterface;
use FondOfSpryker\Zed\JellyfishB2C\Business\Api\Adapter\CompanyBusinessUnitAdapter;
use FondOfSpryker\Zed\JellyfishB2C\Business\Api\Adapter\CompanyUserAdapter;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Checker\CompanyUnitAddressChecker;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Checker\CompanyUnitAddressCheckerInterface;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Exporter\CompanyUnitAddressExporter;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Exporter\ExporterInterface;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapper;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyMapper;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyMapperInterface;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyUnitAddressMapper;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyUserMapper;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyUserMapperInterface;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCustomerMapper;
use FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCustomerMapperInterface;
use FondOfSpryker\Zed\JellyfishB2C\Communication\Plugin\JellyfishCompanyBusinessUnitAddressExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2C\Communication\Plugin\JellyfishCompanyBusinessUnitCompanyExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2C\Communication\Plugin\JellyfishCompanyBusinessUnitDataExpanderPlugin;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyUnitAddressFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyUserFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCustomerFacadeInterface;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface;
use FondOfSpryker\Zed\JellyfishB2C\JellyfishB2CDependencyProvider;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \FondOfSpryker\Zed\JellyfishB2C\JellyfishB2CConfig getConfig()
 */
class JellyfishB2CBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return void
     */
    public function createUserExporter()
    {
    }

    /*public function createCompanyExporter(): ExporterInterface
    {
        return new CompanyExporter(
            $this->getCompanyFacade(),
            $this->createCompanyBusinessUnitMapper(),
            $this->createCompanyExporterExpanderPlugins(),
            $this->createCompanyBusinessUnitAdapter()
        );
    }*/

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyUserMapperInterface
     */
    protected function createCompanyUserMapper(): JellyfishCompanyUserMapperInterface
    {
        return new JellyfishCompanyUserMapper(
            $this->createCompanyBusinessUnitMapper(),
            $this->createCompanyMapper(),
            $this->createCustomerMapper()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Exporter\ExporterInterface
     */
    public function createCompanyUnitAddressExporter(): ExporterInterface
    {
        return new CompanyUnitAddressExporter(
            $this->getCompanyUnitAddressFacade(),
            $this->createCompanyBusinessUnitMapper(),
            $this->createCompanyExporterExpanderPlugins(),
            $this->createCompanyBusinessUnitAdapter()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCustomerMapperInterface
     */
    public function createCustomerMapper(): JellyfishCustomerMapperInterface
    {
        return new JellyfishCustomerMapper();
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyBusinessUnitMapperInterface
     */
    protected function createCompanyBusinessUnitMapper(): JellyfishCompanyBusinessUnitMapperInterface
    {
        return new JellyfishCompanyBusinessUnitMapper(
            $this->createCompanyMapper(),
            $this->createCompanyUnitAddressMapper(),
            $this->createCompanyUnitAddressChecker()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyMapperInterface
     */
    protected function createCompanyMapper(): JellyfishCompanyMapperInterface
    {
        return new JellyfishCompanyMapper();
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Mapper\JellyfishCompanyUnitAddressMapperInterface
     */
    protected function createCompanyUnitAddressMapper(): JellyfishCompanyUnitAddressMapperInterface
    {
        return new JellyfishCompanyUnitAddressMapper();
    }

    /**
     * @return array
     */
    protected function createCompanyExporterExpanderPlugins(): array
    {
        return [
            $this->createCompanyBusinessUnitDataExpanderPlugin(),
            $this->createCompanyBusinessUnitAddressExpanderPlugin(),
            $this->createCompanyBusinessUnitCompanyExpanderPlugin(),
        ];
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface
     */
    protected function createCompanyBusinessUnitDataExpanderPlugin(): JellyfishCompanyBusinessUnitExpanderPluginInterface
    {
        return new JellyfishCompanyBusinessUnitDataExpanderPlugin(
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyUnitAddressFacade(),
            $this->createCompanyBusinessUnitMapper()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface
     */
    protected function createCompanyBusinessUnitAddressExpanderPlugin(): JellyfishCompanyBusinessUnitExpanderPluginInterface
    {
        return new JellyfishCompanyBusinessUnitAddressExpanderPlugin(
            $this->getCompanyUnitAddressFacade(),
            $this->createCompanyUnitAddressMapper(),
            $this->createCompanyUnitAddressChecker()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Business\Model\Checker\CompanyUnitAddressCheckerInterface
     */
    protected function createCompanyUnitAddressChecker(): CompanyUnitAddressCheckerInterface
    {
        return new CompanyUnitAddressChecker();
    }

    /**
     * @return \FondOfSpryker\Zed\JellyfishB2C\Dependency\Plugin\JellyfishCompanyBusinessUnitExpanderPluginInterface
     */
    protected function createCompanyBusinessUnitCompanyExpanderPlugin(): JellyfishCompanyBusinessUnitExpanderPluginInterface
    {
        return new JellyfishCompanyBusinessUnitCompanyExpanderPlugin(
            $this->getCompanyBusinessUnitFacade(),
            $this->createCompanyMapper()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface
     */
    protected function createCompanyBusinessUnitAdapter(): AdapterInterface
    {
        return new CompanyBusinessUnitAdapter(
            $this->getUtilEncodingService(),
            $this->createHttpClient(),
            $this->getConfig()->getUsername(),
            $this->getConfig()->getPassword(),
            $this->getConfig()->dryRun()
        );
    }

    /**
     * @return \FondOfSpryker\Zed\Jellyfish\Business\Api\Adapter\AdapterInterface
     */
    protected function createCompanyUserAdapter(): AdapterInterface
    {
        return new CompanyUserAdapter(
            $this->getUtilEncodingService(),
            $this->createHttpClient(),
            $this->getConfig()->getUsername(),
            $this->getConfig()->getPassword(),
            $this->getConfig()->dryRun()
        );
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    protected function createHttpClient(): HttpClientInterface
    {
        return new HttpClient([
            'base_uri' => $this->getConfig()->getBaseUri(),
            'timeout' => $this->getConfig()->getTimeout(),
        ]);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\Jellyfish\Dependency\Service\JellyfishToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): JellyfishToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(JellyfishB2CDependencyProvider::UTIL_ENCODING_SERVICE);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyFacadeInterface
     */
    protected function getCompanyFacade(): JellyfishB2CToCompanyFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2CDependencyProvider::COMPANY_FACADE);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade(): JellyfishB2CToCompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2CDependencyProvider::COMPANY_BUSINESS_UNIT_FACADE);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyUnitAddressFacadeInterface
     */
    protected function getCompanyUnitAddressFacade(): JellyfishB2CToCompanyUnitAddressFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2CDependencyProvider::COMPANY_UNIT_ADDRESS_FACADE);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCustomerFacadeInterface
     */
    protected function getCustomerFacade(): JellyfishB2CToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2CDependencyProvider::CUSTOMER_FACADE);
    }

    /**
     * @throws
     *
     * @return \FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyUserFacadeInterface
     */
    protected function getCompanyUserFacade(): JellyfishB2CToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(JellyfishB2CDependencyProvider::COMPANY_USER_FACADE);
    }
}
