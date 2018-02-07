{if isset($thnxbestsellproductblock) && !empty($thnxbestsellproductblock)}
	<li><a data-toggle="tab" href="#thnx_bestratedproductsblock_tab_{if isset($thnxbestsellproductblock.id_thnxbestsellproductblock)}{$thnxbestsellproductblock.id_thnxbestsellproductblock}{/if}" class="thnx_bestratedproductsblock">{$thnxbestsellproductblock.title}</a></li>
{/if}