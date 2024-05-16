{**
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
 *}
	<form action="" id="exclusionForm">
		<div class="panel" id="fieldset_0" style="position: relative;">

			<div class="loaderExclusion-container" style="display:none;">
				<span class="loader"></span>
				<span class="loading-message">{l s='Loading, please wait...' d='Modules.Sellingpricerule.Admin'}</span>
			</div>

			<div class="panel-heading">
				<i class="icon-ban"></i> {l s='Product exclusion' d='Modules.Sellingpricerule.Admin'}
			</div>

			<div class="form-group" style="display: flex; align-items: center">
				<label for="groupShop" style="text-align: end;" class="control-label col-lg-2 required">{l
					s='Product'
					d='Modules.Sellingpricerule.Admin'}</label>
				<div class="col-lg-6">
					<select id="productsInput" class="form-control" name="products[]" multiple="multiple">
						{foreach from=$products item=product}
						<option value="{$product.id_product}">{$product.name} {if
							$product.reference}({$product.reference}){/if}</option>
						{/foreach}
					</select>

				</div>
				<div class="col-lg-2">
					<button type="submit" class="btn btn-primary text-capitalize">{l s='Exclude'
						d='Modules.Sellingpricerule.Admin'}</button>
				</div>
				<div class="col-lg-2">
					<button type="button" class="btn btn-danger pull-right" data-toggle="modal"
						data-target="#deleteAllExclusionsModal" {if !$hasExclusions} disabled {/if}>{l s='Delete all exclusions' d='Modules.Sellingpricerule.Admin'}</button>
				</div>
			</div>


			<div class="form-wrapper">

				<table class="table">
					<thead>
						<tr>
							<th>ID</th>
							<th>{l s='Name' d='Modules.Sellingpricerule.Admin'}</th>
							<th>{l s='Reference' d='Modules.Sellingpricerule.Admin'}</th>
							<th>{l s='Date' d='Modules.Sellingpricerule.Admin'}</th>
							<th>{l s='Actions' d='Modules.Sellingpricerule.Admin'}</th>
						</tr>
					</thead>
					{if $exclusions}
					<tbody style="text-align: center;">
						{foreach from=$exclusions item=exclusion}
						<tr>
							<td style="padding: 0.5rem;">{$exclusion.id_product}</td>
							<td style="padding: 0.5rem;"><a target="_blank"
									href="/{$adminPath}/index.php/sell/catalog/products/{$exclusion.id_product}?_token={$token}#tab-step1">{$exclusion.product_name}</a>
							</td>
							<td style="padding: 0.5rem;">{$exclusion.product_reference}</td>
							<td style="padding: 0.5rem;">{$exclusion.date_add|date_format:"%d/%m/%Y"}</td>
							<td style="padding: 0.5rem;">
								<a href="#" class="toolbar-button" onclick="deleteExclusion({$exclusion.id_exclusion})">
									<i class="material-icons">delete</i>
								</a>
							</td>
						</tr>
						{/foreach}
					</tbody>
					{else}
					<tbody style="text-align: center;">
						<tr>
							<td style="padding: 0.5rem;" colspan="6">{l s='No exclusion defined'
								d='Modules.Sellingpricerule.Admin'}</td>
						</tr>
					</tbody>
					{/if}
				</table>

			</div>
		</div>
	</form>

	<!-- Modal -->
	<div class="modal fade" id="deleteAllExclusionsModal" tabindex="-1" role="dialog"
		aria-labelledby="deleteAllExclusionsModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title" id="deleteAllExclusionsModalLabel">{l s='Delete all exclusions'
						d='Modules.Sellingpricerule.Admin'}</h4>
				</div>
				<div class="modal-body">
					{l s='Are you sure you want to remove all exclusions?' d='Modules.Sellingpricerule.Admin'}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
						{l s='Close' d='Modules.Sellingpricerule.Admin'}
					</button>
					<button type="button" class="btn btn-danger" onclick="deleteAllExclusions()">{l s='Delete All'
						d='Modules.Sellingpricerule.Admin'}</button>
				</div>
			</div>
		</div>
	</div>


	<script>
		$(document).ready(function () {
			$('#productsInput').select2(
				{
					placeholder: "{l s='Search a product' d='Modules.Sellingpricerule.Admin'}",
					allowClear: true,
					language: {
						noResults: function () {
							return "{l s='No results found' d='Modules.Sellingpricerule.Admin'}";
						}
					},
				}
			);
		});
	</script>