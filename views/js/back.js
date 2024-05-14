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

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("priceRuleForm");
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        const id_shop = document.getElementById("groupShop").value;
        const id_group = document.getElementById("groupSelect").value;
        const coefficient = document.getElementById("coefficient").value;
        applyPriceRule(id_shop, id_group, coefficient);
    });

    const exclusionForm = document.getElementById("exclusionForm");
    exclusionForm.addEventListener("submit", function (e) {
        e.preventDefault();
        const productIds = $("#productsInput").val();
        addExclusion(productIds);
    });
    
});

function applyPriceRule(id_shop, id_group, coefficient) {
    const loaderAdd = document.querySelector("#content .loaderAdd-container");
    loaderAdd.style.display = "flex";
    var postdata = {
        controller: "AdminSellingPriceRule",
        ajax: true,
        action: "ApplyPriceRule",
        token: token,
        id_shop: id_shop,
        id_group: id_group,
        coefficient: coefficient,
    };
    $.ajax({
        type: "POST",
        cache: false,
        dataType: "json",
        url: "index.php",
        data: postdata,
    })
        .done(function (response) {
            loaderAdd.style.display = "none";
            if (response.success) {
                $.growl.notice({ title: "", message: response.message });
                location.reload();
            } else {
                $.growl.error({ title: "", message: response.message });
            }
        })
        .fail(function (response) {
            console.error(response);
        });
}

function deletePriceRule(id_price_rule, id_shop, id_group) {
    const loaderDelete = document.querySelector(
        "#content .loaderDelete-container",
    );
    loaderDelete.style.display = "flex";
    var postdata = {
        controller: "AdminSellingPriceRule",
        ajax: true,
        action: "DeletePriceRule",
        token: token,
        id_price_rule: id_price_rule,
        id_shop: id_shop,
        id_group: id_group,
    };
    $.ajax({
        type: "POST",
        cache: false,
        dataType: "json",
        url: "index.php",
        data: postdata,
    })
        .done(function (response) {
            loaderDelete.style.display = "none";
            if (response.success) {
                $.growl.notice({ title: "", message: response.message });
                location.reload();
            } else {
                $.growl.error({ title: "", message: response.message });
            }
        })
        .fail(function (response) {
            console.error(response);
        });
}

function addExclusion(productIds) {
    const loaderExclusion = document.querySelector("#content .loaderExclusion-container");
    loaderExclusion.style.display = "flex";
    var postdata = {
        controller: "AdminSellingPriceRule",
        ajax: true,
        action: "AddExclusion",
        token: token,
        productIds: productIds,
    };
    $.ajax({
        type: "POST",
        cache: false,
        dataType: "json",
        url: "index.php",
        data: postdata,
    })
        .done(function (response) {
            loaderExclusion.style.display = "none";
            if (response.success) {
                $.growl.notice({ title: "", message: response.message });
                location.reload();
            } else {
                $.growl.error({ title: "", message: response.message });
            }
        })
        .fail(function (response) {
            console.error(response);
        });
}

function deleteExclusion(id_exclusion) {
    const loaderExclusion = document.querySelector("#content .loaderExclusion-container");
    loaderExclusion.style.display = "flex";
    var postdata = {
        controller: "AdminSellingPriceRule",
        ajax: true,
        action: "DeleteExclusion",
        token: token,
        id_exclusion: id_exclusion,
    };
    $.ajax({
        type: "POST",
        cache: false,
        dataType: "json",
        url: "index.php",
        data: postdata,
    })
        .done(function (response) {
            loaderExclusion.style.display = "none";
            if (response.success) {
                $.growl.notice({ title: "", message: response.message });
                location.reload();
            } else {
                $.growl.error({ title: "", message: response.message });
            }
        })
        .fail(function (response) {
            console.error(response);
        });
}