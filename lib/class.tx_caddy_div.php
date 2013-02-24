<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 - Dirk Wildt <http://wildt.at.die-netzmacher.de>
 *  All rights reserved
 *
 *  Caddy is a fork of wt_cart (version 1.4.6)
 *  (c) wt_cart 2010-2012 - wt_cart Development Team <info@wt-cart.com>
 * 
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

require_once(PATH_tslib . 'class.tslib_pibase.php');

/**
 * Plugin 'Cart' for the 'caddy' extension.
 *
 * @author	Dirk Wildt <http://wildt.at.die-netzmacher.de>
 * @package    TYPO3
 * @subpackage    tx_caddy
 * @version     2.0.0
 * @since       1.4.6
 */
class tx_caddy_div extends tslib_pibase
{

    public $prefixId = 'tx_caddy_pi1';

    // Same as class name
    public $scriptRelPath = 'pi1/class.tx_caddy_pi1.php';

    // Path to any file in pi1 for locallang
    public $extKey = 'caddy'; // The extension key.
    
      // #45775, 130224, dwildt, 2+
      // Object: the parent cObject
    public $cObj = null;

    /**
    * Add product to session
    *
    * @param array   $parray: product array like
    *    array (
    *      'title' => 'this is the title',
    *      'amount' => 2,
    *      'price' => '1,49',
    *      'tax' => 1,
    *      'puid' => 234,
    *      'sku' => 'P234whatever'
    *    )
    * @param array   $pobj: Parent Object
    * @return  void
    */
    public function addProduct2Session($parray, $pObj)
    {
        // return without price or without title
        if (empty($parray['price']) || empty($parray['title'])) 
        {
            return false;
        }

        // return without price or without title

        // variants
        $arr_variant['puid'] = $parray['puid'];
        // add variant keys from ts settings.variants array,
        //  if there is a corresponding key in GET or POST
        if (is_array($pObj->conf['settings.']['variant.']))
        {
            $arr_get  = t3lib_div::_GET();
            $arr_post = t3lib_div::_POST();
            foreach($pObj->conf['settings.']['variant.'] as $key => $tableField)
            {
                list($table, $field) = explode('.', $tableField);
                if(isset($arr_get[$table][$field]))
                {
                    $arr_variant[$tableField] = mysql_escape_string($arr_get[$table][$field]);
                }
                if(isset($arr_post[$table][$field]))
                {
                    $arr_variant[$tableField] = mysql_escape_string($arr_post[$table][$field]);
                }
            }
            // add variant keys from ts settings.variants array,
        }
        // variants

        $sesArray = array();
        // get already exting products from session
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id);
        
        // check if this puid already exists and when delete it
        foreach ((array) $sesArray['products'] as $key => $value)
        { // one loop for every product
            if (is_array($value))
            {
                // counter for condition. Every condition has to be true
                $int_counter = 0;

                // loop every condition
                foreach($arr_variant as $key_variant => $value_variant)
                {
                    // condition fits
                    if ($value[$key_variant] == $value_variant)
                    {
                        $int_counter++;
                    }
                }
                // loop every condition

                // all conditions fit
                if($int_counter == count($arr_variant))
                {
                    // remove product 
                    $parray['qty'] = $sesArray['products'][$key]['qty'] + $parray['qty'];
                    unset($sesArray['products'][$key]);
                }
            }
        }

        $parray = $this->check($parray);

        if (isset($parray['price']))
        {
            $parray['price'] = str_replace(',', '.', $parray['price']); // comma to point
        }

        // remove puid from variant array
        unset($arr_variant[0]);

        // add variant key/value pairs to the current product
        if (!empty($arr_variant))
        {
            foreach ($arr_variant as $key_variant => $value_variant)
            {
                $parray[$key_variant] = $value_variant;
            }
        }
        // add variant key/value pairs to the current product

        // add product to the session array
        $sesArray['products'][] = $parray;
        
