<?php
/**
 * @author Bytes Technolab Team
 * @copyright Copyright (c) 2021 Bytes Technolab (https://www.bytestechnolab.com/)
 * @package BytesTechnolab_Base
 */

declare(strict_types = 1);

namespace BytesTechnolab\Base\Plugin;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Closure;

class CustomerSessionContext 
{
	/**
	 * @var HttpContext
	 */
	protected $httpContext;

	/**
	 * @var Session
	 */
	protected $customerSession;

	public function __construct(
		HttpContext $httpContext,
		Session $customerSession
	){
		$this->httpContext = $httpContext;
		$this->customerSession = $customerSession;
	}

	/**
	 * @param \Magento\Framework\App\ActionInterface $subject
	 * @param callable $proceed
	 * @param \Magento\Framework\App\RequestInterface $request
	 * @return mixed
	 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
	 */
	public function aroundDispatch(
		ActionInterface $subject,
		Closure $proceed,
		RequestInterface $request
	) : mixed {
		$this->httpContext->setValue(
			'customer_id',
			$this->customerSession->getCustomerId(),
			false
		);

		$this->httpContext->setValue(
			'customer_name',
			$this->customerSession->getCustomer()->getName(),
			false
		);

		$this->httpContext->setValue(
			'customer_email',
			$this->customerSession->getCustomer()->getEmail(),
			false
		);

		return $proceed($request);
	}
}