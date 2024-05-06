<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

class AdminCustomPriceRuleController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->ajax = true;
    }

    public function displayAjaxApplyPriceRule()
    {
        $groupId = (int) Tools::getValue('id_group');
        $coefficient = (int) Tools::getValue('coefficient');

        if (!$this->applyPriceRule($groupId, $coefficient)) {
            $response = ['success' => false, 'message' => $this->trans('The rule was not applied correctly. Some products have not been processed.', [], 'Modules.Custompricerule.Admin')];
            $this->ajaxDie(json_encode($response));
        }
        $response = ['success' => true, 'message' => $this->trans('The rule has been successfully applied.', [], 'Modules.Custompricerule.Admin')];
        $this->ajaxDie(json_encode($response));
    }

    public function displayAjaxDeletePriceRule()
    {
        $idPriceRule = (int) Tools::getValue('id_price_rule');
        $groupId = (int) Tools::getValue('id_group');

        if (!$this->deletePriceRule($idPriceRule, $groupId)) {
            $response = ['success' => false, 'message' => $this->trans('Deleting the rule failed.', [], 'Modules.Custompricerule.Admin')];
            $this->ajaxDie(json_encode($response));
        }
        $response = ['success' => true, 'message' => $this->trans('The rule has been successfully deleted.', [], 'Modules.Custompricerule.Admin')];
        $this->ajaxDie(json_encode($response));
    }

    public function applyPriceRule($groupId, $coefficient)
    {
        $result = true;
        $products = $this->getProducts();

        foreach ($products as $item) {
            $product = new Product($item['id_product']);
            $wholesalePriceProduct = floatval($product->wholesale_price);

            $attributes = $this->getAttributeCombinations($product);

            if (!empty($attributes)) {
                foreach ($attributes as $attribute) {
                    $wholesalePriceAttribute = floatval($attribute['wholesale_price']);
                    if ($wholesalePriceAttribute <= 0) {
                        continue;
                    }
                    $result = $this->addSpecificPrice($product->id, $groupId, $coefficient, $wholesalePriceAttribute, $attribute['id_product_attribute']);
                }
            } else {
                if ($wholesalePriceProduct <= 0) {
                    continue;
                }
                $result = $this->addSpecificPrice($product->id, $groupId, $coefficient, $wholesalePriceProduct);
            }
        }

        $result = Db::getInstance()->insert('custom_price_rule', [
            'id_group' => (int) $groupId,
            'coef' => (int) $coefficient,
            'date_add' => date('Y-m-d H:i:s'),
        ]);

        return $result;
    }

    public function addSpecificPrice($productId, $groupId, $coefficient, $wholesalePrice, $attributeId = 0)
    {
        $newPrice = $this->calculateNewPrice($coefficient, $wholesalePrice);

        PrestaShopLogger::addLog("$productId, $groupId, $coefficient, $wholesalePrice");

        $specificPrice = new SpecificPrice();
        $specificPrice->id_product = (int) $productId;
        $specificPrice->id_product_attribute = (int) $attributeId;
        $specificPrice->reduction = 0;
        $specificPrice->reduction_type = 'amount';
        $specificPrice->reduction_tax = 1;
        $specificPrice->id_shop = 0;
        $specificPrice->id_cart = 0;
        $specificPrice->id_currency = 0;
        $specificPrice->id_country = 0;
        $specificPrice->id_group = (int) $groupId;
        $specificPrice->id_customer = 0;
        $specificPrice->price = $newPrice;
        $specificPrice->from_quantity = 1;
        $specificPrice->from = date('Y-m-d H:i:s');
        $specificPrice->to = '0000-00-00 00:00:00';

        /* $addedPriceId = $specificPrice->id; */
        return $specificPrice->save();
    }

    public function calculateNewPrice($coefficient, $wholesalePrice)
    {
        $coef = (int) $coefficient / 100;
        $amountToAdd = $wholesalePrice * $coef;
        $newPrice = $wholesalePrice + $amountToAdd;

        return (float) $newPrice;
    }

    public function deletePriceRule($id_price_rule, $groupId)
    {
        return Db::getInstance()->delete('specific_price', "id_group = $groupId AND id_customer = 0 AND id_specific_price_rule = 0") && Db::getInstance()->delete('custom_price_rule', "id_price_rule = $id_price_rule");
    }

    public function getProducts()
    {
        return Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'product`');
    }

    public function getAttributeCombinations(Product $product)
    {
        return $product->getAttributeCombinations();
    }
}
