<?php
/**
 * Pmclain_AuthorizenetCim extension
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GPL v3 License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://www.gnu.org/licenses/gpl.txt
 *
 * @category  Pmclain
 * @package   Pmclain_AuthorizenetCim
 * @copyright Copyright (c) 2017
 * @license   https://www.gnu.org/licenses/gpl.txt GPL v3 License
 */

namespace Pmclain\AuthorizenetCim\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;
use Pmclain\AuthorizenetCim\Gateway\Helper\SubjectReader;
use net\authorize\api\contract\v1\CustomerAddressType;

class AddressDataBuilder implements BuilderInterface
{
  /** @var SubjectReader */
  protected $_subjectReader;

  /** @var CustomerAddressType */
  protected $_customerAddress;

  public function __construct(
    SubjectReader $subjectReader,
    CustomerAddressType $customerAddressType
  ) {
    $this->_subjectReader = $subjectReader;
    $this->_customerAddress = $customerAddressType;
  }

  public function build(array $buildSubject)
  {
    $paymentDataObject = $this->_subjectReader->readPayment($buildSubject);

    $order = $paymentDataObject->getOrder();
    $billingAddress = $order->getBillingAddress();

    $this->_customerAddress->setFirstName($billingAddress->getFirstname());
    $this->_customerAddress->setLastName($billingAddress->getLastname());
    $this->_customerAddress->setCompany($billingAddress->getCompany());
    $this->_customerAddress->setAddress($billingAddress->getStreetLine1());
    $this->_customerAddress->setCity($billingAddress->getCity());
    $this->_customerAddress->setState($billingAddress->getRegionCode());
    $this->_customerAddress->setZip($billingAddress->getPostcode());
    $this->_customerAddress->setCountry($billingAddress->getCountryId());
    $this->_customerAddress->setPhoneNumber($billingAddress->getTelephone());
    $this->_customerAddress->setEmail($billingAddress->getEmail());

    return ['bill_to_address' => $this->_customerAddress];
  }
}