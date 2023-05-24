<?php
/**
 * @author Bytes Technolab Team
 * @copyright Copyright (c) 2021 Bytes Technolab (https://www.bytestechnolab.com/)
 * @package BytesTechnolab_Base
 */
declare(strict_types = 1);

namespace BytesTechnolab\Base\Model;

use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Area;
use Exception;
use BytesTechnolab\Base\Model\Config;

/**
 * Class Mail
 * @package BytesTechnolab\Base\Model
 */
class Mail {

	/**
	 * @var StateInterface
	 */
	protected $inlineTranslation;

	/**
	 * @var TransportBuilder
	 */
	protected $transportBuilder;

	/**
	 * @var ManagerInterface
	 */
	protected $messageManager;

	/**
	 * @var Config
	 */
	protected $config;	

	public function __construct(
		StateInterface $inlineTranslation,
		TransportBuilder $transportBuilder,
		ManagerInterface $messageManager,
		Config $config
	) {
		$this->inlineTranslation = $inlineTranslation;
		$this->transportBuilder = $transportBuilder;
		$this->messageManager = $messageManager;
		$this->config = $config;
	}

	/**
     * Method to send email.
     *
     * @param array $post Post data from contact form
     *
     * @return boolean
     */
	public function send($toEmail, $toEmailName, $senderEmail, $senderName, $templateId, $templateVars): bool{
		if (!isset($senderEmail) && !isset($senderName) && !$senderEmail && !$senderName) {
			return false;
		}
		$from['name'] = $senderName;
		$from['email'] = $senderEmail;
		$this->inlineTranslation->suspend();
		$this->transportBuilder->setTemplateIdentifier($templateId)
			->setTemplateOptions(
				[
					'area' => Area::AREA_FRONTEND,
					'store' => $this->config->getStoreId(),
				]
			)
			->setTemplateVars($templateVars)
			->setFromByScope($from)
			->addTo($toEmail, $toEmailName);
		try {
			$transport = $this->transportBuilder->getTransport();
			$transport->sendMessage();
			$this->inlineTranslation->resume();
			return true;
		} catch (Exception $e) {
			$this->messageManager->addErrorMessage($e->getMessage());
			return false;
		}
	}
}