<?php

use DOMDocument;

class UBLParser
{
    protected array $data = [
        'UBLVersionID' => null,
        'CustomizationID' => null,
        'ProfileID' => null,
        'ID' => null,
        'UUID' => null,
        'IssueDate' => null,
        'IssueTime' => null,
        'InvoiceTypeCode' => null,
        'Notes' => [],
        'DocumentCurrencyCode' => null,
        'LineCountNumeric' => null,

        'Supplier' => [
            'WebsiteURI' => null,
            'TaxID' => null,
            'Name' => null,
            'StreetName' => null,
            'BuildingName' => null,
            'BuildingNumber' => null,
            'CitySubdivisionName' => null,
            'CityName' => null,
            'PostalZone' => null,
            'Region' => null,
            'District' => null,
            'Country' => null,
            'TaxOfficeName' => null,
            'PersonFirstName' => null,
            'PersonFamilyName' => null,
        ],

        'AllowanceCharge' => [
            'Amount' => null,
            'AmountCurrency' => null,
        ],

        'TaxTotal' => [
            'TaxAmount' => null,
            'TaxAmountCurrency' => null,
        ],

        'TaxSubtotal' => [
            'TaxableAmount' => null,
            'TaxableAmountCurrency' => null,
            'TaxAmount' => null,
            'TaxAmountCurrency' => null,
            'CalculationSequenceNumeric' => null,
            'Percent' => null,
        ],

        'LegalMonetaryTotal' => [
            'LineExtensionAmount' => null,
            'LineExtensionAmountCurrency' => null,
            'TaxExclusiveAmount' => null,
            'TaxExclusiveAmountCurrency' => null,
            'TaxInclusiveAmount' => null,
            'TaxInclusiveAmountCurrency' => null,
            'AllowanceTotalAmount' => null,
            'AllowanceTotalAmountCurrency' => null,
            'PayableAmount' => null,
            'PayableAmountCurrency' => null,
        ],

        'InvoiceLines' => [],
    ];

    protected array $item = [
        'ID' => null,
        'InvoicedQuantity' => null,
        'UnitCode' => null,
        'LineExtensionAmount' => null,
        'LineExtensionAmountCurrency' => null,
        'AllowanceCharge' => [
            'Amount' => null,
            'AmountCurrency' => null,
            'BaseAmount' => null,
            'BaseAmountCurrency' => null,
        ],
        'TaxTotal' => [
            'TaxAmount' => null,
            'TaxAmountCurrency' => null,
        ],
        'TaxSubtotal' => [
            'TaxableAmount' => null,
            'TaxableAmountCurrency' => null,
            'TaxAmount' => null,
            'TaxAmountCurrency' => null,
            'CalculationSequenceNumeric' => null,
            'Percent' => null,
        ],
        'Item' => [
            'Name' => null,
        ],
        'Price' => [
            'PriceAmount' => null,
            'PriceAmountCurrency' => null,
        ],
    ];

    protected function isNode($node, $name): bool
    {
        return $node->nodeType === XML_ELEMENT_NODE && $node->nodeName === $name;
    }

