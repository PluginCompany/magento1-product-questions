<?php
/**
 * Copyright (c) 2014-2026 Plugin Company
 *
 * This file is part of Product Questions, released under the MIT License.
 * See the LICENSE file in the project root.
 */
class Plugincompany_Productquestions_Block_Frontend_Accounttab extends Mage_Core_Block_Template {
    public function __construct()
    {
        parent::__construct();
    }

    public function getCustomer()
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        if ($customer->getId()):
            return $customer;
        endif;

        return false;
    }

    /* Get Customer product question data for current customer
     *      (Latest asked product first).
     * @return Array data
     */
    public function getProductQuestionsDataExt(){
        global $arr;
        $data = $this->getProductQuestionsData();
        function cmp($a, $b){
            if ($a[1] < $b[1]) {return 1;}
            if ($a[1] === $b[1]) {return 0;}
            if ($a[1] > $b[1]) {return -1;}
            return 0;
        }
        function cmp2($a, $b){
            global $arr;
            if($a['product_id'] === $b['product_id']) {
                if ($a['date_asked'] < $b['date_asked']) {return -1;}
                if ($a['date_asked'] > $b['date_asked']) {return 1;}
                return 0;
            }
            $cmpa = array_search($a['product_id'], $arr);
            $cmpb = array_search($b['product_id'], $arr);
            return ($cmpa > $cmpb) ? 1 : -1;
        }
        $arr  = Array();

        foreach ($data as $key => $row){
            if (count($arr)===0){
                array_push($arr, array($row['product_id'], $row['date_asked']));
            }
            if ($arr[count($arr)-1][0] !== $row['product_id']){
                array_push($arr, array($row['product_id'], $row['date_asked']));
            } else {
                if ($arr[count($arr)-1][1] < $row['date_asked']){
                    $arr[count($arr)-1][1] = $row['date_asked'];
                }
            }
        }
        usort($arr, 'cmp');
        $arr = array_column($arr,0);
        usort($data, 'cmp2');
        $questions = array();
        foreach($data as $questionData){
            $question = new Varien_Object();
            $question->setData($questionData);
            $questions[] = $question;
        }
        return $questions;
    }

    /* Get Customer product question data for current customer
     *      (Alphabetical sort order Product.)
     * @return Array data
     */
    public function getProductQuestionsData(){
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            $collection = Mage::getModel('productquestions/questions')->getCollection();
            $collection
                ->addFilter('customer_id', $customerId);
            $collection->setOrder('product_id', 'DESC')->setOrder('date_asked', 'ASC');
            $data = $collection->getData();
            return $data;
        }
    }

    /*  Get Customer product question data fieldnames collection
     *
     *  @return Array fieldname
     */
    public function getProductQuestionsFieldNames(){
        $fields = Array();
        $collection = Mage::getModel('productquestions/mysql4_questions_collection');
        if ($data = $collection->getFirstItem()) {
            foreach ($data->toArray() as $name => $value) {
                array_push($fields, $name);
            }
        }
        return $fields;
    }

    public function getProductName($prodId){
        $product = Mage::getModel('catalog/product')->load($prodId);
        return $product->getName();
    }

    public function getProductUrl($prodId){
        return Mage::getModel('catalog/product')->load($prodId)->getProductUrl();
    }
}

if (!function_exists('array_column')) {
    /**
     * Returns the values from a single column of the input array, identified by
     * the $columnKey.
     *
     * Optionally, you may provide an $indexKey to index the values in the returned
     * array by the values from the $indexKey column in the input array.
     *
     * @param array $input A multi-dimensional array (record set) from which to pull
     *                     a column of values.
     * @param mixed $columnKey The column of values to return. This value may be the
     *                         integer key of the column you wish to retrieve, or it
     *                         may be the string key name for an associative array.
     * @param mixed $indexKey (Optional.) The column to use as the index/keys for
     *                        the returned array. This value may be the integer key
     *                        of the column, or it may be the string key name.
     * @return array
     */
    function array_column($input = null, $columnKey = null, $indexKey = null)
    {
        // Using func_get_args() in order to check for proper number of
        // parameters and trigger errors exactly as the built-in array_column()
        // does in PHP 5.5.
        $argc = func_num_args();
        $params = func_get_args();
        if ($argc < 2) {
            trigger_error("array_column() expects at least 2 parameters, {$argc} given", E_USER_WARNING);
            return null;
        }
        if (!is_array($params[0])) {
            trigger_error(
                'array_column() expects parameter 1 to be array, ' . gettype($params[0]) . ' given',
                E_USER_WARNING
            );
            return null;
        }
        if (!is_int($params[1])
            && !is_float($params[1])
            && !is_string($params[1])
            && $params[1] !== null
            && !(is_object($params[1]) && method_exists($params[1], '__toString'))
        ) {
            trigger_error('array_column(): The column key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        if (isset($params[2])
            && !is_int($params[2])
            && !is_float($params[2])
            && !is_string($params[2])
            && !(is_object($params[2]) && method_exists($params[2], '__toString'))
        ) {
            trigger_error('array_column(): The index key should be either a string or an integer', E_USER_WARNING);
            return false;
        }
        $paramsInput = $params[0];
        $paramsColumnKey = ($params[1] !== null) ? (string) $params[1] : null;
        $paramsIndexKey = null;
        if (isset($params[2])) {
            if (is_float($params[2]) || is_int($params[2])) {
                $paramsIndexKey = (int) $params[2];
            } else {
                $paramsIndexKey = (string) $params[2];
            }
        }
        $resultArray = array();
        foreach ($paramsInput as $row) {
            $key = $value = null;
            $keySet = $valueSet = false;
            if ($paramsIndexKey !== null && array_key_exists($paramsIndexKey, $row)) {
                $keySet = true;
                $key = (string) $row[$paramsIndexKey];
            }
            if ($paramsColumnKey === null) {
                $valueSet = true;
                $value = $row;
            } elseif (is_array($row) && array_key_exists($paramsColumnKey, $row)) {
                $valueSet = true;
                $value = $row[$paramsColumnKey];
            }
            if ($valueSet) {
                if ($keySet) {
                    $resultArray[$key] = $value;
                } else {
                    $resultArray[] = $value;
                }
            }
        }
        return $resultArray;
    }
}