{if !empty($thnxbestsellproductblock)}
	{foreach from=$thnxbestsellproductblock item=thnxbestsale}
		{include file="./layout/{$thnxbestsale.layout}.tpl"}
	{/foreach}
{/if}