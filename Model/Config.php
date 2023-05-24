<?php
/**
 * @author Bytes Technolab Team
 * @copyright Copyright (c) 2021 Bytes Technolab (https://www.bytestechnolab.com/)
 * @package BytesTechnolab_Base
 */
declare(strict_types = 1);

namespace BytesTechnolab\Base\Model;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Config
 * @package BytesTechnolab\Base\Model
 */
class Config {

	/**
	 * @var ScopeConfigInterface;
	 */
	protected $scopeConfig;

	/**
	 * @var StoreManagerInterface;
	 */
	protected $storeManager;

	public function __construct(
		ScopeConfigInterface $scopeConfig,
		StoreManagerInterface $storeManager
	) {
		$this->scopeConfig = $scopeConfig;
		$this->storeManager = $storeManager;
	}

	/**
	 * Get Store ConfigValue
	 *
	 * @param  string $field
	 *
	 * @return string
	 */
	public function getConfigValue($field) : string {
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $this->getStoreId()
		);
	}

	/**
	 * Get store id
	 * 
	 * @return string
	 */
	public function getStoreId() : string
	{
		return $this->storeManager->getStore()->getStoreId();
	}
}