    protected function accountingSupplierParty($node): void
    {
        foreach ($node->childNodes as $node2) {

            if ($this->isNode($node2, 'cac:Party')) {

                foreach ($node2->childNodes as $node3) {

                    if ($this->isNode($node3, 'cbc:WebsiteURI')) {
                        $this->data['Supplier']['WebsiteURI'] = $node3->nodeValue;

                    } elseif ($this->isNode($node3, 'cac:PartyIdentification')) {

                        foreach ($node3->childNodes as $node4) {
                            if ($this->isNode($node4, 'cbc:ID') && $node4->getAttribute('schemeID') === 'VKN') {
                                $this->data['Supplier']['TaxID'] = $node4->nodeValue;
                            } elseif ($this->isNode($node4, 'cbc:ID') && $node4->getAttribute('schemeID') === 'TCKN') {
                                $this->data['Supplier']['TaxID'] = $node4->nodeValue;
                            }
                        }

                    } elseif ($this->isNode($node3, 'cac:PartyName')) {

                        foreach ($node3->childNodes as $node4) {
                            if ($this->isNode($node4, 'cbc:Name')) {
                                $this->data['Supplier']['Name'] = $node4->nodeValue;
                            }
                        }

                    } elseif ($this->isNode($node3, 'cac:PostalAddress')) {

                        foreach ($node3->childNodes as $node4) {
                            if ($this->isNode($node4, 'cbc:StreetName')) {
                                $this->data['Supplier']['StreetName'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cbc:BuildingName')) {
                                $this->data['Supplier']['BuildingName'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cbc:BuildingNumber')) {
                                $this->data['Supplier']['BuildingNumber'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cbc:CitySubdivisionName')) {
                                $this->data['Supplier']['CitySubdivisionName'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cbc:CityName')) {
                                $this->data['Supplier']['CityName'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cbc:PostalZone')) {
                                $this->data['Supplier']['PostalZone'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cbc:Region')) {
                                $this->data['Supplier']['Region'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cbc:District')) {
                                $this->data['Supplier']['District'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cac:Country')) {

                                foreach ($node4->childNodes as $node5) {
                                    if ($this->isNode($node5, 'cbc:Name')) {
                                        $this->data['Supplier']['Country'] = $node5->nodeValue;
                                    }
                                }
                            }
                        }

                    } elseif ($this->isNode($node3, 'cac:PartyTaxScheme')) {

                        foreach ($node3->childNodes as $node4) {

                            if ($this->isNode($node4, 'cac:TaxScheme')) {

                                foreach ($node4->childNodes as $node5) {
                                    if ($this->isNode($node5, 'cbc:Name')) {
                                        $this->data['Supplier']['TaxOfficeName'] = $node5->nodeValue;
                                    }
                                }
                            }
                        }

                    } elseif ($this->isNode($node3, 'cac:Person')) {

                        foreach ($node3->childNodes as $node4) {

                            if ($this->isNode($node4, 'cbc:FirstName')) {
                                $this->data['Supplier']['PersonFirstName'] = $node4->nodeValue;
                            } elseif ($this->isNode($node4, 'cbc:FamilyName')) {
                                $this->data['Supplier']['PersonFamilyName'] = $node4->nodeValue;
                            }
                        }
                    }
                }
            }
        }
    }

    protected function allowanceCharge($node): void
    {
        foreach ($node->childNodes as $node2) {

            if ($this->isNode($node2, 'cbc:Amount')) {
                $this->data['AllowanceCharge']['Amount'] = $node2->nodeValue;
                $this->data['AllowanceCharge']['AmountCurrency'] = $node2->getAttribute('currencyID');
            }
        }
    }

    protected function taxTotal($node): void
    {
        foreach ($node->childNodes as $node2) {

            if ($this->isNode($node2, 'cbc:TaxAmount')) {
                $this->data['TaxTotal']['TaxAmount'] = $node2->nodeValue;
                $this->data['TaxTotal']['TaxAmountCurrency'] = $node2->getAttribute('currencyID');

            } elseif ($this->isNode($node2, 'cac:TaxSubtotal')) {

                foreach ($node2->childNodes as $node3) {

                    if ($this->isNode($node3, 'cbc:TaxableAmount')) {
                        $this->data['TaxSubtotal']['TaxableAmount'] = $node3->nodeValue;
                        $this->data['TaxSubtotal']['TaxableAmountCurrency'] = $node3->getAttribute('currencyID');

                    } elseif ($this->isNode($node3, 'cbc:TaxAmount')) {
                        $this->data['TaxSubtotal']['TaxAmount'] = $node3->nodeValue;
                        $this->data['TaxSubtotal']['TaxAmountCurrency'] = $node3->getAttribute('currencyID');

                    } elseif ($this->isNode($node3, 'cbc:CalculationSequenceNumeric')) {
                        $this->data['TaxSubtotal']['CalculationSequenceNumeric'] = $node3->nodeValue;

                    } elseif ($this->isNode($node3, 'cbc:Percent')) {
                        $this->data['TaxSubtotal']['Percent'] = $node3->nodeValue;
                    }
                }
            }
        }
    }

    protected function legalMonetaryTotal($node): void
    {
        foreach ($node->childNodes as $node2) {

            if ($this->isNode($node2, 'cbc:LineExtensionAmount')) {
                $this->data['LegalMonetaryTotal']['LineExtensionAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['LineExtensionAmountCurrency'] = $node2->getAttribute('currencyID');

            } elseif ($this->isNode($node2, 'cbc:TaxExclusiveAmount')) {
                $this->data['LegalMonetaryTotal']['TaxExclusiveAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['TaxExclusiveAmountCurrency'] = $node2->getAttribute('currencyID');

            } elseif ($this->isNode($node2, 'cbc:TaxInclusiveAmount')) {
                $this->data['LegalMonetaryTotal']['TaxInclusiveAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['TaxInclusiveAmountCurrency'] = $node2->getAttribute('currencyID');

            } elseif ($this->isNode($node2, 'cbc:AllowanceTotalAmount')) {
                $this->data['LegalMonetaryTotal']['AllowanceTotalAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['AllowanceTotalAmountCurrency'] = $node2->getAttribute('currencyID');

            } elseif ($this->isNode($node2, 'cbc:PayableAmount')) {
                $this->data['LegalMonetaryTotal']['PayableAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['PayableAmountCurrency'] = $node2->getAttribute('currencyID');
            }
        }
    }

    protected function invoiceLine($node): void
    {
        $invoiceLine = $this->item;

        foreach ($node->childNodes as $node2) {

            if ($this->isNode($node2, 'cbc:ID')) {
                $invoiceLine['ID'] = $node2->nodeValue;

            } elseif ($this->isNode($node2, 'cbc:InvoicedQuantity')) {
                $invoiceLine['InvoicedQuantity'] = $node2->nodeValue;
                $invoiceLine['UnitCode'] = $node2->getAttribute('unitCode');

            } elseif ($this->isNode($node2, 'cbc:LineExtensionAmount')) {
                $invoiceLine['LineExtensionAmount'] = $node2->nodeValue;
                $invoiceLine['LineExtensionAmountCurrency'] = $node2->getAttribute('currencyID');

            } elseif ($this->isNode($node2, 'cac:AllowanceCharge')) {

                foreach ($node2->childNodes as $node3) {

                    if ($this->isNode($node3, 'cbc:Amount')) {
                        $invoiceLine['AllowanceCharge']['Amount'] = $node3->nodeValue;
                        $invoiceLine['AllowanceCharge']['AmountCurrency'] = $node3->getAttribute('currencyID');

                    } elseif ($this->isNode($node3, 'cbc:BaseAmount')) {
                        $invoiceLine['AllowanceCharge']['BaseAmount'] = $node3->nodeValue;
                        $invoiceLine['AllowanceCharge']['BaseAmountCurrency'] = $node3->getAttribute('currencyID');
                    }
                }

            } elseif ($this->isNode($node2, 'cac:TaxTotal')) {

                foreach ($node2->childNodes as $node3) {

                    if ($this->isNode($node3, 'cbc:TaxAmount')) {
                        $invoiceLine['TaxTotal']['TaxAmount'] = $node3->nodeValue;
                        $invoiceLine['TaxTotal']['TaxAmountCurrency'] = $node3->getAttribute('currencyID');

                    } elseif ($this->isNode($node3, 'cac:TaxSubtotal')) {

                        foreach ($node3->childNodes as $node4) {

                            if ($this->isNode($node4, 'cbc:TaxableAmount')) {
                                $invoiceLine['TaxSubtotal']['TaxableAmount'] = $node4->nodeValue;
                                $invoiceLine['TaxSubtotal']['TaxableAmountCurrency'] = $node4->getAttribute('currencyID');

                            } elseif ($this->isNode($node4, 'cbc:TaxAmount')) {
                                $invoiceLine['TaxSubtotal']['TaxAmount'] = $node4->nodeValue;
                                $invoiceLine['TaxSubtotal']['TaxAmountCurrency'] = $node4->getAttribute('currencyID');

                            } elseif ($this->isNode($node4, 'cbc:CalculationSequenceNumeric')) {
                                $invoiceLine['TaxSubtotal']['CalculationSequenceNumeric'] = $node4->nodeValue;

                            } elseif ($this->isNode($node4, 'cbc:Percent')) {
                                $invoiceLine['TaxSubtotal']['Percent'] = $node4->nodeValue;
                            }
                        }
                    }
                }

            } elseif ($this->isNode($node2, 'cac:Item')) {

                foreach ($node2->childNodes as $node3) {

                    if ($this->isNode($node3, 'cbc:Name')) {
                        $invoiceLine['Item']['Name'] = $node3->nodeValue;
                    }
                }

            } elseif ($this->isNode($node2, 'cac:Price')) {

                foreach ($node2->childNodes as $node3) {

                    if ($this->isNode($node3, 'cbc:PriceAmount')) {
                        $invoiceLine['Price']['PriceAmount'] = $node3->nodeValue;
                        $invoiceLine['Price']['PriceAmountCurrency'] = $node3->getAttribute('currencyID');
                    }
                }
            }
        }

        $this->data['InvoiceLines'][] = $invoiceLine;
    }

    public function set(string $xmlString): static
    {
        $doc = new DOMDocument();
        $doc->loadXML($xmlString, LIBXML_NOERROR);

        foreach ($doc->documentElement->childNodes as $node) {

            if ($this->isNode($node, 'cbc:UBLVersionID')) {
                $this->data['UBLVersionID'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:CustomizationID')) {
                $this->data['CustomizationID'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:ProfileID')) {
                $this->data['ProfileID'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:ID')) {
                $this->data['ID'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:UUID')) {
                $this->data['UUID'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:IssueDate')) {
                $this->data['IssueDate'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:IssueTime')) {
                $this->data['IssueTime'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:InvoiceTypeCode')) {
                $this->data['InvoiceTypeCode'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:Note') && ! empty($node->nodeValue)) {
                $this->data['Notes'][] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:DocumentCurrencyCode')) {
                $this->data['DocumentCurrencyCode'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cbc:LineCountNumeric')) {
                $this->data['LineCountNumeric'] = $node->nodeValue;

            } elseif ($this->isNode($node, 'cac:AccountingSupplierParty')) {
                $this->accountingSupplierParty($node);

            } elseif ($this->isNode($node, 'cac:AllowanceCharge')) {
                $this->allowanceCharge($node);

            } elseif ($this->isNode($node, 'cac:TaxTotal')) {
                $this->taxTotal($node);

            } elseif ($this->isNode($node, 'cac:LegalMonetaryTotal')) {
                $this->legalMonetaryTotal($node);

            } elseif ($this->isNode($node, 'cac:InvoiceLine')) {
                $this->invoiceLine($node);
            }
        }

        return $this;
    }

    public function get(): array
    {
        return $this->data;
    }
}
