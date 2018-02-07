{if isset($thnxbestsellproductblock) && !empty($thnxbestsellproductblock)}
	<div class="thnx_product_home_small col-sm-4">
		{if isset($thnxbestsellproductblock.device)}
			{assign var=device_data value=$thnxbestsellproductblock.device|json_decode:true}
		{/if}
		<div class="thnxbestsellproductblock block carousel">
			<h4 class="title_block">
		    	<em>{$thnxbestsellproductblock.title}</em>
		    </h4>
		    <div class="block_content products-block">
		        {if isset($thnxbestsellproductblock) && $thnxbestsellproductblock}
		        	{if isset($thnxbestsellproductblock.products) && !empty($thnxbestsellproductblock.products)}
						{foreach from=$thnxbestsellproductblock.products item="product"}
							  {include file="catalog/_partials/miniatures/product.tpl" product=$product}
						{/foreach}
					{/if}
		        {else}
		        	<ul id="bestsellproduct_{$thnxbestsellproductblock.id_thnxbestsellproductblock}" class="bestsellproduct">
		        		<li class="alert alert-info">{l s='No products at this time.' mod='thnxbestsellproductblock'}</li>
		        	</ul>
		        {/if}
		    </div>
		</div>
	</div>
{/if}