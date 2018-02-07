
<div class="panel"><h3><i class="icon-list-ul"></i> {l s='Block list' mod='thnxbestsellproductblock'}
	<span class="panel-heading-action">
		<a id="desc-product-new" class="list-toolbar-btn" href="{$link->getAdminLink('AdminModules')}&configure={$thnxmodulename}&add{$thnxmodulename}=1">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="Add new" data-html="true">
				<i class="process-icon-new "></i>
			</span>
		</a>
	</span>
	</h3>
	<div id="{$thnxmodulename}Content">
			{if isset($block_lists) && !empty($block_lists)}
		<div id="block_lists">
			{foreach from=$block_lists item=block_list}
				<div id="block_lists_{$block_list.id}" class="panel">
					<div class="row">
						<div class="col-md-1">
							<div><i class="icon-arrows"></i></div>
						</div>
						<div class="col-md-3">
							{$block_list.id}
						</div>
						<div class="col-md-8">
							<h4 class="pull-left">
								{$block_list.title}
							</h4>
							<div class="btn-group-action pull-right">
								{$block_list.status}
								<a class="btn btn-default"
									href="{$link->getAdminLink('AdminModules')}&configure={$thnxmodulename}&id_{$thnxmodulename}={$block_list.id}&update{$thnxmodulename}=1">
									<i class="icon-edit"></i>
								</a>
								<a class="btn btn-default"
									href="{$link->getAdminLink('AdminModules')}&configure={$thnxmodulename}&delete_id_{$thnxmodulename}={$block_list.id}">
									<i class="icon-trash"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
			{else}
				<center><p style="font-size:15px;"><a id="desc-product-new2" class="list-toolbar-btn" href="{$link->getAdminLink('AdminModules')}&configure={$thnxmodulename}&add{$thnxmodulename}=1">You don't have any block lists. Please  click here  to add new.</a></p></center>
			{/if}
	</div>
</div>
<style type="text/css">
	#block_lists .panel{
	    padding: 0px !important;
	    padding-left: 15px !important;
	    padding-right: 15px !important;
	    margin-bottom: 10px !important;
	}
</style>