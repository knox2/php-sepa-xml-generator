<?php
/**
 * Created by Ruben Podadera. e-mail: ruben.podadera@gmail.com
 * Date: 2/12/14
 * Time: 12:02 PM
 * Credit Transfer Transactions
 */

namespace SEPA;

/**
 * Class SepaDirectDebitTransaction
 *
 * @package SEPA
 */
class CreditTransferTransaction extends PaymentInfo implements TransactionInterface
{
    const DEFAULT_CURRENCY = 'EUR';
    /**
     * Unique identification as assigned by an instructing party for an instructed party to unambiguously identify
     * the instruction.
     *
     * @var string
     */
    private $InstructionIdentification = '';
    /**
     *Unique identification assigned by the initiating party to unumbiguously identify the transaction.
     * This identification is passed on, unchanged, throughout the entire end-to-end chain.
     *
     * @var string
     */
    private $EndToEndIdentification = '';
    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in
     * the currency as ordered by the initiating party.
     *
     * @var float
     */
    private $InstructedAmount = 0.00;
    /**
     * Credit Bank BIC
     *
     * @var string
     */
    private $BIC = '';
    /**
     * Credit IBAN
     *
     * @var string
     */
    private $IBAN = '';
    /**
     * Information supplied to enable the matching/reconciliation of an entry with the items that the payment is
     * intended to settle, such as commercial invoices in an accounts' receivable system, in an unstructured form.
     * max 140 length
     *
     * @var string
     */
    private $creditInvoice = '';
    /**
     * Creditor Name
     *
     * @var string
     */
    private $creditorName = '';
    private $creditorCountry = '';
    private $currency = '';


     /**
     * Institution Name
     *
     * @var string
     */
    private $institutionName = '';
    
    /**
     * Institution Address
     *
     * @var string
     */
    private $institutionAddress = '';
    /**
     * Clearance Id
     *
     * @var string
     */
    private $clearanceId = '';
    
    /**
     * Uses Beneficiary Codes
     *
     * @var string
     */
    private $usesBeneficiaryCodes = true;

