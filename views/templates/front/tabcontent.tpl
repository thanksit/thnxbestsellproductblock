{if isset($thnxbestsellproductblock) && !empty($thnxbestsellproductblock)}
	{if isset($thnxbestsellproductblock.device)}
		{assign var=device_data value=$thnxbestsellproductblock.device|json_decode:true}
	{/if}
	{if isset($thnxbestsellproductblock.products) && !empty($thnxbestsellproductblock.products)}
		<div id="thnx_bestratedproductsblock_tab_{if isset($thnxbestsellproductblock.id_thnxbestsellproductblock)}{$thnxbestsellproductblock.id_thnxbestsellproductblock}{/if}" class="tab-pane fade">
			{if isset($thnxbestsellproductblock.products) && !empty($thnxbestsellproductblock.products)}
				{foreach from=$thnxbestsellproductblock.products item="product"}
					  {include file="catalog/_partials/miniatures/product.tpl" product=$product}
				{/foreach}
			{/if}
		</div>
	{else}
		<div id="thnx_bestratedproductsblock_tab_{if isset($thnxbestsellproductblock.id_thnxbestsellproductblock)}{$thnxbestsellproductblock.id_thnxbestsellproductblock}{/if}" class="tab-pane fade">
			<p class="alert alert-info">{l s='No products at this time.' mod='thnxbestsellproductblock'}</p>
		</div>
	{/if}
{/if}