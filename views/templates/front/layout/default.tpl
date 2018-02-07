{if isset($thnxbestsellproductblock) && !empty($thnxbestsellproductblock)}
	{if isset($thnxbestsellproductblock.device)}
		{assign var=device_data value=$thnxbestsellproductblock.device|json_decode:true}
	{/if}
	<div id="thnxbestsellproductblock_{$thnxbestsellproductblock.id_thnxbestsellproductblock}" class="thnxbestsellproductblock thnx_default_products_block" style="margin:{$thnxbestsellproductblock.section_margin};">
		<div class="page_title_area {$thnx.home_title_style}">
			{if isset($thnxbestsellproductblock.title)}
				<h3 class="page-heading">
					<em>{$thnxbestsellproductblock.title}</em>
					<span class="page_title_shadow_text"><span>{$thnxbestsellproductblock.title}</span></span>
					<span class="heading_carousel_arrow"></span>
				</h3>
			{/if}
			{if isset($thnxbestsellproductblock.sub_title)}
				<p class="page_subtitle">{$thnxbestsellproductblock.sub_title}</p>
			{/if}
			<div class="heading-line d_none"><span></span></div>
		</div>
		{if isset($thnxbestsellproductblock) && $thnxbestsellproductblock}
			<div id="thnx_bestsellproductblock_{$thnxbestsellproductblock.id_thnxbestsellproductblock}" class="thnx_default_products_block_content featured-products">
			
				{include file="catalog/_partials/product-list/product-list-default.tpl" thnxprdcolumnclass=$device_data products=$thnxbestsellproductblock.products id='' class="thnx_bestsellproductblock {if $thnxbestsellproductblock.enable_carousel == 1}carousel{/if}"}

				{if isset($thnxbestsellproductblock.view_more_prod.$lang_id) && !empty($thnxbestsellproductblock.view_more_prod.$lang_id)}
					<div class="prod_view_more">
						<a class="view_more" target="_blank" href="{$thnxbestsellproductblock.view_more_prod_link.$lang_id}">{$thnxbestsellproductblock.view_more_prod.$lang_id}</a>
					</div>
				{/if}

			</div>
		{else}
			<div class="thnx_default_products_block_content">
				<p class="alert alert-info">{l s='No products at this time.' mod='thnxbestsellproductblock'}</p>
			</div>
		{/if}
	</div>

{thnxbestsellproductblock_js name="thnxbestsellproductblock_{$thnxbestsellproductblock.id_thnxbestsellproductblock}"}
<script type="text/javascript">
$(document).ready(function() { 


	var thnxbestsellproductblock = $("#thnxbestsellproductblock_{$thnxbestsellproductblock.id_thnxbestsellproductblock}");
	var sliderSelect = thnxbestsellproductblock.find('.product_list.grid.carousel'); 
	var arrowSelect = thnxbestsellproductblock.find('.heading_carousel_arrow'); 

	{if isset($thnxbestsellproductblock.nav_arrow_style) && ($thnxbestsellproductblock.nav_arrow_style == 'arrow_top')}
		var appendArrows = arrowSelect;
	{else}
		var appendArrows = sliderSelect;
	{/if}
	
	if (!!$.prototype.slick)
	sliderSelect.slick( { 
		infinite: {if isset($thnxbestsellproductblock.play_again)}{$thnxbestsellproductblock.play_again|boolval|var_export:true}{else}false{/if},
		autoplay: {if isset($thnxbestsellproductblock.autoplay)}{$thnxbestsellproductblock.autoplay|boolval|var_export:true}{else}false{/if},
		pauseOnHover: {if isset($thnxbestsellproductblock.pause_on_hover)}{$thnxbestsellproductblock.pause_on_hover|boolval|var_export:true}{else}true{/if},
		dots: {if isset($thnxbestsellproductblock.navigation_dots)}{$thnxbestsellproductblock.navigation_dots|boolval|var_export:true}{else}false{/if},
		arrows: {if isset($thnxbestsellproductblock.navigation_arrow)}{$thnxbestsellproductblock.navigation_arrow|boolval|var_export:true}{else}false{/if},
		appendArrows: appendArrows,
		nextArrow : '<i class="slick-next arrow_carrot-right"></i>',
		prevArrow : '<i class="slick-prev arrow_carrot-left"></i>',
		rows: {if isset($thnxbestsellproductblock.product_rows)}{$thnxbestsellproductblock.product_rows|intval}{else}1{/if},
		// slidesPerRow: 3,
		slidesToShow : {if isset($device_data.device_xl)}{$device_data.device_xl|intval}{else}4{/if},
		slidesToScroll : {if isset($thnxbestsellproductblock.product_scroll) && ($thnxbestsellproductblock.product_scroll=='per_item')}1{else}{if isset($device_data.device_xl)}{$device_data.device_xl|intval}{else}4{/if}{/if},
		responsive:[
			 // { 
				// breakpoint: 1920,
				// settings:  { 
				// 	slidesToShow: {if isset($device_data.device_xl)}{$device_data.device_xl|intval}{else}4{/if},
				// 	slidesToScroll : {if isset($thnxbestsellproductblock.product_scroll) && ($thnxbestsellproductblock.product_scroll=='per_item')}1{else}{if isset($device_data.device_xl)}{$device_data.device_xl|intval}{else}4{/if}{/if},
				// 	// slidesToShow : prd_per_column,
				//  } 
			 // } ,
			 { 
				breakpoint: 1367,
				settings:  { 
					slidesToShow: {if isset($device_data.device_lg)}{$device_data.device_lg|intval}{else}4{/if},
					slidesToScroll : {if isset($thnxbestsellproductblock.product_scroll) && ($thnxbestsellproductblock.product_scroll=='per_item')}1{else}{if isset($device_data.device_lg)}{$device_data.device_lg|intval}{else}4{/if}{/if},
					// slidesToShow : 2,
				 } 
			 } ,
			 { 
				breakpoint: 1025,
				settings:  { 
					slidesToShow: {if isset($device_data.device_md)}{$device_data.device_md|intval}{else}4{/if},
					slidesToScroll : {if isset($thnxbestsellproductblock.product_scroll) && ($thnxbestsellproductblock.product_scroll=='per_item')}1{else}{if isset($device_data.device_md)}{$device_data.device_md|intval}{else}4{/if}{/if},
					// slidesToShow : 2,
				 } 
			 } ,
			 { 
				breakpoint: 768,
				settings:  { 
					slidesToShow: {if isset($device_data.device_sm)}{$device_data.device_sm|intval}{else}3{/if},
					slidesToScroll : {if isset($thnxbestsellproductblock.product_scroll) && ($thnxbestsellproductblock.product_scroll=='per_item')}1{else}{if isset($device_data.device_sm)}{$device_data.device_sm|intval}{else}3{/if}{/if},
					// slidesToShow : 2,
				 } 
			 } ,
			 { 
				breakpoint: 576,
				settings:  { 
					slidesToShow: {if isset($device_data.device_xs)}{$device_data.device_xs|intval}{else}2{/if},
					slidesToScroll : {if isset($thnxbestsellproductblock.product_scroll) && ($thnxbestsellproductblock.product_scroll=='per_item')}1{else}{if isset($device_data.device_xs)}{$device_data.device_xs|intval}{else}2{/if}{/if},
				 } 
			 } 
		]
	 } );
	 } );
</script>
{/thnxbestsellproductblock_js}
{/if}