<?php
/**
 * Class Billet
 *
 * @author      MundiPagg Embeddables Team <embeddables@mundipagg.com>
 * @copyright   2017 MundiPagg (http://www.mundipagg.com)
 * @license     http://www.mundipagg.com Copyright
 *
 * @link        http://www.mundipagg.com
 */

namespace MundiPagg\MundiPagg\Block\Payment\Info;

use Magento\Payment\Block\Info\Cc;
use Mundipagg\Core\Kernel\Services\OrderService;
use Mundipagg\Core\Kernel\ValueObjects\Id\OrderId;
use MundiPagg\MundiPagg\Concrete\Magento2CoreSetup;

class TwoCreditCard extends Cc
{
    const TEMPLATE = 'MundiPagg_MundiPagg::info/twoCreditCard.phtml';

    public function _construct()
    {
        $this->setTemplate(self::TEMPLATE);
    }

    public function getCcType()
    {
        return $this->getCcTypeName();
    }

    public function getCardNumber()
    {
        return '**** **** **** ' . $this->getInfo()->getCcLast4();
    }

    public function getTitle()
    {
        return $this->getInfo()->getAdditionalInformation('method_title');
    }

    public function getInstallments()
    {
        return $this->getInfo()->getAdditionalInformation('cc_installments');
    }

    public function getInstallmentsFirstCard()
    {
        return $this->getInfo()->getAdditionalInformation('cc_installments_first');
    }

    public function getCcTypeFirst()
    {
        return $this->getInfo()->getAdditionalInformation('cc_type_first');
    }

    public function getFirstCardAmount()
    {
        return (float) $this->getInfo()->getAdditionalInformation('cc_first_card_amount') + (float) $this->getInfo()->getAdditionalInformation('cc_first_card_tax_amount');
    }

    public function getFirstCardLast4()
    {
        return '**** **** **** ' . $this->getInfo()->getAdditionalInformation('cc_last_4_first');
    }

    public function getInstallmentsSecondCard()
    {
        return $this->getInfo()->getAdditionalInformation('cc_installments_second');
    }

    public function getCcTypeSecond()
    {
        return $this->getInfo()->getAdditionalInformation('cc_type_second');
    }

    public function getSecondCardAmount()
    {
        return (float) $this->getInfo()->getAdditionalInformation('cc_second_card_amount') + (float) $this->getInfo()->getAdditionalInformation('cc_second_card_tax_amount');
    }

    public function getSecondCardLast4()
    {
        return '**** **** **** ' . $this->getInfo()->getAdditionalInformation('cc_last_4_second');
    }

    public function getTransactionInfo()
    {
        Magento2CoreSetup::bootstrap();
        $orderService = new OrderService();

        $orderId = $this->getInfo()->getLastTransId();
        $orderId = explode('-', $orderId)[0];

        /**
         * @var \Mundipagg\Core\Kernel\Aggregates\Order orderObject
         */
        $orderObject = $orderService->getOrderByMundiPaggId(new OrderId($orderId));

        return [
            'card1' => $orderObject->getCharges()[0]->getLastTransaction(),
            'card2' => $orderObject->getCharges()[1]->getLastTransaction()
        ];
    }
}