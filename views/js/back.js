/**
 * 2007-2024 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2024 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 *
 * Don't forget to prefix your containers with your own identifier
 * to avoid any conflicts with others containers.
 */

document.addEventListener("DOMContentLoaded", function () {
	const form = document.getElementById("priceRuleForm");
	form.addEventListener("submit", function (e) {
		e.preventDefault();
		const id_group = document.getElementById("groupSelect").value;
		const coefficient = document.getElementById("coefficient").value;
		applyPriceRule(id_group, coefficient);
	});
});

function applyPriceRule(id_group, coefficient) {
	const loaderAdd = document.querySelector("#content .loaderAdd-container");
	loaderAdd.style.display = "flex";
	var postdata = {
		controller: "AdminCustomPriceRule",
		ajax: true,
		action: "ApplyPriceRule",
		token: token,
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

function deletePriceRule(id_price_rule, id_group) {
	const loaderDelete = document.querySelector(
		"#content .loaderDelete-container"
	);
	loaderDelete.style.display = "flex";
	var postdata = {
		controller: "AdminCustomPriceRule",
		ajax: true,
		action: "DeletePriceRule",
		token: token,
		id_price_rule: id_price_rule,
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
