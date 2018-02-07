{extends file="helpers/form/form.tpl"}
{block name="field"}
	{if $input.type == 'device'}
		<div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}9{/if}{if !isset($input.label)} col-lg-offset-3{/if}">
			{if isset($input.device_desc) && !empty($input.device_desc)}
				{foreach $input.device_desc AS $dev}
					<div class="col-xs-4 col-sm-2">
						<label data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="{if isset($dev.tooltip)}{$dev.tooltip}{/if}">{if isset($dev.title)}{$dev.title}{/if}</label>
						{$devicess = "{$input.name}_{$dev.name}"}
						<select name="{$input.name}_{$dev.name}" id="{$input.name}_{$dev.name}" class="fixed-width-md">
							{if isset($dev.column) && !empty($dev.column)}
			        			{foreach $dev.column AS $column}
			                        <option value="{$column}" {if isset($fields_value[$input['name']]->{$devicess}) && ($fields_value[$input['name']]->{$devicess} == $column)} selected="selected"  {/if} >{$column}</option>
			                    {/foreach}
		                    {/if}
	        			</select>
	        			{$devicess = ""}
					</div>
				{/foreach}
			{/if}
		</div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
{block name="description"}
{if !isset($input.expandstyle_class)}{$input.expandstyle_class = true}{/if}
{if !isset($input.expandstyle_margin)}{$input.expandstyle_margin = true}{/if}
{if !isset($input.expandstyle_padding)}{$input.expandstyle_padding = true}{/if}
{if (isset($input.expandstyle) && $input.expandstyle == true) || isset($input.desc) && !empty($input.desc)}
		<div class="help-block-container">
			{if isset($input.expandstyle) && $input.expandstyle == true}
					<a class="click-to-put-cls clk-put-cls-{$input.name}" href="#">Click Here To Use Expand Style {if isset($input.label) && !empty($input.label)} In {$input.label} {/if}</a>
					<div class="col-xs-12 col-sm-12 classnamecontainerclass">
						{if $input.expandstyle_class}
							<div class="col-xs-4 col-sm-3">
								<label data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="Please Enter a custom class for custom design">Custom CSS Class</label>
								<input type="text" class="fixed-width-md classnameclass put-cls-{$input.name}" id="put-cls-{$input.name}" name="put-cls-{$input.name}" value="{if isset($fields_value["put-cls-{$input.name}"])}{$fields_value["put-cls-{$input.name}"]}{/if}">
							</div>
						{/if}
						{if $input.expandstyle_margin}
						<div class="col-xs-4 col-sm-3">
							<label data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="top right bottom left">Custom Margin</label>
							<input type="text" class="fixed-width-md classnamemar put-mar-{$input.name}" id="put-mar-{$input.name}" name="put-mar-{$input.name}" value="{if isset($fields_value["put-mar-{$input.name}"])}{$fields_value["put-mar-{$input.name}"]}{/if}">
						</div>
						{/if}
						{if $input.expandstyle_padding}
						<div class="col-xs-4 col-sm-3">
							<label data-html="true" data-toggle="tooltip" class="label-tooltip" data-original-title="top right bottom left">Custom Padding</label>
							<input type="text" class="fixed-width-md classnamepad put-pad-{$input.name}" id="put-pad-{$input.name}" name="put-pad-{$input.name}" value="{if isset($fields_value["put-pad-{$input.name}"])}{$fields_value["put-pad-{$input.name}"]}{/if}">
						</div>
						{/if}
					</div>
			{/if}
			{if isset($input.type) && $input.type == "file"}
				{if isset($fields_value[$input.name]) && !empty($fields_value[$input.name])}
					<img src="{$image_baseurl}{$fields_value[$input.name]}" height="200px" width="auto"/>
				{/if}
			{/if}
			{$smarty.block.parent}
		</div>
	{/if}
{/block}
{block name="script"}
		$(document).ready(function(){
			$(".classnamecontainerclass").hide();
			$(".click-to-put-cls").on("click",function(){
				$(this).parent().find(".classnamecontainerclass").toggle(500);
			});
		});
{/block}
{block name="input"}
	{if ($input.type == 'selecttwotype')}
			<div class="{if isset($input.hideclass)}{$input.hideclass}{/if} {$input.name} {$input.name}_class" id="{$input.name}_id">
			<select name="selecttwotype_{$input.name}" class="selecttwotype_{$input.name}_cls" id="selecttwotype_{$input.name}_id" multiple="true">
			    {foreach from=$input.initvalues item=initval}
			        {if isset($fields_value[$input.name])}
			            {assign var=settings_def_value value=","|explode:$fields_value[$input.name]}
			            {if $initval['id']|in_array:$settings_def_value}
			                {$selected = 'selected'}
			            {else}
			                {$selected = ''}
			            {/if}
			        {else}
			            {$selected = ''}
			        {/if}
			        <option {$selected} value="{$initval['id']}">{$initval['name']}</option>
			    {/foreach}
			</select>
			<input type="hidden" name="{$input.name}" id="{$input.name}" value="{if isset($input.defvalues)}{$input.defvalues}{else}{$fields_value[$input.name]}{/if}" class=" {$input.name} {$input.type}_field">
			</div>
			<script type="text/javascript">
			    // START SELECT TWO CALLING
			    $(function(){
			        var defVal = $("input#{$input.name}").val();
			        if(defVal.length){
			            var ValArr = defVal.split(',');
			            for(var n in ValArr){
			                $( "select#selecttwotype_{$input.name}_id" ).children('option[value="'+ValArr[n]+'"]').attr('selected','selected');
			            }
			        }
			        $( "select#selecttwotype_{$input.name}_id" ).select2( { placeholder: "{$input.placeholder}", width: 200, tokenSeparators: [',', ' '] } ).on('change',function(){
			            var data = $(this).select2('data');
			            var select = $(this);
			            var field = select.next("input#{$input.name}");
			            var saved = '';
			            select.children('option').attr('selected',null);
			            if(data.length)
			                $.each(data, function(k,v){
			                    var selected = v.id;   
			                    select.children('option[value="'+selected+'"]').attr('selected','selected');
			                    if(k > 0)
			                        saved += ',';
			                    saved += selected;                                
			                });
			             field.val(saved);   
			        });
			    });
 			// END SELECT TWO CALLING
			</script>
			<style type="text/css">
				.select2-container.select2-container-multi
				{ 
					width: 100% !important;
				}
			</style>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}