        // generate session with session array
        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id, $sesArray);
        // save session
        $GLOBALS['TSFE']->storeSessionData();
    }

    /* Check requested quantity and set error message
    *
    * @param array
    * @return  array
    * @version 1.4.2
    */
    public function check($parray)
    {
        $parray['error'] = Array();
        // check if min and max in range
        if (!empty($parray['min'])) {
            if ($parray['qty'] < $parray['min']) {
                $parray['qty'] = $parray['min'];
                $parray['error'][] = 'min';
            }
        }
        if (!empty($parray['max'])) {
            if ($parray['qty'] > $parray['max']) {
                $parray['qty'] = $parray['max'];
                $parray['error'][] = 'max';
            }
        }
        return $parray;
    }

    /**
    * Remove product from session with given uid
    *
    * @param array   $pobj: Parent Object
    * @return  void
    * @version 1.2.2
    */
    public function removeProductFromSession($pObj)
    {
        // variants
        // add variant key/value pairs from piVars
        $arr_variant = $this->get_variant_from_piVars($pObj);
        // add product id to variant array
        $arr_variant['puid'] = $pObj->piVars['del'];

        // get products from session array
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        // loop every product
        foreach ((array) $sesArray['products'] as $key => $value) 
        {
            //      if ($sesArray[$key]['puid'] == intval($uid)) { // uid fits
            //        unset($sesArray[$key]); // delete old value
            //      }
            // Counter for condition
            $int_counter = 0;

            // loop through conditions
            foreach($arr_variant as $key_variant => $value_variant)
            {
                // condition fits
                if ($sesArray['products'][$key][$key_variant] == $value_variant) 
                {
                    $int_counter++;
                }
            }
            // loop through conditions

            // all conditions fit
            if($int_counter == count($arr_variant))
            {
                // remove product from session
                unset($sesArray['products'][$key]);
            }
        }
        // loop every product

        // generate new session
        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id, $sesArray);
        // save session
        $GLOBALS['TSFE']->storeSessionData();
    }

    /**
    * Clear complete session
    *
    * @return  void
    */
    public function removeAllProductsFromSession()
    {
        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id, array()); // Generate new session with empty array
        $GLOBALS['TSFE']->storeSessionData(); // Save session
    }

    /**
    * Change quantity of a product in session
    *
    * @param array   $pobj: Parent Object
    * @return  void
    * @version 1.2.2
    */
    public function changeQtyInSession($pObj)
    {
        // variants
        // add variant key/value pairs from piVars
        $arr_variant = $this->get_variant_from_qty($pObj);

        // get products from session
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id);

        $is_cart = intval($pObj->piVars['update_from_cart']);

        // loop every product
        foreach ((array) $sesArray['products'] as $key_session => $value)
        {
            // current product id
            $session_puid = $sesArray['products'][$key_session]['puid'];

            if(!is_array($arr_variant))
            {
                // no variant, nothing to loop
                $int_qty = intval($pObj->piVars['qty'][$session_puid]);

                if ($int_qty > 0)
                {
                    // update quantity
                    if ($is_cart) {
                        // update from cart then set new qty
                        $sesArray['products'][$key_session]['qty'] = $int_qty;
                    } else {
                        // update not from cart then add qty
                        $sesArray['products'][$key_session]['qty'] += $int_qty;
                    }
                    $sesArray['products'][$key_session] = $this->check($sesArray['products'][$key_session]);
                } else {
                    // remove product from session
                    $this->removeProductFromSession($sesArray['products'][$key_session]['puid']);
                    // remove product from current session array
                    unset($sesArray['products'][$key_session]);
                }
            } else {
                // loop for every variant
                $arr_variant_backup = $arr_variant;
                foreach($arr_variant as $key_variant => $arr_condition)
                {
                    if (!isset($arr_variant[$key_variant]['puid']))
                    {
                        // without variant
                        $curr_puid = key($pObj->piVars['qty']);
                    }
                    if (isset($arr_variant[$key_variant]['puid']))
                    {
                        $curr_puid = $arr_variant[$key_variant]['puid'];
                    }
                    if (!isset($arr_variant[$key_variant]['qty']))
                    {
                        // without variant
                        $int_qty = intval($pObj->piVars['qty'][$curr_puid]);
                    }
                    if (isset($arr_variant[$key_variant]['qty']))
                    {
                        $int_qty = intval($arr_variant[$key_variant]['qty']);
                    }

                    // counter for condition
                    $int_counter = 0;
                    // puid: condition fits
                    if ($session_puid == $curr_puid) 
                    {
                        $int_counter++;
                    }

                    // loop through conditions
                    foreach($arr_condition as $key_condition => $value_condition)
                    {
                        // workaround (it would be better, if qty and puid won't be elements of $arr_condition
                        if (in_array($key_condition, array('qty', 'puid')))
                        {
                            // workaround: puid and qty should fit in every case
                            $int_counter++;
                        }
                        // workaround (it would be better, if qty and puid won't be elements of $arr_condition
                        if (!in_array($key_condition, array('qty', 'puid')))
                        {
                            // variants: condition fits
                            if ($sesArray['products'][$key_session][$key_condition] == $value_condition) 
                            {
                                //$prompt_315[] = 'div 315: true - session_key : ' . $key_session . ', ' . $key_condition . ' : ' . $value_condition;
                                $int_counter++;
                            }
                        }
                    }
                    // loop through conditions

                    // all conditions fit
                    if($int_counter == (count($arr_condition) + 1))
                    {
                        if ($int_qty > 0)
                        {
                            if ($is_cart)
                            {
                                // update from cart then set new qty
                                $sesArray['products'][$key_session]['qty'] = $int_qty;
                            } else {
                                // update not from cart then add qty
                                $sesArray['products'][$key_session]['qty'] += $int_qty;
                            }
                        } else {
                            // remove product from session
                            $this->removeProductFromSession($sesArray['products'][$key_session]['puid']);
                            // remove product from current session array
                            unset($sesArray['products'][$key_session]);
                        }
                    }

                }
                $arr_variant = $arr_variant_backup;
                // loop every variant
            }
        }
        // loop every product

        // generate new session
        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id, $sesArray);
        // save session 
        $GLOBALS['TSFE']->storeSessionData();
    }

    /**
    * Change the shipping method in session
    *
    * @param array     $arr: array to change
    * @return  void
    */
    public function changeShippingInSession($value)
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        $sesArray['shipping'] = intval($value); // overwrite with new qty

        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
        $GLOBALS['TSFE']->storeSessionData(); // Save session
    }

    /**
    * get the shipping method from session
    *
    * @return  integer
    */
    public function getShippingFromSession()
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        return $sesArray['shipping'];
    }

    /**
    * Change the payment method in session
    *
    * @param array     $arr: array to change
    * @return  void
    */
    public function changePaymentInSession($value)
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        $sesArray['payment'] = intval($value); // overwrite with new qty

        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
        $GLOBALS['TSFE']->storeSessionData(); // Save session
    }

    /**
    * get the payment method from session
    *
    * @return  integer
    */
    public function getPaymentFromSession()
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        return $sesArray['payment'];
    }
    
    /**
    * Change the special method in session
    *
    * @param array     $arr: array to change
    * @return  void
    */
    public function changeSpecialInSession($special_arr)
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        $sesArray['special'] = $special_arr; // overwrite with new qty

        $GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id, $sesArray); // Generate new session
        $GLOBALS['TSFE']->storeSessionData(); // Save session
    }

    /**
    * get the special method from session
    *
    * @return  integer
    */
    public function getSpecialFromSession()
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        return $sesArray['special'];
    }

    /**
    * Read products from session
    *
    * @return  array   $arr: array with all products from session
    */
    public function getProductsFromSession()
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        return $sesArray['products'];
    }
    
    /**
    * Count products in a cart
    *
    * @return  integer
    */
    public function countProductsInCart($pid)
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $pid); // get already exting products from session

        $count = 0;
        foreach ((array) $sesArray['products'] as $key => $val) {
            $count += $val['qty'];
        }

        return $count;
    }
    
    /**
    * Count gross price of all products in a cart
    *
    * @return  integer
    */
    public function getGrossPrice($pid)
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $pid); // get already exting products from session

        $gross = 0;
        foreach ((array) $sesArray['products'] as $key => $val) {
            $gross += $val['price'] * $val['qty'];
        }

        return $gross;
    }
    
    /**
    * get the order number from session
    *
    * @param array     $arr: array to change
    * @return  void
    */
    public function getOrderNumberFromSession()
    {
        $sesArray = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_cart_' . $GLOBALS["TSFE"]->id); // get already exting products from session

        return $sesArray['ordernumber'];
    }

    /**
    * read product details (title, price from table)
    * the method getProductDetails of version 1.2.1 became getProductDetails_ts from version 1.2.2
    *
    * @param array   $gpvar: array with product uid, title, tax, etc...
    * @param array   $pobj: Parent Object
    * @return  array   $arr: array with title and price
    * @version 1.2.2
    * @since 1.2.2
    */
    public function getProductDetails($gpvar, $pObj)
    {
        // build own sql query
        // handle query by db.sql
        if (!empty($pObj->conf['db.']['sql'])) {
            return $this->getProductDetails_sql($gpvar, $pObj);
        }

        // handle query by db.table and db.fields
        return $this->getProductDetails_ts($gpvar, $pObj);
    }

    /**
    * read product details by a manually configured sql query
    *
    * @param array   $gpvar: array with product uid, title, tax, etc...
    * @param array   $pobj: Parent Object
    * @return  array   $arr: array with title and price
    * @version 1.4.5
    * @since 1.2.2
    */
    public function getProductDetails_sql($gpvar, $pObj)
    {
        if ((!t3lib_div::_GET()) && (!t3lib_div::_POST()))
        {
            return false;
        }

        // replace gp:marker and enable_fields:marker in $pObj->conf['db.']['sql']
        $this->_replace_marker_in_sql($gpvar, $pObj);
                  // #42154, 101218, dwildt, 1-
                //$query = $pObj->cObj->stdWrap($pObj->conf['db.']['sql'], $pObj->conf['db.']['sql.']);
                  // #42154, 101218, dwildt, 1+
                $query = $pObj->cObj->cObjGetSingle($pObj->conf['db.']['sql'], $pObj->conf['db.']['sql.']);
        // execute the query
        $res = $GLOBALS['TYPO3_DB']->sql_query($query);
        $error = $GLOBALS['TYPO3_DB']->sql_error();

        // exit in case of error
        if (!empty($error))
        {
            $str = '<h1>caddy: SQL-Error</h1>';
            $str .= '<p>'.$error.'</p>';
            $str .= '<p>'.$query.'</p>';
            $this->msg($str, 0, 1, 1);
        }

        // ToDo: @dwildt: optimization highly needed
        if ($res)
        {
            while($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) 
            {
                if($row['title'] != null)
                {
                    break;
                }
            }  
            $row['puid']  = $gpvar['puid'];

            return $row;
        }

        return false;
    }

    /**
    * read product details (title, price from table)
    *
    * @param array   $gpvar: array with product uid, title, tax, etc...
    * @param array   $pobj: Parent Object
    * @return  array   $arr: array with title and price
    */
    public function getProductDetails_ts($gpvar, $pObj)
    {
        if (!empty($gpvar['title']) && !empty($gpvar['price']) && !empty($gpvar['tax']))
        { // all values already filled via POST or GET param
            return $gpvar;
        }

        $puid = intval($gpvar['puid']);
        if ($puid === 0)
        { // stop if no puid given
            return false;
        }

        $table    = $pObj->conf['db.']['table'];
        $select = $table . '.' . $pObj->conf['db.']['title'] . ', ' . $table . '.' . $pObj->conf['db.']['price'] . ', ' . $table . '.' . $pObj->conf['db.']['tax'];
        if ($pObj->conf['db.']['sku'] != '' && $pObj->conf['db.']['sku'] != '{$plugin.caddy.db.sku}')
        {
            $select .= ', ' . $table . '.' . $pObj->conf['db.']['sku'];
        }
        if ($pObj->conf['db.']['min'] != '' && $pObj->conf['db.']['min'] != '{$plugin.caddy.db.min}')
        {
            $select .= ', ' . $table . '.' . $pObj->conf['db.']['min'];
        }
        if ($pObj->conf['db.']['max'] != '' && $pObj->conf['db.']['max'] != '{$plugin.caddy.db.max}')
        {
            $select .= ', ' . $table . '.' . $pObj->conf['db.']['max'];
        }
        if ($pObj->conf['db.']['service_attribute_1'] != '' && $pObj->conf['db.']['service_attribute_1'] != '{$plugin.caddy.db.service_attribute_1}')
        {
            $select .= ', ' . $table . '.' . $pObj->conf['db.']['service_attribute_1'];
        }
        if ($pObj->conf['db.']['service_attribute_2'] != '' && $pObj->conf['db.']['service_attribute_2'] != '{$plugin.caddy.db.service_attribute_2}')
        {
            $select .= ', ' . $table . '.' . $pObj->conf['db.']['service_attribute_2'];
        }
        if ($pObj->conf['db.']['service_attribute_3'] != '' && $pObj->conf['db.']['service_attribute_3'] != '{$plugin.caddy.db.service_attribute_3}')
        {
            $select .= ', ' . $table . '.' . $pObj->conf['db.']['service_attribute_3'];
        }
        $where = ' ( ' . $table . '.uid = ' . $puid . ' OR l10n_parent = '.$puid . ' ) AND sys_language_uid = ' .$GLOBALS['TSFE']->sys_language_uid;
        $where .= tslib_cObj::enableFields($table);
        $groupBy = '';
        $orderBy = '';
        $limit = 1;

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery($select, $table, $where, $groupBy, $orderBy, $limit);

        if ($res)
        {
            $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
            $arr = array (
                'title' => $row[$pObj->conf['db.']['title']],
                'price' => $row[$pObj->conf['db.']['price']],
                'tax'   => $row[$pObj->conf['db.']['tax']],
                'puid'  => $gpvar['puid']
            );
            if ($row[$pObj->conf['db.']['sku']])
            {
                $arr['sku'] = $row[$pObj->conf['db.']['sku']];
            }
            if ($row[$pObj->conf['db.']['min']])
            {
                $arr['min'] = $row[$pObj->conf['db.']['min']];
            }
            if ($row[$pObj->conf['db.']['max']])
            {
                $arr['max'] = $row[$pObj->conf['db.']['max']];
            }
            if ($row[$pObj->conf['db.']['service_attribute_1']])
            {
                $arr['service_attribute_1'] = $row[$pObj->conf['db.']['service_attribute_1']];
            }
            if ($row[$pObj->conf['db.']['service_attribute_2']])
            {
                $arr['service_attribute_2'] = $row[$pObj->conf['db.']['service_attribute_2']];
            }
            if ($row[$pObj->conf['db.']['service_attribute_3']])
            {
                $arr['service_attribute_3'] = $row[$pObj->conf['db.']['service_attribute_3']];
            }

            return $arr;
        } else {
            // ToDo: include error handling -> only needed for admin, let's use devlog()
        }
    }

    /**
    * add flexform values to conf array
    *
    * @param array   $pobj: Parent Object
    * @return  void
    */
    public function flex2conf(&$pObj)
    {
        if (is_array($pObj->cObj->data['pi_flexform']['data']))
        { // if there are flexform values
            foreach ($pObj->cObj->data['pi_flexform']['data'] as $key => $value)
            { // every flexform category
                if (count($pObj->cObj->data['pi_flexform']['data'][$key]['lDEF']) > 0)
                { // if there are flexform values
                    foreach ($pObj->cObj->data['pi_flexform']['data'][$key]['lDEF'] as $key2 => $value2)
                    { // every flexform option
                        if ($pObj->pi_getFFvalue($pObj->cObj->data['pi_flexform'], $key2, $key))
                        { // if value exists in flexform
                            $pObj->conf[$key . '.'][$key2] = $pObj->pi_getFFvalue($pObj->cObj->data['pi_flexform'], $key2, $key); // overwrite $conf
                        }
                    }
                }
            }
        }
    }

    /**
    * returns message with optical flair
    *
    * @param string    $str: Message to show
    * @param int     $pos: Is this a positive message? (0,1,2)
    * @param boolean   $die: Process should be died now
    * @param boolean   $prefix: Activate or deactivate prefix "$extKey: "
    * @param string    $id: id to add to the message (maybe to do some javascript effects)
    * @return  string    $string: Manipulated string
    */
    public function msg($str, $pos = 0, $die = 0, $prefix = 1, $id = '')
    {
        // config
        if ($prefix)
        {
            $string = $this->extKey . ($pos != 1 && $pos != 2 ? ' Error' : '') . ': ';
        }
        $string .= $str; // add string
        $URLprefix = t3lib_div::getIndpEnv('TYPO3_SITE_URL') . '/'; // URLprefix with domain
        if (t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST') . '/' != t3lib_div::getIndpEnv('TYPO3_SITE_URL'))
        { // if request_host is different to site_url (TYPO3 runs in a subfolder)
            $URLprefix .= str_replace(t3lib_div::getIndpEnv('TYPO3_REQUEST_HOST') . '/', '', t3lib_div::getIndpEnv('TYPO3_SITE_URL')); // add folder (like "subfolder/")
        }

        // let's go
        switch ($pos)
        {
            default: // error
                $wrap = '<div class="' . $this->extKey . '_msg_error" style="background-color: #FBB19B; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/error.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #DC4C42; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
                if ($id)
                    $wrap .= ' id="' . $id . '"'; // add css id
                $wrap .= '>';
                break;

            case 1: // success
                $wrap = '<div class="' . $this->extKey . '_msg_status" style="background-color: #CDEACA; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/ok.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #58B548; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
                if ($id)
                    $wrap .= ' id="' . $id . '"'; // add css id
                $wrap .= '>';
                break;

            case 2: // note
                $wrap = '<div class="' . $this->extKey . '_msg_error" style="background-color: #DDEEF9; background-position: 4px 4px; background-image: url(' . $URLprefix . 'typo3/gfx/information.png); background-repeat: no-repeat; padding: 5px 30px; font-weight: bold; border: 1px solid #8AAFC4; margin-bottom: 20px; font-family: arial, verdana; color: #444; font-size: 12px;"';
                if ($id)
                    $wrap .= ' id="' . $id . '"'; // add css id
                $wrap .= '>';
                break;
        }

        if (!$die)
        {
            return $wrap . $string . '</div>'; // return message
        } else {
            die($string); // die process and write message
        }
    }
    
    /**
    * add_variant_gpvar_to_imagelinkwrap():  Adds all table.field of the variant to
    *                                          imageLinkWrap.typolink.additionalParams.wrap
    *
    * @param array   $product: array with product uid, title, tax, etc...
    * @param string   $ts_key: key of the current TypoScript configuration array
    * @param array   $ts_conf: the current TypoScript configuration array
    * @param array   $pobj: Parent Object
    * @return  array  $ts_conf: configuration array added with the varaition gpvars
    * @version 1.2.2
    * @since 1.2.2
    */
    public function add_variant_gpvar_to_imagelinkwrap($product, $ts_key, $ts_conf, $pObj)
    {
        // return there isn't any variant
        if (!is_array($pObj->conf['settings.']['variant.']))
        {
            return $ts_conf;
        }

        // get all variant key/value pairs from the current product
        $array_add_gpvar = $this->get_variant_from_product($product, $pObj);

        // add variant key/value pairs to imageLinkWrap
        foreach ((array) $array_add_gpvar as $key => $value)
        {
            $str_wrap = $ts_conf['imageLinkWrap.']['typolink.']['additionalParams.']['wrap'];
            $str_wrap = $str_wrap . '&' . $this->prefixId . '[' . $key . ']=' . $value;
            $ts_conf['imageLinkWrap.']['typolink.']['additionalParams.']['wrap'] = $str_wrap;
        }

        return $ts_conf;
    }

    /**
    * add_qty_marker():  Allocates to the global markerArray a value for ###QTY_NAME###
    *                          in case of variant
    *                          It returns in aray with hidden fields like
    *                          <input type="hidden" 
    *                                 name="tx_caddy_pi1[puid][20][]" 
    *                                 value="tx_caddy_pi1[tx_org_calentrance.uid]=4|tx_caddy_pi1[qty]=91" />
    *
    * @param array   $products: array with products with elements uid, title, tax, etc...
    * @param array   $markerArray: current marker array
    * @param array   $pobj: Parent Object
    * @return  array   $markerArray: with added element ###VARIANTS### in case of variants
    * @version 1.2.2
    * @since 1.2.2
    */
    public function add_qtyname_marker($product, $markerArray, $pObj)
    {
        // default name for QTY. It is compatible with version 1.2.1
        $markerArray['###QTY_NAME###'] = 'tx_caddy_pi1[qty][' . $product['puid'] . ']';

        // return there isn't any variant
        if (!is_array($pObj->conf['settings.']['variant.']))
        {
            return $markerArray;
        }

        $str_marker = null;
        // get all variant key/value pairs from the current product
        $array_add_gpvar = $this->get_variant_from_product($product, $pObj);
        $array_add_gpvar['puid']  = $product['puid'];
        // generate the marker array
        foreach ((array) $array_add_gpvar as $key => $value)
        {
            $str_marker = $str_marker . '[' . $key . '=' . $value . ']';
        }
        $markerArray['###QTY_NAME###'] = 'tx_caddy_pi1[qty]'. $str_marker;

        return $markerArray;
    }

    /**
    * get_variant_from_product():  Get an array with the variant values
    *                                out of the current product
    *
    * @param array   $product: array with product uid, title, tax, etc...
    * @param array   $pobj: Parent Object
    * @return  array   $arr_variants: array with variant key/value pairs
    * @version 1.2.2
    * @since 1.2.2
    */
    private function get_variant_from_product($product, $pObj)
    {
        $arr_variants = null;

        // return there isn't any variant
        if (!is_array($pObj->conf['settings.']['variant.']))
        {
            return $arr_variants;
        }
        // return there isn't any variant

        // loop through ts array variant
        foreach ($pObj->conf['settings.']['variant.'] as $key_variant)
        {
            // product contains variant key from ts 
            if (in_array($key_variant, array_keys($product)))
            {
                $arr_variants[$key_variant] = $product[$key_variant];
                if (empty($arr_variants[$key_variant]))
                {
                    unset($arr_variants[$key_variant]);
                }
            }
        }

        return $arr_variants;
    }

    /**
    * get_variant_from_piVars(): Get variant values from piVars
    *                              variant values have to be content of
    *                              ts array variant and of piVars
    *
    * @param array   $product: array with product uid, title, tax, etc...
    * @param array   $pobj: Parent Object
    * @return  array   $arr_variants: array with variant key/value pairs
    * @version 1.2.2
    * @since 1.2.2
    */
    private function get_variant_from_piVars($pObj)
    {
        $arr_variant = null;

        // return there isn't any variant
        if (!is_array($pObj->conf['settings.']['variant.']))
        {
            return $arr_variant;
        }
        // return there isn't any variant

        // loop through ts variant array
        foreach ($pObj->conf['settings.']['variant.'] as $key => $tableField)
        {
            list($table, $field) = explode('.', $tableField);
            // piVars contain variant key 
            if (!empty($pObj->piVars[$tableField]))
            {
                $arr_variant[$tableField] = mysql_escape_string($pObj->piVars[$tableField]);
            }
        }

        return $arr_variant;
    }

    /**
    * get_variant_from_qty(): Get variant values out of the name of the qty field
    *                              variant values have to be content of
    *                              ts array variant and of qty field
    *
    * @param array   $product: array with product uid, title, tax, etc...
    * @param array   $pobj: Parent Object
    * @return  array   $arr_variants: array with variant key/value pairs
    * @version 1.2.2
    * @since 1.2.2
    */
    private function get_variant_from_qty($pObj)
    {
        $arr_variant = null;

        // return there isn't any variant
        if (!is_array($pObj->conf['settings.']['variant.']))
        {
            return $arr_variant;
        }

        $int_counter = 0;
        foreach ($pObj->piVars['qty'] as $key => $value)
        {
            $arr_qty[$int_counter]['qty'][$key] = $value;
            $int_counter++;
        }

        foreach ($arr_qty as $key => $piVarsQty)
        {
            // iterator object
            $data     = new RecursiveArrayIterator( $piVarsQty['qty'] );
            $iterator = new RecursiveIteratorIterator( $data, true );
            // top level of ecursive array
            $iterator->rewind();

            // get all variant key/value pairs from qty name
            foreach ($iterator as $key_iterator => $value_iterator)
            {
                // i.e for a key: tx_org_calentrance.uid=4
                list($key_variant, $value_variant) = explode('=', $key_iterator);
                if ($key_variant == 'puid')
                {
                    $arr_variant[$key]['puid'] = $value_variant;
                }
                // i.e arr_var[tx_org_calentrance.uid] = 4
                $arr_from_qty[$key][$key_variant] = $value_variant;
                if (is_array($value_iterator))
                {
                    list($key_variant, $value_variant) = explode('=', key($value_iterator));
                    if ($key_variant == 'puid')
                    {
                        $arr_variant[$key]['puid'] = $value_variant;
                    }
                    $arr_from_qty[$key][$key_variant] = $value_variant;
                }
                // value is the value of the field qty in every case
                if (!is_array($value_iterator))
                {
                    $arr_variant[$key]['qty'] = $value_iterator;
                }
            }

            // loop through ts variant array
            foreach ($pObj->conf['settings.']['variant.'] as $key_variant => $tableField)
            {
                list($table, $field) = explode('.', $tableField);
                // piVars contain variant key 
                if (!empty($arr_from_qty[$key][$tableField]))
                {
                    $arr_variant[$key][$tableField] = mysql_escape_string($arr_from_qty[$key][$tableField]);
                }
            }
        }

        return $arr_variant;
    }

    /**
    * _replace_marker_in_sql(): Replace marker in the SQL query
    *                             MARKERS are
    *                             - GET/POST markers
    *                             - enable_field markers
    *                             SYNTAX is
    *                             - ###GP:TABLE###
    *                             - ###GP:TABLE.FIELD###
    *                             - ###ENABLE_FIELD:TABLE.FIELD###
    *
    * @param array   $gpvar: array with product uid, title, tax, etc...
    * @param array   $pobj: Parent Object
    * @return  void
    * @version 1.4.5
    * @since 1.2.2
    */
    private function _replace_marker_in_sql($gpvar, $pObj)
    {
        // set marker array with values from GET
        foreach (t3lib_div::_GET() as $table => $arr_fields) 
        {
            if (is_array($arr_fields))
            {
                foreach($arr_fields as $field => $value)
                {
                    $tableField = strtoupper($table . '.' . $field);
                    $marker['###GP:' . strtoupper($tableField) . '###'] = mysql_escape_string($value);
                }
            }
            if (!is_array($arr_fields))
            {
                $marker['###GP:' . strtoupper($table) . '###'] = mysql_escape_string($arr_fields);
            }
        }

        // set and overwrite marker array with values from POST
        foreach (t3lib_div::_POST() as $table => $arr_fields) 
        {
            if (is_array($arr_fields))
            {
                foreach ($arr_fields as $field => $value)
                {
                    $tableField = strtoupper($table . '.' . $field);
                    $marker['###GP:' . strtoupper($tableField) . '###'] = mysql_escape_string($value);
                }
            }
            if (!is_array($arr_fields))
            {
                $marker['###GP:' . strtoupper($table) . '###'] = mysql_escape_string($arr_fields);
            }
        }

        // get the SQL query from ts, allow stdWrap
                  // #42154, 101218, dwildt, 1-
                //$query = $pObj->cObj->stdWrap($pObj->conf['db.']['sql'], $pObj->conf['db.']['sql.']);
                  // #42154, 101218, dwildt, 1+
                $query = $pObj->cObj->cObjGetSingle($pObj->conf['db.']['sql'], $pObj->conf['db.']['sql.']);

        // get all gp:marker out of the query
        $arr_gpMarker = array();
        preg_match_all('|###GP\:(.*)###|U', $query, $arr_result, PREG_PATTERN_ORDER);
        if (isset($arr_result[0]))
        {
            $arr_gpMarker = $arr_result[0];
        } 

        // get all enable_fields:marker out of the query
        $arr_efMarker = array();
        preg_match_all('|###ENABLE_FIELDS\:(.*)###|U', $query, $arr_result, PREG_PATTERN_ORDER);
        if (isset($arr_result[0]))
        {
            $arr_efMarker = $arr_result[0];
        }

        // replace gp:marker
        foreach($arr_gpMarker as $str_gpMarker)
        {
            $value = null;
            if (isset($marker[$str_gpMarker])) 
            {
                $value = $marker[$str_gpMarker];
            }
            $query = str_replace($str_gpMarker, $value, $query);
        }

        // replace enable_fields:marker
        foreach($arr_efMarker as $str_efMarker)
        {
            $str_efTable = trim(strtolower($str_efMarker), '#');
            list($dummy, $str_efTable) = explode(':', $str_efTable);
            $andWhere_ef = tslib_cObj::enableFields($str_efTable);
            $query = str_replace($str_efMarker, $andWhere_ef, $query);
        }

                  // #42154, 121203, dwildt, 1-
//        $pObj->conf['db.']['sql'] = $query;
                  // #42154, 121203, dwildt, 2+
        $pObj->conf['db.']['sql'] = 'TEXT';
        $pObj->conf['db.']['sql.']['value'] = $query;
    }
  
 /**
  * cObjGetSingle( )
  *
  * @return  string
  * @access public
  * @version    2.0.0
  * @since      2.0.0
  */
  public function cObjGetSingle( $cObj_name, $cObj_conf )
  {
    switch( true )
    {
      case( is_array( $cObj_conf ) ):
        $value = $this->cObj->cObjGetSingle( $cObj_name, $cObj_conf );
        break;
      case( ! ( is_array( $cObj_conf ) ) ):
      default:
        $value = $cObj_name;
        break;
    }
      
    return $value;
  }
    
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_div.php'])
{
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/caddy/lib/class.tx_caddy_div.php']);
}
?>
