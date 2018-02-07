<?php
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
include_once(_PS_MODULE_DIR_.'thnxbestsellproductblock/classes/thnxbestsellproductblockclass.php');
class thnxbestsellproductblock extends Module implements WidgetInterface
{
	private $_html = '';
	private $_hooks = '';
	public static $inlinejs = array();
	public static $module_name = 'thnxbestsellproductblock';
	public static $tablename = 'thnxbestsellproductblock';
	public static $classname = 'thnxbestsellproductblockclass';
    public $css_files = array(
    	array(
			'key' => 'thnxbestsellproductblock_css',
			'src' => 'thnxbestsellproductblock.css',
			'priority' => 150,
			'media' => 'all',
			'load_theme' => false,
		),
	);
	public $js_files = array(
		array(
			'key' => 'thnxbestsellproductblock_js',
			'src' => 'thnxbestsellproductblock.js',
			'priority' => 150,
			'position' => 'bottom', // bottom or head
			'load_theme' => false,
		),
	);
	private static $device_desc = array(
			array(
				'name' => 'xs',
				'title' => 'Extra Small Device(xs)',
				'tooltip' => '< 575px',
				'column' => array(1,2,3),
			),
			array(
				'name' => 'sm',
				'title' => 'Small Device(sm)',
				'tooltip' => '> 576px',
				'column' => array(1,2,3,4),
			),
			array(
				'name' => 'md',
				'title' => 'Medium Device(md)',
				'tooltip' => '> 768px',
				'column' => array(1,2,3,4,5,6),
			),
			array(
				'name' => 'lg',
				'title' => 'Large Device(lg)',
				'tooltip' => '> 992px',
				'column' => array(1,2,3,4,5,6),
			),
			array(
				'name' => 'xl',
				'title' => 'Extra Large Device(xl)',
				'tooltip' => '> 1200px',
				'column' => array(1,2,3,4,5,6),
			),
	);
	public function __construct()
	{
		$this->name = 'thnxbestsellproductblock';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'thanksit.com';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		$this->bootstrap = true;
		parent::__construct();
		$this->initializeHooks();
		$this->displayName = $this->l('Platinum Theme Best Selling Products Block');
		$this->description = $this->l('Platinum Theme Best Selling Products Block Modules.');
		$this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
		if(!isset($this->context)){
			$this->context = Context::getContext();
        }
        if((isset($this->context->controller->controller_type)) && ($this->context->controller->controller_type == 'front' || $this->context->controller->controller_type == 'modulefront')){
			global $smarty;
			smartyRegisterFunction($smarty, 'block', 'thnxbestsellproductblock_js', array('thnxbestsellproductblock', 'thnxbestsellproductblock_js'));
			smartyRegisterFunction($smarty, 'function', 'thnxaddJsDef', array('Media', 'addJsDef'));
			smartyRegisterFunction($smarty, 'block', 'thnxaddJsDefL', array('Media', 'addJsDefL'));
		}
	}
	public function install()
	{
		if(!parent::install()
			|| !$this->HookRegister()
			|| !$this->createTables()
			|| !$this->DummyData()
			|| !$this->thankssampledata()
		)
			return false;	
		else	
			return true;
	}
	public function uninstall()
	{
		if(!parent::uninstall()
			|| !$this->deleteTables()
		)
			return false;	
		else	
			return true;
	}
	private function HookRegister(){
		if(isset($this->_hooks) && !empty($this->_hooks)){
			foreach ($this->_hooks as $_hooks_values) {
				if(isset($_hooks_values) && !empty($_hooks_values)){
					foreach ($_hooks_values as $_hook) {
						$this->registerHook($_hook);
					}
				}
			}
		}
		return true;
	}
	private function GetHookarray(){
		$results = array();
		if(isset($this->_hooks['display']) && !empty($this->_hooks['display'])){
			$i = 0;
			foreach ($this->_hooks['display'] as $_hook) {
				$results[$i]['id'] = $_hook;
				$results[$i]['name'] = $_hook;
				$i++;
			}
		}
		return $results;
	}
	private function initializeHooks()
    {
        $this->_hooks = array(
        	'display' => array(
        		'displayLeftColumn',
        		'displayHome',
        		'displayHomeTabContent',
        		'displayHomeTop',
        		'displayHomeMiddle',
        		'displayHomeFullWidthMiddle',
        		'displayHomeBottom',
        		'displayHomeFullWidthBottom',
        		'displayHomeTopLeft',
        		'displayHomeTopLeftOne',
        		'displayHomeTopLeftTwo',
        		'displayHomeTopRight',
        		'displayHomeTopRightOne',
        		'displayHomeTopRightTwo',
        		'displayHomeTopLeftRightBottom',
        		'displayHomeBottomLeft',
        		'displayHomeBottomLeftOne',
        		'displayHomeBottomLeftTwo',
        		'displayHomeBottomRight',
        		'displayHomeBottomRightOne',
        		'displayHomeBottomRightTwo',
        		'displayFooterProduct',
        		'displayCatTop',
        		'displayCatBottom',
        	),
        	'action' => array(
        		'displayHeader',
        		'displayBeforeBodyClosingTag',
        		'displayHomeTab',
        	),
        );
    }
	public function getContent()
	{
		$this->_postProcess();
		if(Tools::getValue("update".$this->name) && Tools::getValue("id_".$this->name)){
			$this->_html .= $this->renderAddForm();
		}elseif(Tools::getValue("add".$this->name)){
			$this->_html .= $this->renderAddForm();
		}else{
			$this->_html .= $this->renderList();
		}
		$this->_html .= $this->headerHTML();
		return $this->_html;
	}
	public function processImage($file_name = null){
		if($file_name == null)
			return false;
	    if(isset($_FILES[$file_name]) && isset($_FILES[$file_name]['tmp_name']) && !empty($_FILES[$file_name]['tmp_name'])){
            $ext = substr($_FILES[$file_name]['name'], strrpos($_FILES[$file_name]['name'], '.') + 1);
            $basename_file_name = basename($_FILES[$file_name]["name"]);
            $strlen = strlen($basename_file_name);
            $strlen_ext = strlen($ext);
            $basename_file_name = substr($basename_file_name,0,($strlen-$strlen_ext));
            $link_rewrite_file_name = Tools::link_rewrite($basename_file_name);
            $file_orgname = $link_rewrite_file_name.'.'.$ext;
            $path = _PS_MODULE_DIR_ .self::$module_name.'/images/' . $file_orgname;
            if(!move_uploaded_file($_FILES[$file_name]['tmp_name'],$path))
                return false;         
            else
                return $file_orgname;   
	    }
	}
	public static function PageException($exceptions = NULL)
	{
		if($exceptions == NULL)
			return false;
		$exceptions = explode(",",$exceptions);
		$page_name = Context::getContext()->controller->php_self;
		$this_arr = array();
		$this_arr[] = 'all_page';
		$this_arr[] = $page_name;
		if($page_name == 'category'){
			$id_category = Tools::getvalue('id_category');
			$this_arr[] = 'cat_'.$id_category;
		}elseif($page_name == 'product'){
			$id_product = Tools::getvalue('id_product');
			$this_arr[] = 'prd_'.$id_product;
			// Start Get Product Category
			$prd_cat_sql = 'SELECT cp.`id_category` AS id
			    FROM `'._DB_PREFIX_.'category_product` cp
			    LEFT JOIN `'._DB_PREFIX_.'category` c ON (c.id_category = cp.id_category)
			    '.Shop::addSqlAssociation('category', 'c').'
			    WHERE cp.`id_product` = '.(int)$id_product;
			$prd_catresults = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($prd_cat_sql);
			if(isset($prd_catresults) && !empty($prd_catresults))
			{
			    foreach($prd_catresults as $prd_catresult)
			    {
			        $this_arr[] = 'prdcat_'.$prd_catresult['id'];
			    }
			}
			// END Get Product Category
			// Start Get Product Manufacturer
			$prd_man_sql = 'SELECT `id_manufacturer` AS id FROM `'._DB_PREFIX_.'product` WHERE `id_product` = '.(int)$id_product;
			$prd_manresults = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($prd_man_sql);
			if(isset($prd_manresults) && !empty($prd_manresults))
			{
			    foreach($prd_manresults as $prd_manresult)
			    {
			        $this_arr[] = 'prdman_'.$prd_manresult['id'];
			    }
			}
			// END Get Product Manufacturer
			// Start Get Product SupplierS
			$prd_sup_sql = "SELECT `id_supplier` AS id FROM `"._DB_PREFIX_."product_supplier` WHERE `id_product` = ".(int)$id_product." GROUP BY `id_supplier`";
			$prd_supresults = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($prd_sup_sql);
			if(isset($prd_supresults) && !empty($prd_supresults))
			{
			    foreach($prd_supresults as $prd_supresult)
			    {
			        $this_arr[] = 'prdsup_'.$prd_supresult['id'];
			    }
			}
			// END Get Product SupplierS
		}elseif($page_name == 'cms'){
			$id_cms = Tools::getvalue('id_cms');
			$this_arr[] = 'cms_'.$id_cms;
		}elseif($page_name == 'manufacturer'){
			$id_manufacturer = Tools::getvalue('id_manufacturer');
			$this_arr[] = 'man_'.$id_manufacturer;
		}elseif($page_name == 'supplier'){
			$id_supplier = Tools::getvalue('id_supplier');
			$this_arr[] = 'sup_'.$id_supplier;
		}
		if(isset($this_arr)){
			foreach ($this_arr as $this_arr_val) {
				if(in_array($this_arr_val,$exceptions))
					return true;
			}
		}
		return false;
	}
	private function _postProcess()
	{
		$errors = array();
		$this->context->controller->addJqueryPlugin('select2');
		$shop_context = Shop::getContext();
		if(Tools::isSubmit('submit'.$this->name))
		{
			$id = Tools::getValue('id_'.$this->name);
			if(isset($id) && !empty($id)){
				$thnxobj = new self::$classname($id);
			}else{
				$thnxobj = new self::$classname();
			}
			// start save
			$languages = Language::getLanguages(false);
			$fields_form = $this->initFieldsForm();
			if(isset($fields_form['form']['input']) && !empty($fields_form['form']['input'])){
				$content_group = array();
				foreach ($fields_form['form']['input'] as $field) {
					if(isset($field['expandstyle']) && $field['expandstyle'] == true){
						$content_group["put-cls-".$field['name']] = Tools::getValue("put-cls-".$field['name']);
						$content_group["put-mar-".$field['name']] = Tools::getValue("put-mar-".$field['name']);
						$content_group["put-pad-".$field['name']] = Tools::getValue("put-pad-".$field['name']);
					}
					if(isset($field['type']) && $field['type'] == 'device'){
					$device_data = array();
						if(isset(self::$device_desc) && !empty(self::$device_desc)){
							foreach (self::$device_desc as $dvce_des) {
								$device_data[$field['name'].'_'.$dvce_des['name']] = Tools::getValue($field['name'].'_'.$dvce_des['name']);
							}
							$thnxobj->{$field['name']} = Tools::jsonEncode($device_data);
						}
					}elseif(isset($field['type']) && $field['type'] == 'file'){
						if(isset($_FILES[$field['name']]) && isset($_FILES[$field['name']]['tmp_name']) && !empty($_FILES[$field['name']]['tmp_name'])){
							$thnxobj->{$field['name']} = $this->processImage($field['name']);
						}else{
							$thnxobj->{$field['name']} = (isset($thnxobj->{$field['name']}) && !empty($thnxobj->{$field['name']})) ? $thnxobj->{$field['name']} : "";
						}
					}else{
						// start
						if(isset($field['group']) && $field['group'] == "content"){
							// start
							if(isset($field['type']) && $field['type'] == 'file'){
								$content_group[$field['name']] = $this->processImage($field['name']);
								if(isset($_FILES[$field['name']]) && isset($_FILES[$field['name']]['tmp_name']) && !empty($_FILES[$field['name']]['tmp_name'])){
									$content_group[$field['name']] = $this->processImage($field['name']);
								}else{
									if(isset($thnxobj->content) && !empty($thnxobj->content)){
										$contentdecode = Tools::jsonDecode($thnxobj->content);
										$content_group[$field['name']] = (isset($contentdecode[$field['name']]) && !empty($contentdecode[$field['name']])) ? $contentdecode[$field['name']] : "";
									}else{
										$content_group[$field['name']] = "";
									}
								}
							}else{
								if(isset($field['lang']) && $field['lang'] == true){
									foreach ($languages as $lang)
									{
										if(isset($field['name']) && !empty($field['name'])){
											$content_group[$field['name']][$lang['id_lang']] = Tools::getValue($field['name'].'_'.(int)$lang['id_lang']);
										}
									}
								}else{
									if(isset($field['name']) && !empty($field['name'])){
										$content_group[$field['name']] = Tools::getValue($field['name']);
									}
								}
							}
							// end
						}else{
							if(isset($field['lang']) && $field['lang'] == true){
								foreach ($languages as $lang)
								{
									if(isset($field['name']) && !empty($field['name'])){
										$thnxobj->{$field['name']}[$lang['id_lang']] = Tools::getValue($field['name'].'_'.(int)$lang['id_lang']);
									}
								}
							}else{
								if(isset($field['name']) && !empty($field['name'])){
									$thnxobj->{$field['name']} = Tools::getValue($field['name']);
								}
							}
						}
						// End
					}
				}
			}
			// end save
			if(isset($content_group) && !empty($content_group)){
				$thnxobj->content = Tools::jsonEncode($content_group);
			}else{
				$thnxobj->content = "";
			}
			$hookname = Tools::getValue("hook");
			if (Validate::isHookName($hookname)) {
				if(!$this->isRegisteredInHook($hookname)){
					$this->registerHook($hookname);
				}
			}
			$res = $thnxobj->save();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Successfully Updated')) : $this->displayError($this->l('The Values could not be Updated.')));
		}
		elseif (Tools::isSubmit('changeStatus') && Tools::isSubmit('id_'.$this->name))
		{
			$thnxobj = new self::$classname((int)Tools::getValue('id_'.$this->name));
			if ($thnxobj->active == 0)
				$thnxobj->active = 1;
			else
				$thnxobj->active = 0;
			$res = $thnxobj->update();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Successfully updated')) : $this->displayError($this->l('The Values could not be updated.')));
		}
		elseif (Tools::isSubmit('delete_id_'.$this->name))
		{
			$thnxobj = new self::$classname((int)Tools::getValue('delete_id_'.$this->name));
			$res = $thnxobj->delete();
			$this->_html .= ($res ? $this->displayConfirmation($this->l('Successfully Deleted')) : $this->displayError($this->l('The Values could not be Deleted.')));
		}
		elseif (Tools::isSubmit('updateblocklistsPosition'))
		{
			$block_lists = array();
			if (Tools::getValue('block_lists'))
			{
				$block_lists = Tools::getValue('block_lists');
				foreach ($block_lists as $position => $id_i)
				{
					$res = Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.self::$tablename.'` SET `position` = '.(int)$position.' WHERE `id_'.self::$tablename.'` = '.(int)$id_i
					);
				}
			}
		}
	}
	public function headerHTML()
	{
		if (Tools::getValue('controller') != 'AdminModules' && Tools::getValue('configure') != $this->name)
			return;
		$links = $this->context->link->getAdminLink('AdminModules')."&configure=".$this->name."&updateblocklistsPosition=1";
		$this->context->controller->addJqueryUI('ui.sortable');
		$html = '<script type="text/javascript">
			$(function() {
				var $mySlides = $("#block_lists");
				$mySlides.sortable({
					opacity: 0.6,
					cursor: "move",
					update: function() {
						var order = $(this).sortable("serialize");
						$.post("'.$links.'", order);
						}
					});
				$mySlides.hover(function() {
					$(this).css("cursor","move");
					},
					function() {
					$(this).css("cursor","auto");
				});
			});
		</script>';
		return $html;
	}
	public function getblock_lists($active = null)
	{
		if(!$this->context)
			$this->context = Context::getContext();
		$id_shop = (int)$this->context->shop->id;
		$id_lang = (int)$this->context->language->id;
        $sql = 'SELECT * FROM `'._DB_PREFIX_.self::$tablename.'` v 
                INNER JOIN `'._DB_PREFIX_.self::$tablename.'_lang` vl ON (v.`id_'.self::$tablename.'` = vl.`id_'.self::$tablename.'` AND vl.`id_lang` = '.$id_lang.') INNER JOIN `'._DB_PREFIX_.self::$tablename.'_shop` vs ON (v.`id_'.self::$tablename.'` = vs.`id_'.self::$tablename.'` AND vs.`id_shop` = '.$id_shop.') ';
        $sql .= 'ORDER BY v.`position` DESC';
        $results = Db::getInstance()->executeS($sql);
        if(isset($results) && !empty($results)){
        	foreach ($results as &$result) {
        		$result['id'] = $result['id_'.self::$tablename];
        	}
        }
        return $results;
	}
	public function displayStatus($id_i, $active)
	{
		$title = ((int)$active == 0 ? $this->l('Disabled') : $this->l('Enabled'));
		$icon = ((int)$active == 0 ? 'icon-remove' : 'icon-check');
		$class = ((int)$active == 0 ? 'btn-danger' : 'btn-success');
		$html = '<a class="btn '.$class.'" href="'.AdminController::$currentIndex.
			'&configure='.$this->name.'
				&token='.Tools::getAdminTokenLite('AdminModules').'
				&changeStatus&id_'.$this->name.'='.(int)$id_i.'" title="'.$title.'"><i class="'.$icon.'"></i></a>';
		return $html;
	}
	public function BlockExists($id_i)
	{
		$req = 'SELECT hs.`id_'.self::$tablename.'` as id_'.self::$tablename.'
				FROM `'._DB_PREFIX_.self::$tablename.'` hs
				WHERE hs.`id_'.self::$tablename.'` = '.(int)$id_i;
		$row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
		return ($row);
	}
	public function renderList()
	{
		$block_lists = $this->getblock_lists();
		foreach ($block_lists as $key => $block)
		{
			$block_lists[$key]['status'] = $this->displayStatus($block['id_'.$this->name], $block['active']);
		}
		$this->context->smarty->assign(
			array(
				'link' => $this->context->link,
				'thnxtablename' => self::$tablename,
				'thnxclassname' => self::$classname,
				'thnxmodulename' => $this->name,
				'block_lists' => $block_lists,
				'image_baseurl' => $this->_path.'images/'
			)
		);
		return $this->display(__FILE__, 'list.tpl');
	}
	public static function layout_style_val()
	{
	    $layout_style_val = array();
	    $theme_path =  _PS_THEME_DIR_.'modules/'.self::$module_name.'/views/templates/front/layout/';
	    $mod_path =  _PS_MODULE_DIR_.self::$module_name.'/views/templates/front/layout/';
	    if(file_exists($theme_path.'default.tpl')){
	        $file_lists = array_diff(scandir($theme_path), array('..', '.'));
	    }else{
		    $file_lists = array_diff(scandir($mod_path), array('..', '.'));
	    }
	    if(isset($file_lists) && !empty($file_lists)){
	        $i = 0;
	        foreach ($file_lists as $key => $value) {
	            $layout_style_val[$i]['id'] = str_replace(".tpl","",$value);
	            $layout_style_val[$i]['name'] = ucwords(str_replace(".tpl","",$value))." Style";
	            $i++;
	        }
	    }
		return $layout_style_val;
	}
	public static function AllPageExceptions()
	{
	    $id_lang = (int)Context::getContext()->language->id;
	    $sql = 'SELECT p.`id_product`, pl.`name`
	            FROM `'._DB_PREFIX_.'product` p
	            '.Shop::addSqlAssociation('product', 'p').'
	            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
	            WHERE pl.`id_lang` = '.(int)$id_lang.' ORDER BY pl.`name`';
	    $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
	    $id_lang = Context::getContext()->language->id;
	    $categories =  Category::getCategories($id_lang,true,false);
	    $controllers = Dispatcher::getControllers(_PS_FRONT_CONTROLLER_DIR_);
	    if(isset($controllers))
	        ksort($controllers);
	    $Manufacturers =  Manufacturer::getManufacturers();
	    $Suppliers =  Supplier::getSuppliers();
	    $rslt = array();
	    $rslt[0]['id'] = 'all_page';
	    $rslt[0]['name'] = 'All Pages';
	    $i = 1;
	    if(isset($controllers))
	        foreach($controllers as $r => $v){
	            $rslt[$i]['id'] = $r;
	            $rslt[$i]['name'] = 'Page : '.ucwords($r);
	            $i++;
	        }
	    if(isset($Manufacturers))
	        foreach($Manufacturers as $r){
	            $rslt[$i]['id'] = 'man_'.$r['id_manufacturer'];
	            $rslt[$i]['name'] = 'Manufacturer : '.$r['name'];
	            $i++;
	        }
	    if(isset($Suppliers))
	        foreach($Suppliers as $r){
	            $rslt[$i]['id'] = 'sup_'.$r['id_supplier'];
	            $rslt[$i]['name'] = 'Supplier : '.$r['name'];
	            $i++;
	        }
	    if(isset($categories))
	        foreach($categories as $cats){
	            $rslt[$i]['id'] = 'cat_'.$cats['id_category'];
	            $rslt[$i]['name'] = 'Category : '.$cats['name'];
	            $i++;
	        }
	    if(isset($products))
	        foreach($products as $r){
	            $rslt[$i]['id'] = 'prd_'.$r['id_product'];
	            $rslt[$i]['name'] = 'Product : '. $r['name'];
	            $i++;
	        }
	    if(isset($categories))
	        foreach($categories as $cats){
	            $rslt[$i]['id'] = 'prdcat_'.$cats['id_category'];
	            $rslt[$i]['name'] = 'Category Product: '.$cats['name'];
	            $i++;
	        }
	    if(isset($Manufacturers))
	        foreach($Manufacturers as $r){
	            $rslt[$i]['id'] = 'prdman_'.$r['id_manufacturer'];
	            $rslt[$i]['name'] = 'Manufacturer Product : '.$r['name'];
	            $i++;
	        }
	    if(isset($Suppliers))
	        foreach($Suppliers as $r){
	            $rslt[$i]['id'] = 'prdsup_'.$r['id_supplier'];
	            $rslt[$i]['name'] = 'Supplier Product : '.$r['name'];
	            $i++;
	        }
	    return $rslt;
	}
	public function initFieldsForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Product Block'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('Title'),
						'name' => 'title',
						'desc' => 'Please Enter Block Title',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Sub Title'),
						'name' => 'sub_title',
						'desc' => 'Please Enter Block Sub Title',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('Section margin'),
						'name' => 'section_margin',
						'group' => 'content',
						'class' => 'fixed-width-lg',
						'desc' => 'Please Enter margin for this section. Ex: 0px 0px 60px 0px (top right bottom left)',
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Select Layout Style'),
					    'name' => 'layout',
					    'required'=>true,
					    'options' => array(
					        'query' => self::layout_style_val(),
					        'id' => 'id',
					        'name' => 'name'
					    )
					),
					array(
					    'type' => 'file',
					    'label' => $this->l('Banner Image'),
					    'name' => 'image',
					    'desc' => 'Please select Image file on your local computer.',
					),
					array(
						'type' => 'text',
						'label' => $this->l('View more button text'),
						'name' => 'view_more_prod',
						'group' => 'content',
						'desc' => 'Please Enter view more button text',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('View more button link'),
						'name' => 'view_more_prod_link',
						'group' => 'content',
						'desc' => 'Please Enter view more button link ( index.php?controller=best-sales ).',
						'lang' => true,
					),
					array(
						'type' => 'text',
						'label' => $this->l('How Many Products You want display'),
						'name' => 'count_prd',
						'class' => 'fixed-width-sm',
						'desc' => 'Please Enter How Many Products You want display',
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Order By'),
					    'name' => 'order_by',
					    'group' => 'content',
					    'options' => array(
					        'query' => array(
					            array(
					                'id'=>'id_product',
					                'name'=>'Product ID',
					            ),
					            array(
					                'id'=>'name',
					                'name'=>'Product Name',
					            ),
					            array(
					                'id'=>'price',
					                'name'=>'Product Price',
					            ),
					            array(
					                'id'=>'date_add',
					                'name'=>'Product Added Date',
					            ),
					            array(
					                'id'=>'date_upd',
					                'name'=>'Product Updated Date',
					            ),
					        ),
					        'id' => 'id',
					        'name' => 'name'
					    ),
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Order Way'),
					    'name' => 'order_way',
					    'group' => 'content',
					    'options' => array(
					        'query' => array(
					            array(
					                'id'=>'ASC',
					                'name'=>'Assending',
					            ),
					            array(
					                'id'=>'DESC',
					                'name'=>'Desending',
					            ),
					        ),
					        'id' => 'id',
					        'name' => 'name'
					    ),
					),
					array(
						'type' => 'device',
						'label' => $this->l('Number of Columns You Want to Display'),
						'name' => 'device',
						'device_desc' => self::$device_desc,
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Enable/Disable Carousel mode'),
						'name' => 'enable_carousel',
						'group' => 'content',
						'is_bool' => true,
						'std' => 1,
						'values' => array(
							array(
								'id' => 'enable_carousel_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'enable_carousel_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Autoplay carousel'),
						'name' => 'autoplay',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'autoplay_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'autoplay_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'text',
						'label' => $this->l('Transition period'),
						'name' => 'transition_period',
						'class' => 'fixed-width-sm',
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Play Again After End of the Slide'),
						'name' => 'play_again',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'play_again_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'play_again_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Products Scroll '),
					    'name' => 'product_scroll',
					    'options' => array(
					        'query' => array(
					        	array(
					        	    'id'=>'per_item',
					        	    'name'=>'Per Item',
					        	),
					            array(
					                'id'=>'per_page',
					                'name'=>'Per Page',
					            ),
					        ),
					        'id' => 'id',
					        'name' => 'name'
					    ),
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Products Rows'),
					    'name' => 'product_rows',
					    'options' => array(
					        'query' => array(
					            array(
					                'id'=>'1',
					                'name'=>'One Line Products',
					            ),
					            array(
					                'id'=>'2',
					                'name'=>'Two Line Products',
					            ),
					        ),
					        'id' => 'id',
					        'name' => 'name'
					    ),
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Navigation arrows style'),
					    'name' => 'nav_arrow_style',
					    'group' => 'content',
					    'options' => array(
					        'query' => array(
					            array(
					                'id'=>'arrow_top',
					                'name'=>'Arrows on top',
					            ),
					            array(
					                'id'=>'arrow_middle',
					                'name'=>'Arrows on middle',
					            ),
					        ),
					        'id' => 'id',
					        'name' => 'name'
					    ),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show Navigation arrows'),
						'name' => 'navigation_arrow',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'navigation_arrow_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'navigation_arrow_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Show Navigation dots'),
						'name' => 'navigation_dots',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'navigation_dots_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'navigation_dots_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
					array(
					    'type' => 'select',
					    'label' => $this->l('Select Hook'),
					    'name' => 'hook',
					    'options' => array(
					        'query' => $this->GetHookarray(),
					        'id' => 'id',
					        'name' => 'name'
					    ),
					),
					array(
					    'type' => 'selecttwotype',
					    'label' => $this->l('Which Page You Want to Display'),
					    'placeholder' => $this->l('Please Type Your Controller Name.'),
					    'initvalues' => self::AllPageExceptions(),
					    'name' => 'pages',
					    'desc' => $this->l('Please Type Your Specific Page Name,Category name,Product Name,For All Product specific Category select category product: category name.<br>For showing All page Type: All Page. For Home Page Type:index.')
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Enabled'),
						'name' => 'active',
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')
							),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')
							)
						),
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				),
			),
		);
		return $fields_form;
	}
	public function renderAddForm()
	{
		$fields_form = $this->initFieldsForm();
		if (Tools::isSubmit('id_'.$this->name) && $this->BlockExists((int)Tools::getValue('id_'.$this->name)))
		{
			$thnxobj = new self::$classname((int)Tools::getValue('id_'.$this->name));
			$fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_'.$this->name);
		}
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->show_cancel_button = true;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submit'.$this->name;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
			'fields_value' => $this->getAddFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'image_baseurl' => $this->_path.'images/'
		);
		$helper->override_folder = '/';
		return $helper->generateForm(array($fields_form));
	}
	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'label' => $this->l('sample'),
						'name' => 'sample',
					),
				),
				'submit' => array(
					'title' => $this->l('Save'),
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitmain'.$this->name;
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);
		return $helper->generateForm(array($fields_form));
	}
	public function getConfigFieldsValues()
	{
		return array(
			'sample' => Tools::getValue('sample', Configuration::get('sample')),
		);
	}
	public function getAddFieldsValues()
	{
		$fields_form = $this->initFieldsForm();
		$languages = Language::getLanguages(false);
		$fields_values = array();
		if(Tools::getValue('id_'.$this->name) && $this->BlockExists((int)Tools::getValue('id_'.$this->name)))
		{
			$thnxobj = new self::$classname((int)Tools::getValue('id_'.$this->name));
			$fields_values['id_'.$this->name] = (int)Tools::getValue('id_'.$this->name, $thnxobj->id);
		}else{
			$thnxobj = new self::$classname();
		}
		if(isset($thnxobj->content) && !empty($thnxobj->content)){
			$content_fields_values = Tools::jsonDecode($thnxobj->content);
			if(isset($content_fields_values) && !empty($content_fields_values)){
				foreach ($content_fields_values as $content_key => $content_value) {
					if(is_object($content_value)){
						foreach ($content_value as $content_value_key => $content_value_value){
							$fields_values_temp[$content_key][$content_value_key] = $content_value_value;
						}
					}else{
						$fields_values_temp[$content_key] = Tools::getValue($content_key, $content_value);
					}
				}
			}
		}
		if(isset($fields_form['form']['input']) && !empty($fields_form['form']['input'])){
			foreach ($fields_form['form']['input'] as $field) {
				if(isset($field['expandstyle']) && $field['expandstyle'] == true){
					$fields_values["put-cls-".$field['name']] = isset($fields_values_temp["put-cls-".$field['name']]) ? $fields_values_temp["put-cls-".$field['name']] : "";
					$fields_values["put-cls-".$field['name']] = isset($fields_values_temp["put-cls-".$field['name']]) ? $fields_values_temp["put-cls-".$field['name']] : "";
					$fields_values["put-mar-".$field['name']] = isset($fields_values_temp["put-mar-".$field['name']]) ? $fields_values_temp["put-mar-".$field['name']] : "";
					$fields_values["put-pad-".$field['name']] = isset($fields_values_temp["put-pad-".$field['name']]) ? $fields_values_temp["put-pad-".$field['name']] : "";
				}
				if(isset($field['type']) && $field['type'] == 'device'){
					$fields_values[$field['name']] = Tools::jsonDecode($thnxobj->{$field['name']});
				}elseif(isset($field['group']) && $field['group'] == "content"){
					// start
						if(isset($field['lang']) && $field['lang'] == true){
							foreach ($languages as $lang)
							{
								if(isset($field['name']) && !empty($field['name'])){
									$fields_values[$field['name']][$lang['id_lang']] = isset($fields_values_temp[$field['name']][$lang['id_lang']]) ? $fields_values_temp[$field['name']][$lang['id_lang']] : "";
								}
							}
						}else{
							if(isset($field['name']) && !empty($field['name'])){
								$fields_values[$field['name']] = isset($fields_values_temp[$field['name']]) ? $fields_values_temp[$field['name']] : "";
							}
						}
					// end
					
				}else{
					if(isset($field['lang']) && $field['lang'] == true){
						foreach ($languages as $lang)
						{
							if(isset($field['name']) && !empty($field['name'])){
								$fields_values[$field['name']][$lang['id_lang']] = Tools::getValue($field['name'].'_'.(int)$lang['id_lang'], $thnxobj->{$field['name']}[$lang['id_lang']]);
							}
						}
					}else{
						if(isset($field['name']) && !empty($field['name'])){
							$fields_values[$field['name']] = Tools::getValue($field['name'], $thnxobj->{$field['name']});
						}
					}
				}
			}
		}
		return $fields_values;
	}
	public function thankssampledata($demo=NULL)
	{
		if(($demo==NULL) || (empty($demo)))
			$demo = "demo_1";
		$this->alldisabled();
		$this->demowiseenabled($demo);
	    return true;
	}
	public function alldisabled(){
		$data = array();
		$data['active'] = 0;
		Db::getInstance()->update(self::$tablename,$data);
		return true;
	}
	public function demowiseenabled($demo = "demo_1"){
		$data = array();
		$data['active'] = 1;
		Db::getInstance()->update(self::$tablename,$data," `truck_identify` = '".$demo."' ");
		return true;
	}
	public function DummyData()
	{
	    include_once(dirname(__FILE__).'/data/dummy_data.php');
	    $this->InsertDummyData($productblock_dummy_data,self::$classname);
	    return true;
	}
	public function InsertDummyData($productblock_dummy_data=NULL,$class=NULL){
		if($productblock_dummy_data == NULL || $class == NULL)
			return false;
		$languages = Language::getLanguages(false);
	    if(isset($productblock_dummy_data) && !empty($productblock_dummy_data)){
	        $classobj = new $class();
	        foreach($productblock_dummy_data as $valu){
	        	if(isset($valu['lang']) && !empty($valu['lang'])){
	        		foreach ($valu['lang'] as $valukey => $value){
	        			foreach ($languages as $language){
	        				if(isset($valukey)){
	        					$classobj->{$valukey}[$language['id_lang']] = isset($value) ? $value : '';
	        				}
	        			}
	        		}
	        	}
        		if(isset($valu['notlang']) && !empty($valu['notlang'])){
        			foreach ($valu['notlang'] as $valukey => $value){
        				if(isset($valukey)){
        					$classobj->{$valukey} = $value;
        				}
        			}
        		}
	        	$classobj->add();
	        }
	    }
	    return true;
	}
	protected function deleteTables(){
		Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.self::$tablename.'`');
		Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.self::$tablename.'_lang`');
		Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.self::$tablename.'_shop`');
		return true;
	}
	protected function createTables()
	{
		Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$tablename.'` (
				`id_'.self::$tablename.'` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`count_prd` VARCHAR(150) NOT NULL,
				`transition_period` VARCHAR(150) NOT NULL,
				`product_scroll` VARCHAR(150) NOT NULL,
				`product_rows` VARCHAR(150) NOT NULL,
				`play_again` int(10) NOT NULL,
				`navigation_dots` int(10) NOT NULL,
				`navigation_arrow` int(10) NOT NULL,
				`autoplay` int(10) NOT NULL,
				`hook` VARCHAR(150) NOT NULL,
				`image` VARCHAR(300) NOT NULL,
				`truck_identify` VARCHAR(150) NOT NULL,
				`pages` text NULL,
				`layout` VARCHAR(150) NOT NULL,
				`device` text NULL,
				`active` int(10) NOT NULL,
				`content` longtext NULL,
				`position` int(10) NOT NULL,
				PRIMARY KEY (`id_'.self::$tablename.'`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',false);

		Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$tablename.'_lang` (
				`id_'.self::$tablename.'` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_lang` int(10) unsigned NOT NULL,
				`title` VARCHAR(200) NOT NULL,
				`sub_title` VARCHAR(500) NOT NULL,
				PRIMARY KEY (`id_'.self::$tablename.'`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;',false);

		Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::$tablename.'_shop` (
			  `id_'.self::$tablename.'` int(11) NOT NULL,
			  `id_shop` int(10) unsigned NOT NULL,
			  PRIMARY KEY (`id_'.self::$tablename.'`,`id_shop`)
			)ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',false);
		return true;
	}
    public static function isEmptyFileContet($path = null){
    	if($path == null)
    		return false;
    	if(file_exists($path)){
    		$content = Tools::file_get_contents($path);
    		if(empty($content)){
    			return false;
    		}else{
    			return true;
    		}
    	}else{
    		return false;
    	}
    }
    public function Register_Css()
    {
        if(isset($this->css_files) && !empty($this->css_files)){
        	$theme_name = $this->context->shop->theme_name;
    		$page_name = $this->context->controller->php_self;
    		$root_path = _PS_ROOT_DIR_.'/';
        	foreach($this->css_files as $css_file):
        		if(isset($css_file['key']) && !empty($css_file['key']) && isset($css_file['src']) && !empty($css_file['src'])){
        			$media = (isset($css_file['media']) && !empty($css_file['media'])) ? $css_file['media'] : 'all';
        			$priority = (isset($css_file['priority']) && !empty($css_file['priority'])) ? $css_file['priority'] : 50;
        			$page = (isset($css_file['page']) && !empty($css_file['page'])) ? $css_file['page'] : array('all');
        			if(is_array($page)){
        				$pages = $page;
        			}else{
        				$pages = array($page);
        			}
        			if(in_array($page_name, $pages) || in_array('all', $pages)){
        				if(isset($css_file['load_theme']) && ($css_file['load_theme'] == true)){
        					$theme_file_src = 'themes/'.$theme_name.'/assets/css/'.$css_file['src'];
        					if(self::isEmptyFileContet($root_path.$theme_file_src)){
        						$this->context->controller->registerStylesheet($css_file['key'], $theme_file_src , ['media' => $media, 'priority' => $priority]);
        					}
        				}else{
        					$module_file_src = 'modules/'.$this->name.'/css/'.$css_file['src'];
        					if(self::isEmptyFileContet($root_path.$module_file_src)){
        						$this->context->controller->registerStylesheet($css_file['key'], $module_file_src , ['media' => $media, 'priority' => $priority]);
        					}
        				}
    				}
        		}
        	endforeach;
        }
        return true;
    }
    public function Register_Js()
    {
        if(isset($this->js_files) && !empty($this->js_files)){
	    	$theme_name = $this->context->shop->theme_name;
			$page_name = $this->context->controller->php_self;
			$root_path = _PS_ROOT_DIR_.'/';
        	foreach($this->js_files as $js_file):
        		if(isset($js_file['key']) && !empty($js_file['key']) && isset($js_file['src']) && !empty($js_file['src'])){
        			$position = (isset($js_file['position']) && !empty($js_file['position'])) ? $js_file['position'] : 'bottom';
        			$priority = (isset($js_file['priority']) && !empty($js_file['priority'])) ? $js_file['priority'] : 50;
        			$page = (isset($css_file['page']) && !empty($css_file['page'])) ? $css_file['page'] : array('all');
        			if(is_array($page)){
        				$pages = $page;
        			}else{
        				$pages = array($page);
        			}
        			if(in_array($page_name, $pages) || in_array('all', $pages)){
	        			if(isset($js_file['load_theme']) && ($js_file['load_theme'] == true)){
	        				$theme_file_src = 'themes/'.$theme_name.'/assets/js/'.$js_file['src'];
	        				if(self::isEmptyFileContet($root_path.$theme_file_src)){
	        					$this->context->controller->registerJavascript($js_file['key'], $theme_file_src , ['position' => $position, 'priority' => $priority]);
	        				}
	        			}else{
		        			$module_file_src = 'modules/'.$this->name.'/js/'.$js_file['src'];
	        				if(self::isEmptyFileContet($root_path.$module_file_src)){
		        				$this->context->controller->registerJavascript($js_file['key'], $module_file_src , ['position' => $position, 'priority' => $priority]);
	        				}
	        			}
        			}
        		}
        	endforeach;
        }
        return true;
    }
	public static function thnxbestsellproductblock_js($params, $content, &$smarty)
	{
		if(isset($params['name']) && !empty($params['name']) && !empty($content)){
			self::$inlinejs[$params['name']] = $content;
		}
	}
	public function hookdisplayBeforeBodyClosingTag($params)
	{
		if(isset(self::$inlinejs) && !empty(self::$inlinejs)){
			foreach (self::$inlinejs as $keyinlinejs => $valueinlinejs) {
				print $valueinlinejs;
			}
		}
	}
	public function hookdisplayHeader($params)
	{
        if((isset($this->context->controller->controller_type)) && ($this->context->controller->controller_type == 'front' || $this->context->controller->controller_type == 'modulefront')){
			global $smarty;
			smartyRegisterFunction($smarty, 'block', 'thnxbestsellproductblock_js', array('thnxbestsellproductblock', 'thnxbestsellproductblock_js'));
			smartyRegisterFunction($smarty, 'function', 'thnxaddJsDef', array('Media', 'addJsDef'));
			smartyRegisterFunction($smarty, 'block', 'thnxaddJsDefL', array('Media', 'addJsDefL'));
		}
		$this->Register_Css();
		$this->Register_Js();
	}
	public function renderWidget($hookName = null, array $configuration = [])
	{
		$html = '';
		$mod_name = self::$module_name;
		$query_values = $this->getWidgetVariables($hookName,$configuration);
		$id_lang  = (int)$this->context->language->id;
		if(isset($query_values) && !empty($query_values)){
			foreach ($query_values as $query => $vale){
				$this->smarty->assign(array($mod_name => $vale));
				$this->context->smarty->assign(array('lang_id' => $id_lang));
				$layout = (isset($vale['layout']) && !empty($vale['layout'])) ? $vale['layout'] :'default';
				$html .= $this->fetch('module:'.$this->name.'/views/templates/front/layout/'.$layout.'.tpl');
			}
		}
		return $html;
	}
	public function getWidgetVariables($hookName = null, array $configuration = [])
	{
	    $classname = self::$classname;
	    $query_values = $classname::GetProductBlock($hookName);
	    return $query_values;
	}
	public function hookexecute($hook,$istab = false)
	{
			$html = '';
			$classname = self::$classname;
			$mod_name = self::$module_name;
			$query_values = $classname::GetProductBlock($hook);
			if(isset($query_values) && !empty($query_values)){
				foreach ($query_values as $query => $vale){
					$this->context->smarty->assign(array($mod_name => $vale));
					if($istab == false){
						$layout = (isset($vale['layout']) && !empty($vale['layout'])) ? $vale['layout'] :'default';
						$html .= $this->fetch('module:'.$this->name.'/views/templates/front/layout/'.$layout.'.tpl');
					}else{
						$html .= $this->fetch('module:'.$this->name.'/views/templates/front/'.$istab.'.tpl');
					}
				}
			}
			return $html;
	}
	public function hookdisplayHomeTab($params)
	{
		return $this->hookexecute('displayHomeTabContent',"tab");
	}
	public function hookdisplayHomeTabContent($params)
	{
		return $this->hookexecute('displayHomeTabContent',"tabcontent");
	}
}