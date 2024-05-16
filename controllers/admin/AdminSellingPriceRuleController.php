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
if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminSellingPriceRuleController extends ModuleAdminController
{
    public function __construct()
    {
        $this->module = Module::getInstanceByName('sellingpricerule');
        $this->ajax = true;

        parent::__construct();
    }

    public function displayAjaxApplyPriceRule()
    {
        $shopId = (int) Tools::getValue('id_shop');
        $groupId = (int) Tools::getValue('id_group');
        $coefficient = (int) Tools::getValue('coefficient');

        $res = $this->applyPriceRule($shopId, $groupId, $coefficient);
        if (!$res['result']) {
            $response = ['success' => false, 'message' => $this->trans('The rule was not applied correctly. Some products have not been processed.', [], 'Modules.Sellingpricerule.Admin')];
            $this->ajaxDie(json_encode($response));
        }
        PrestaShopLogger::addLog('The ' . $coefficient . '% rule for Group ' . $groupId . ' was applied to ' . $res['affectedRows'] . ' products.', 2);
        $response = ['success' => true, 'message' => $this->trans('The rule has been successfully applied.', [], 'Modules.Sellingpricerule.Admin')];
        $this->ajaxDie(json_encode($response));
    }

    public function displayAjaxDeletePriceRule()
    {
        $idPriceRule = (int) Tools::getValue('id_price_rule');
        $shopId = (int) Tools::getValue('id_shop');
        $groupId = (int) Tools::getValue('id_group');

        if (!$this->deletePriceRule($idPriceRule, $shopId, $groupId)) {
            $response = ['success' => false, 'message' => $this->trans('Deleting the rule failed.', [], 'Modules.Sellingpricerule.Admin')];
            $this->ajaxDie(json_encode($response));
        }
        $response = ['success' => true, 'message' => $this->trans('The rule has been successfully deleted.', [], 'Modules.Sellingpricerule.Admin')];
        $this->ajaxDie(json_encode($response));
    }

    public function displayAjaxAddExclusion()
    {
        $productIds = Tools::getValue('productIds');

        foreach ($productIds as $productId) {
            if (!$this->addExclusion($productId)) {
                $response = ['success' => false, 'message' => $this->trans('Adding exclusion failed for product ID : ' . $productId, [], 'Modules.Sellingpricerule.Admin')];
                $this->ajaxDie(json_encode($response));
            }
        }
        $response = ['success' => true, 'message' => $this->trans('The exclusion has been successfully added.', [], 'Modules.Sellingpricerule.Admin')];
        $this->ajaxDie(json_encode($response));
    }

    public function displayAjaxDeleteExclusion()
    {
        $exclusionId = (int) Tools::getValue('id_exclusion');

        if (!$this->deleteExclusion($exclusionId)) {
            $response = ['success' => false, 'message' => $this->trans('Deleting exclusion failed.', [], 'Modules.Sellingpricerule.Admin')];
            $this->ajaxDie(json_encode($response));
        }
        $response = ['success' => true, 'message' => $this->trans('The exclusion has been successfully deleted.', [], 'Modules.Sellingpricerule.Admin')];
        $this->ajaxDie(json_encode($response));
    }

    public function displayAjaxDeleteAllExclusions()
    {
        if (!$this->deleteAllExclusions()) {
            $response = ['success' => false, 'message' => $this->trans('Deleting all exclusions failed.', [], 'Modules.Sellingpricerule.Admin')];
            $this->ajaxDie(json_encode($response));
        }
        $response = ['success' => true, 'message' => $this->trans('All exclusions have been successfully deleted.', [], 'Modules.Sellingpricerule.Admin')];
        $this->ajaxDie(json_encode($response));
    }

    public function applyPriceRule($shopId, $groupId, $coefficient)
    {
        $result = true;
        $affectedRows = 0;
        $products = $this->getProducts();

        foreach ($products as $item) {
            if ($this->module->productExcluded($item['id_product'])) {
                continue;
            }
            $product = new Product($item['id_product']);
            $wholesalePriceProduct = (float) $product->wholesale_price;

            if ($product->hasAttributes()) {
                $attributes = $this->getProductAttributes($product->id);

                foreach ($attributes as $attribute) {
                    $wholesalePriceAttribute = (float) $attribute['wholesale_price'];
                    if ($wholesalePriceAttribute <= 0) {
                        continue;
                    }
                    $coef = (float) ((int) $coefficient / 100);
                    $amountToAdd = $wholesalePriceAttribute * $coef;
                    $newPrice = $wholesalePriceAttribute + $amountToAdd;

                    $result = $this->module->addSpecificPrice($product->id, $shopId, $groupId, $newPrice, $attribute['id_product_attribute']);
                    $affectedRows += 1;
                }
            } else {
                if ($wholesalePriceProduct <= 0) {
                    continue;
                }
                $coef = (float) ((int) $coefficient / 100);
                $amountToAdd = $wholesalePriceProduct * $coef;
                $newPrice = $wholesalePriceProduct + $amountToAdd;

                $result = $this->module->addSpecificPrice($product->id, $shopId, $groupId, $newPrice);
                $affectedRows += 1;
            }
        }

        $result = Db::getInstance()->insert('selling_price_rule', [
            'id_shop' => (int) $shopId,
            'id_group' => (int) $groupId,
            'coef' => (int) $coefficient,
            'date_add' => date('Y-m-d H:i:s'),
        ]);

        return ['result' => $result, 'affectedRows' => $affectedRows];
    }

    public function deletePriceRule($id_price_rule, $shopId, $groupId)
    {
        return Db::getInstance()->delete('specific_price', "id_shop = $shopId AND id_group = $groupId AND id_customer = 0 AND id_cart = 0 AND id_specific_price_rule = 0") && Db::getInstance()->delete('selling_price_rule', "id_price_rule = $id_price_rule");
    }

    public function addExclusion($productId)
    {
        return Db::getInstance()->insert('selling_price_rule_exclusion', [
            'id_product' => (int) $productId,
            'date_add' => date('Y-m-d H:i:s'),
        ]) && Db::getInstance()->delete('specific_price', 'reduction_type = "amount" AND reduction = 0 AND id_customer = 0 AND id_cart = 0 AND id_specific_price_rule = 0 AND id_product = ' . $productId);
    }

    public function deleteExclusion($exclusionId)
    {
        return Db::getInstance()->delete('selling_price_rule_exclusion', 'id_exclusion = ' . $exclusionId);
    }

    public function deleteAllExclusions()
    {
        return Db::getInstance()->execute('TRUNCATE TABLE ' . _DB_PREFIX_ . 'selling_price_rule_exclusion');
    }

    public function getProducts()
    {
        return Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'product');
    }

    public function getProductAttributesIds($productId)
    {
        return Product::getProductAttributesIds($productId);
    }

    public function getProductAttributes($productId)
    {
        return Db::getInstance()->executeS('
        SELECT pa.id_product_attribute, pa.wholesale_price
        FROM `' . _DB_PREFIX_ . 'product_attribute` pa
        WHERE pa.`id_product` = ' . (int) $productId);
    }
}
