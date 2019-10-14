<?php

namespace FondOfSpryker\Zed\JellyfishB2C;

use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyBusinessUnitFacadeBridge;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyFacadeBridge;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyUnitAddressFacadeBridge;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCompanyUserFacadeBridge;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Facade\JellyfishB2CToCustomerFacadeBridge;
use FondOfSpryker\Zed\JellyfishB2C\Dependency\Service\JellyfishB2CToUtilEncodingServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class JellyfishB2CDependencyProvider extends AbstractBundleDependencyProvider
{
    public const UTIL_ENCODING_SERVICE = 'UTIL_ENCODING_SERVICE';
    public const COMPANY_FACADE = 'COMPANY_FACADE';
    public const COMPANY_BUSINESS_UNIT_FACADE = 'COMPANY_BUSINESS_UNIT_FACADE';
    public const COMPANY_UNIT_ADDRESS_FACADE = 'COMPANY_UNIT_ADDRESS_FACADE';
    public const CUSTOMER_FACADE = 'CUSTOMER_FACADE';
    public const COMPANY_USER_FACADE = 'COMPANY_USER_FACADE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilEncodingService($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addCompanyUnitAddressFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addCompanyUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::UTIL_ENCODING_SERVICE] = function (Container $container) {
            return new JellyfishB2CToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container): Container
    {
        $container[static::COMPANY_FACADE] = function (Container $container) {
            return new JellyfishB2CToCompanyFacadeBridge(
                $container->getLocator()->company()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container[static::COMPANY_BUSINESS_UNIT_FACADE] = function (Container $container) {
            return new JellyfishB2CToCompanyBusinessUnitFacadeBridge(
                $container->getLocator()->companyBusinessUnit()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressFacade(Container $container): Container
    {
        $container[static::COMPANY_UNIT_ADDRESS_FACADE] = function (Container $container) {
            return new JellyfishB2CToCompanyUnitAddressFacadeBridge(
                $container->getLocator()->companyUnitAddress()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container[static::CUSTOMER_FACADE] = function (Container $container) {
            return new JellyfishB2CToCustomerFacadeBridge(
                $container->getLocator()->customer()->facade()
            );
        };

        return $container;
    }


    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container[static::COMPANY_USER_FACADE] = function (Container $container) {
            return new JellyfishB2CToCompanyUserFacadeBridge(
                $container->getLocator()->companyUser()->facade()
            );
        };

        return $container;
    }
}