    /**
     * @param $instructionIdentifier
     * @return $this
     */
    public function setInstructionIdentification($instructionIdentifier)
    {
        $this->InstructionIdentification = $instructionIdentifier;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstructionIdentification()
    {
        return $this->InstructionIdentification;
    }


    public function getInstitutionName()
    {
        return $this->institutionName;
    }
    
    public function getInstitutionAddress()
    {
        return $this->institutionAddress;
    }
    
    public function getClearanceId()
    {
        return $this->clearanceId;
    }
    
    public function getCreditorCountry()
    {
        return $this->creditorCountry;
    }

    /**
     * @param $instructionIdentifierEndToEnd
     * @return $this
     */
    public function setEndToEndIdentification($instructionIdentifierEndToEnd)
    {
        $this->EndToEndIdentification = $instructionIdentifierEndToEnd;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndToEndIdentification()
    {
        return $this->EndToEndIdentification;
    }

    /**
     * Amount of money to be moved between the debtor and creditor, before deduction of charges, expressed in
     * the currency as ordered by the initiating party.
     *
     * @param $amount
     * @return $this
     */
    public function setInstructedAmount($amount)
    {
        $this->InstructedAmount = $this->amountToString($amount);
        return $this;
    }

    public function getInstructedAmount()
    {
        return $this->InstructedAmount;
    }

    /**
     * @return string
     */
    public function getBIC()
    {
        return $this->BIC;
    }

    /**
     * Financial institution servicing an account for the creditor.
     * Bank Identifier Code.
     * max length
     *
     * @param $BIC
     * @return $this
     */
    public function setBIC($BIC)
    {
        $this->BIC = $this->removeSpaces($BIC);

        return $this;
    }

    /**
     * @return string
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }

    /**
     * Credit IBAN
     * max  34 length
     *
     * @param $IBAN
     * @return $this
     * @throws \Exception
     */
    public function setIBAN($IBAN)
    {
        $IBAN = $this->removeSpaces($IBAN);

        if (!$this->checkIBAN($IBAN)) {
            //throw new \Exception(ERROR_MSG_DD_IBAN . $this->getInstructionIdentification());
        }
        $this->IBAN = $IBAN;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreditInvoice()
    {
        return $this->creditInvoice;
    }

    /**
     * Credit Invoice
     *
     * @param $invoice
     * @return $this
     * @throws \Exception
     */
    public function setCreditInvoice($invoice)
    {
        $invoice = $this->unicodeDecode($invoice);

        if (!$this->checkStringLength($invoice, 140)) {
            throw new \Exception(ERROR_MSG_DD_INVOICE_NUMBER . $this->getInstructionIdentification());
        }
        $this->creditInvoice = $invoice;
        return $this;
    }

    /**
     * @return string
     */
    public function getCreditorName()
    {
        return $this->creditorName;
    }

    /**
     * Name by which a party is known and which is usually used to identify that party.
     *
     * @param $name
     * @return $this
     * @throws \Exception
     */
    public function setCreditorName($name)
    {
        $name = $this->unicodeDecode($name);

        if (!$this->checkStringLength($name, 70)) {
            throw new \Exception(ERROR_MSG_DD_NAME . $this->getInstructionIdentification());
        }
        $this->creditorName = $name;
        return $this;
    }

    public function setCurrency($currency)
    {
        $this->currency = strtoupper($currency);
        return $this;
    }

    public function getCurrency()
    {
        if (empty($this->currency) || is_null($this->currency)) {
            $this->currency = self::DEFAULT_CURRENCY;
        }
        return $this->currency;
    }
    
    /**
     * @return
     */
    public function setCreditorCountry($country)
    {
        $this->creditorCountry = $country;
        return $this;
    }
    
    /**
     * @return 
     */
    public function setClearanceId($id)
    {
        $this->clearanceId = $id;
        return $this;
    }

    /**
     * @return 
     */
    public function setInstitutionName($institution_name)
    {
        $this->institutionName = $institution_name;
        return $this;
    }
    
    /**
     * @return 
     */
    public function setInstitutionAddress($institution_address)
    {
        $this->institutionAddress = $institution_address;
        return $this;
    }
    
    /**
     * @param $usesBeneficiaryCodes
     * @return $this
     */
    public function setUsesBeneficiaryCode($usesBeneficiaryCodes)
    {
        $this->usesBeneficiaryCodes = $usesBeneficiaryCodes;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsesBeneficiaryCode()
    {
        return $this->usesBeneficiaryCodes;
    }

    public function checkIsValidTransaction()
    {
        if (!$this->getBIC() || !$this->getIBAN() || !$this->getCreditorName()) {
            return false;
        }
        return true;
    }

    public function getSimpleXMLElementTransaction()
    {
        $creditTransferTransactionInformation = new \SimpleXMLElement('<CdtTrfTxInf></CdtTrfTxInf>');

        $paymentIdentification = $creditTransferTransactionInformation->addChild('PmtId');
        $paymentIdentification->addChild('InstrId', $this->getInstructionIdentification());
        $paymentIdentification->addChild('EndToEndId', $this->getEndToEndIdentification());

        $amount = $creditTransferTransactionInformation->addChild('Amt');
        $amount->addChild('InstdAmt', $this->getInstructedAmount())
            ->addAttribute('Ccy', $this->getCurrency());
        
        if($this->getUsesBeneficiaryCode()){
            $creditor = $creditTransferTransactionInformation->addChild("Cdtr");
            $creditor_id = $creditor->addChild("Id");
            $creditor_org_id = $creditor_id->addChild("OrgId");
            $creditor_othr = $creditor_org_id->addChild('Othr');
            $creditor_othr->addChild('Id', $this->getIBAN());
            $creditor_scheme = $creditor_othr->addChild('SchmeNm');
            $creditor_scheme->addChild('Cd', 'CUST');
        } else{
            $creditorAgent = $creditTransferTransactionInformation->addChild('CdtrAgt');
            $financialInstitution = $creditorAgent->addChild('FinInstnId');
            $clearance_id = $financialInstitution->addChild('ClrSysMmbId');
            $mmid = $clearance_id->addChild('MmbId', $this->getClearanceId());
            $inst_name = $financialInstitution->addChild('Nm', $this->getInstitutionName());
            $postal_address = $financialInstitution->addChild('PstlAdr');
            $country = $postal_address->addChild('Ctry', $this->getInstitutionAddress());
            //$financialInstitution->addChild('BIC', $this->getBIC());

            $creditor = $creditTransferTransactionInformation->addChild("Cdtr");
            $creditor->addChild("Nm", $this->getCreditorName());
            $postal_address = $creditor->addChild('PstlAdr');
            $country = $postal_address->addChild('Ctry', $this->getCreditorCountry());

            $credit_acc = $creditTransferTransactionInformation->addChild('CdtrAcct');
            $credit_acc_id = $credit_acc->addChild('Id');
            $credit_acc_othr = $credit_acc_id->addChild('Othr');
            $credit_acc_othr->addChild('Id', $this->getIBAN());
        }

        if ($this->getCreditInvoice()) {
            $rmt_inf = $creditTransferTransactionInformation->addChild('RmtInf');
            $rmt_inf->addChild('Ustrd', $this->getCreditInvoice());
            $strd = $rmt_inf->addChild('Strd');
            $cdtfref = $strd->addChild('CdtrRefInf');
            $cdtfreftp = $cdtfref->addChild('CdtrRefTp');
            $cdtfreftp->addChild('Prtry', 'OTHR');  
        }

        return $creditTransferTransactionInformation;
    }
}
