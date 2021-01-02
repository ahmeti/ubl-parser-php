<?php

use DOMDocument;

class UBLParser
{
    protected $data = [
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

        'InvoiceLines' => []
    ];

    protected function isElement($node)
    {
        return $node->nodeType === XML_ELEMENT_NODE;
    }

    protected function accountingSupplierParty($node)
    {
        foreach ($node->childNodes as $node2){

            if($node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cac:Party'){

                foreach ($node2->childNodes as $node3){

                    if( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:WebsiteURI' ){
                        $this->data['Supplier']['WebsiteURI'] = $node3->nodeValue;

                    }elseif ($node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cac:PartyIdentification'){

                        foreach ($node3->childNodes as $node4){
                            if( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:ID' && $node4->getAttribute('schemeID') === 'VKN' ){
                                $this->data['Supplier']['TaxID'] = $node4->nodeValue;
                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:ID' && $node4->getAttribute('schemeID') === 'TCKN' ){
                                $this->data['Supplier']['TaxID'] = $node4->nodeValue;
                            }
                        }

                    }elseif ($node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cac:PartyName'){

                        foreach ($node3->childNodes as $node4){
                            if( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:Name' ){
                                $this->data['Supplier']['Name'] = $node4->nodeValue;
                            }
                        }

                    }elseif ($node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cac:PostalAddress'){

                        foreach ($node3->childNodes as $node4){
                            if( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:StreetName' ){
                                $this->data['Supplier']['StreetName'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:BuildingName' ){
                                $this->data['Supplier']['BuildingName'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:BuildingNumber' ){
                                $this->data['Supplier']['BuildingNumber'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:CitySubdivisionName' ){
                                $this->data['Supplier']['CitySubdivisionName'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:CityName' ){
                                $this->data['Supplier']['CityName'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:PostalZone' ){
                                $this->data['Supplier']['PostalZone'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:Region' ){
                                $this->data['Supplier']['Region'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:District' ){
                                $this->data['Supplier']['District'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cac:Country' ){

                                foreach ($node4->childNodes as $node5){
                                    if( $node5->nodeType === XML_ELEMENT_NODE && $node5->nodeName === 'cbc:Name' ){
                                        $this->data['Supplier']['Country'] = $node5->nodeValue;
                                    }
                                }

                            }

                        }

                    }elseif ($node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cac:PartyTaxScheme'){

                        foreach ($node3->childNodes as $node4){

                            if( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cac:TaxScheme' ) {

                                foreach ($node4->childNodes as $node5){
                                    if( $node5->nodeType === XML_ELEMENT_NODE && $node5->nodeName === 'cbc:Name' ) {
                                        $this->data['Supplier']['TaxOfficeName'] = $node5->nodeValue;
                                    }
                                }

                            }

                        }

                    }elseif ($node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cac:Person'){

                        foreach ($node3->childNodes as $node4){

                            if( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:FirstName' ) {
                                $this->data['Supplier']['PersonFirstName'] = $node4->nodeValue;
                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:FamilyName' ) {
                                $this->data['Supplier']['PersonFamilyName'] = $node4->nodeValue;
                            }

                        }

                    }
                }
            }
        }
    }

    protected function allowanceCharge($node)
    {
        foreach ($node->childNodes as $node2){

            if( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:Amount' ) {
                $this->data['AllowanceCharge']['Amount'] = $node2->nodeValue;
                $this->data['AllowanceCharge']['AmountCurrency'] = $node2->getAttribute('currencyID');
            }

        }
    }

    protected function taxTotal($node)
    {
        foreach ($node->childNodes as $node2){

            if( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:TaxAmount' ) {
                $this->data['TaxTotal']['TaxAmount'] = $node2->nodeValue;
                $this->data['TaxTotal']['TaxAmountCurrency'] = $node2->getAttribute('currencyID');

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cac:TaxSubtotal' ) {

                foreach ($node2->childNodes as $node3){

                    if( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:TaxableAmount' ) {
                        $this->data['TaxSubtotal']['TaxableAmount'] = $node3->nodeValue;
                        $this->data['TaxSubtotal']['TaxableAmountCurrency'] = $node3->getAttribute('currencyID');

                    }elseif( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:TaxAmount' ) {
                        $this->data['TaxSubtotal']['TaxAmount'] = $node3->nodeValue;
                        $this->data['TaxSubtotal']['TaxAmountCurrency'] = $node3->getAttribute('currencyID');

                    }elseif( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:CalculationSequenceNumeric' ) {
                        $this->data['TaxSubtotal']['CalculationSequenceNumeric'] = $node3->nodeValue;

                    }elseif( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:Percent' ) {
                        $this->data['TaxSubtotal']['Percent'] = $node3->nodeValue;
                    }

                }
            }

        }
    }

    protected function legalMonetaryTotal($node)
    {
        foreach ($node->childNodes as $node2){

            if( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:LineExtensionAmount' ) {
                $this->data['LegalMonetaryTotal']['LineExtensionAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['LineExtensionAmountCurrency'] = $node2->getAttribute('currencyID');

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:TaxExclusiveAmount' ) {
                $this->data['LegalMonetaryTotal']['TaxExclusiveAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['TaxExclusiveAmountCurrency'] = $node2->getAttribute('currencyID');

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:TaxInclusiveAmount' ) {
                $this->data['LegalMonetaryTotal']['TaxInclusiveAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['TaxInclusiveAmountCurrency'] = $node2->getAttribute('currencyID');

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:AllowanceTotalAmount' ) {
                $this->data['LegalMonetaryTotal']['AllowanceTotalAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['AllowanceTotalAmountCurrency'] = $node2->getAttribute('currencyID');

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:PayableAmount' ) {
                $this->data['LegalMonetaryTotal']['PayableAmount'] = $node2->nodeValue;
                $this->data['LegalMonetaryTotal']['PayableAmountCurrency'] = $node2->getAttribute('currencyID');
            }

        }
    }

    protected function invoiceLine($node)
    {
        $invoiceLine = [
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

        foreach ($node->childNodes as $node2){

            if( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:ID' ) {
                $invoiceLine['ID'] = $node2->nodeValue;

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:InvoicedQuantity' ) {
                $invoiceLine['InvoicedQuantity'] = $node2->nodeValue;
                $invoiceLine['UnitCode'] = $node2->getAttribute('unitCode');

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cbc:LineExtensionAmount' ) {
                $invoiceLine['LineExtensionAmount'] = $node2->nodeValue;
                $invoiceLine['LineExtensionAmountCurrency'] = $node2->getAttribute('currencyID');

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cac:AllowanceCharge' ) {

                foreach ($node2->childNodes as $node3){

                    if( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:Amount' ) {
                        $invoiceLine['AllowanceCharge']['Amount'] = $node3->nodeValue;
                        $invoiceLine['AllowanceCharge']['AmountCurrency'] = $node3->getAttribute('currencyID');

                    }elseif( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:BaseAmount' ) {
                        $invoiceLine['AllowanceCharge']['BaseAmount'] = $node3->nodeValue;
                        $invoiceLine['AllowanceCharge']['BaseAmountCurrency'] = $node3->getAttribute('currencyID');
                    }

                }

            }elseif ($node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cac:TaxTotal'){

                foreach ($node2->childNodes as $node3){

                    if( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:TaxAmount' ) {
                        $invoiceLine['TaxTotal']['TaxAmount'] = $node3->nodeValue;
                        $invoiceLine['TaxTotal']['TaxAmountCurrency'] = $node3->getAttribute('currencyID');

                    }elseif( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cac:TaxSubtotal' ) {

                        foreach ($node3->childNodes as $node4){

                            if( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:TaxableAmount' ) {
                                $invoiceLine['TaxSubtotal']['TaxableAmount'] = $node4->nodeValue;
                                $invoiceLine['TaxSubtotal']['TaxableAmountCurrency'] = $node4->getAttribute('currencyID');

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:TaxAmount' ) {
                                $invoiceLine['TaxSubtotal']['TaxAmount'] = $node4->nodeValue;
                                $invoiceLine['TaxSubtotal']['TaxAmountCurrency'] = $node4->getAttribute('currencyID');

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:CalculationSequenceNumeric' ) {
                                $invoiceLine['TaxSubtotal']['CalculationSequenceNumeric'] = $node4->nodeValue;

                            }elseif( $node4->nodeType === XML_ELEMENT_NODE && $node4->nodeName === 'cbc:Percent' ) {
                                $invoiceLine['TaxSubtotal']['Percent'] = $node4->nodeValue;
                            }

                        }

                    }

                }

            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cac:Item' ) {

                foreach ($node2->childNodes as $node3){

                    if( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:Name' ) {
                        $invoiceLine['Item']['Name'] = $node3->nodeValue;

                    }

                }
            }elseif( $node2->nodeType === XML_ELEMENT_NODE && $node2->nodeName === 'cac:Price' ) {

                foreach ($node2->childNodes as $node3){

                    if( $node3->nodeType === XML_ELEMENT_NODE && $node3->nodeName === 'cbc:PriceAmount' ) {
                        $invoiceLine['Price']['PriceAmount'] = $node3->nodeValue;
                        $invoiceLine['Price']['PriceAmountCurrency'] = $node3->getAttribute('currencyID');
                    }

                }
            }

        }

        $this->data['InvoiceLines'][] = $invoiceLine;
    }

    public function set($xmlString)
    {
        $doc = new DOMDocument();
        $doc->loadXML($xmlString);

        foreach ($doc->documentElement->childNodes as $node) {

            if( $this->isElement($node) && $node->nodeName === 'cbc:UBLVersionID' ){
                $this->data['UBLVersionID'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:CustomizationID' ){
                $this->data['CustomizationID'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:ProfileID' ){
                $this->data['ProfileID'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:ID' ){
                $this->data['ID'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:UUID' ){
                $this->data['UUID'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:IssueDate' ){
                $this->data['IssueDate'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:IssueTime' ){
                $this->data['IssueTime'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:InvoiceTypeCode' ){
                $this->data['InvoiceTypeCode'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:Note' ){
                $this->data['Notes'][] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:DocumentCurrencyCode' ){
                $this->data['DocumentCurrencyCode'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cbc:LineCountNumeric' ){
                $this->data['LineCountNumeric'] = $node->nodeValue;

            }elseif ( $this->isElement($node) && $node->nodeName === 'cac:AccountingSupplierParty' ){
                $this->accountingSupplierParty($node);

            }elseif ($this->isElement($node) && $node->nodeName === 'cac:AllowanceCharge'){
                $this->allowanceCharge($node);

            }elseif ($this->isElement($node) && $node->nodeName === 'cac:TaxTotal'){
                $this->taxTotal($node);

            }elseif ($this->isElement($node) && $node->nodeName === 'cac:LegalMonetaryTotal'){
                $this->legalMonetaryTotal($node);

            }elseif ($this->isElement($node) && $node->nodeName === 'cac:InvoiceLine'){
                $this->invoiceLine($node);
            }

        }

        return $this;
    }

    public function get()
    {
        return $this->data;
    }
}
