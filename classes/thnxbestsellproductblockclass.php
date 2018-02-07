<?php
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
class thnxbestsellproductblockclass extends ObjectModel
{
	public $id;
	public $id_thnxbestsellproductblock;
	public $position;
	public $hook;
	public $content;
	public $title;
	public $sub_title;
	public $layout;
	public $count_prd;
	public $device;
	public $autoplay;
	public $transition_period;
	public $play_again;
	public $product_scroll;
	public $product_rows;
	public $navigation_arrow;
	public $navigation_dots;
	public $active;
	public $image;
	public $truck_identify;
	public $pages;
	private static $module_name = 'thnxbestsellproductblock';
	private static $tablename = 'thnxbestsellproductblock';
	private static $classname = 'thnxbestsellproductblockclass';
	public static $definition = array(
		'table' => 'thnxbestsellproductblock',
		'primary' => 'id_thnxbestsellproductblock',
		'multilang' => true,
		'fields' => array(
			'title' =>			array('type' => self::TYPE_STRING,'validate' => 'isString','lang' => true),
			'sub_title' =>		array('type' => self::TYPE_STRING,'validate' => 'isString','lang' => true),
			'hook' =>			array('type' => self::TYPE_STRING,'validate' => 'isString'),
			'content' =>		array('type' => self::TYPE_STRING,'validate' => 'isCleanHtml'),
			'layout' =>			array('type' => self::TYPE_STRING,'validate' => 'isString'),
			'count_prd' =>		array('type' => self::TYPE_STRING,'validate' => 'isString'),
			'device' =>			array('type' => self::TYPE_STRING,'validate' => 'isCleanHtml'),
			'image' =>			array('type' => self::TYPE_STRING,'validate' => 'isString'),
			'truck_identify' =>			array('type' => self::TYPE_STRING,'validate' => 'isString'),
			'pages' =>			array('type' => self::TYPE_STRING,'validate' => 'isCleanHtml'),
			'transition_period' =>	array('type' => self::TYPE_STRING,'validate' => 'isString'),
			'product_scroll' =>		array('type' => self::TYPE_STRING,'validate' => 'isString'),
			'product_rows' =>		array('type' => self::TYPE_STRING,'validate' => 'isString'),
			'position' =>			array('type' => self::TYPE_INT,'validate' => 'isunsignedInt'),
			'navigation_arrow' =>	array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
			'navigation_dots' =>	array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
			'active' =>			array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
			'play_again' =>		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
			'autoplay' =>		array('type' => self::TYPE_BOOL,'validate' => 'isBool'),
		)
	);
	public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation(self::$tablename, array('type' => 'shop'));
                parent::__construct($id, $id_lang, $id_shop);
    }
    public function add($autodate = true, $null_values = false)
    {
    	if(isset($this->truck_identify) && !empty($this->truck_identify)){
			$this->truck_identify = $this->truck_identify;
        }else{
        	$this->truck_identify = "";
        }
        if ($this->position <= 0)
            $this->position = self::getHigherPosition() + 1;
        if(!parent::add($autodate, $null_values) || !Validate::isLoadedObject($this))
            return false;
        return true;
    }
    public static function getHigherPosition()
    {
        $sql = 'SELECT MAX(`position`)
                FROM `'._DB_PREFIX_.self::$tablename.'`';
        $position = DB::getInstance()->getValue($sql);
        return (is_numeric($position)) ? $position : -1;
    }
    public static function GetProductBlock($hook = NULL)
    {
        if($hook == NULL)
            return false;
        // start 1.7
        $context = Context::getContext();
        $assembler = new ProductAssembler($context);
        $presenterFactory = new ProductPresenterFactory($context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new ProductListingPresenter(
            new ImageRetriever(
                $context->link
            ),
            $context->link,
            new PriceFormatter(),
            new ProductColorsRetriever(),
            $context->getTranslator()
        );
        $productsfortemplate = array();
        // end 1.7
        $results = array();
        $module_name = self::$module_name;
        $product_type = 'best_product';
        $product_item = NULL;
        $theme_tpl_path = _PS_THEME_DIR_."modules/".$module_name."/views/templates/front/layout/";
        $mod_tpl_path = _PS_MODULE_DIR_.$module_name."/views/templates/front/layout/";
        $allproducts = self::GetAllBlockProductS($hook);
        if(isset($allproducts) && !empty($allproducts)){
            $i = 0;
            foreach ($allproducts as $allprds){
            	if(empty($allprds['pages'])){
					$allprds['pages'] = 'all_page';
				}
                if($module_name::PageException($allprds['pages'])){
                    if(!file_exists(_PS_MODULE_DIR_.$module_name.'/images/'.$allprds['image'])){
						$allprds['image'] = null;
                    }else{
                    	$allprds['image'] = _MODULE_DIR_.$module_name.'/images/'.$allprds['image'];
                    }
                    if(file_exists($theme_tpl_path.$allprds['layout'].".tpl")){
                        $allprds['layout']  =   $allprds['layout'];
                        self::AddMediaFile($allprds['layout'],true);
                        self::AddMediaFile($allprds['layout'],false);
                    }else{
                        if(file_exists($mod_tpl_path.$allprds['layout'].".tpl")){
                            $allprds['layout']  =   $allprds['layout'];
                            self::AddMediaFile($allprds['layout'],true);
                            self::AddMediaFile($allprds['layout'],false);
                        }else{
                            $allprds['layout']  =   'default';
                            self::AddMediaFile('default',true);
                            self::AddMediaFile('default',false);
                        }
                    }
                    $allprds['pages'] =   isset($allprds['pages'])?$allprds['pages']:"";
                    $allprds['hook']  =   isset($allprds['hook'])?$allprds['hook']:"";
                    $allprds['active']    =   $allprds['active'];
                    $allprds['position']  =   $allprds['position'];
                    $order_by = (isset($allprds['order_by']) && !empty($allprds['order_by'])) ? $allprds['order_by'] : "id_product";
                    $order_way = (isset($allprds['order_way']) && !empty($allprds['order_way'])) ? $allprds['order_way'] : "DESC";
                    $count_prd = (isset($allprds['count_prd']) && !empty($allprds['count_prd'])) ? $allprds['count_prd'] : 8;
                    // start 1.7
    				$result_getProducts = self::GetProductProperties($product_type,$product_item,$count_prd,$order_by,$order_way);
            		$productsfortemplate = array();
            		if(isset($result_getProducts) && !empty($result_getProducts)){
    			        foreach ($result_getProducts as $rawProduct) {
    			            $productsfortemplate[] = $presenter->present(
    			                $presentationSettings,
    			                $assembler->assembleProduct($rawProduct),
    			                $context->language
    			            );
    			        }
    					$allprds['products'] = $productsfortemplate;
            		}else{
    					$allprds['products'] = array();
            		}
                    // end 1.7
                    $results[$i] = $allprds;
                $i++;
                }
            }
        }
        return $results;
    }
    public static function GetAllBlockProductS($hook = NULL)
    {
    	$results = array();
        $id_lang = (int)Context::getContext()->language->id;
        $id_shop = (int)Context::getContext()->shop->id;
        $sql = 'SELECT * FROM `'._DB_PREFIX_.self::$tablename.'` pb 
                INNER JOIN `'._DB_PREFIX_.self::$tablename.'_lang` pbl ON (pb.`id_'.self::$tablename.'` = pbl.`id_'.self::$tablename.'` AND pbl.`id_lang` = '.$id_lang.')
                INNER JOIN `'._DB_PREFIX_.self::$tablename.'_shop` pbs ON (pb.`id_'.self::$tablename.'` = pbs.`id_'.self::$tablename.'` AND pbs.`id_shop` = '.$id_shop.')
                ';
        $sql .= ' WHERE pb.`active` = 1 ';
        if($hook != NULL)
            $sql .= ' AND pb.`hook` = "'.$hook.'" ';
        $sql .= ' ORDER BY pb.`position` ASC';
        $results = Db::getInstance()->executeS($sql);
        if(isset($results) && !empty($results) && is_array($results)){
        	foreach ($results as &$result) {
        		if(isset($result['content']) && !empty($result['content'])){
        			$content = Tools::jsonDecode($result['content']);
        			if(isset($content) && !empty($content)){
        				foreach ($content as $content_key => $content_value) {
        					if(isset($content_value) && !is_string($content_value) && is_object($content_value)){
        						foreach ($content_value as $content_value_key => $content_value_value) {
        							$result[$content_key][$content_value_key] = $content_value_value;
        						}
        					}else{
        						$result[$content_key] = $content_value;
        					}
        				}
        			}
        		}
        	}
        }
        return $results;
    }
    public static function AddMediaFile($layout,$css=true)
    {
        if($css){
            $theme_media_path = _PS_THEME_DIR_."css/modules/".self::$module_name."/css/";
            $mod_media_path = _PS_MODULE_DIR_.self::$module_name."/css/";
            $postfix = ".css";
        }else{
            $theme_media_path = _PS_THEME_DIR_."js/modules/".self::$module_name."/js/";
            $mod_media_path = _PS_MODULE_DIR_.self::$module_name."/js/";
            $postfix = ".js";
        }
        if(file_exists($theme_media_path.$layout.$postfix)){
            if($css){
                Context::getContext()->controller->addCSS(_THEME_CSS_DIR_."modules/".self::$module_name."/css/".$layout.$postfix);
            }else{
                Context::getContext()->controller->addJS(_THEME_JS_DIR_."modules/".self::$module_name."/js/".$layout.$postfix);
            }
        }else{
            if(file_exists($mod_media_path.$layout.$postfix)){
                if($css){
                    Context::getContext()->controller->addCSS(_MODULE_DIR_.self::$module_name."/css/".$layout.$postfix);
                }else{
                    Context::getContext()->controller->addJS(_MODULE_DIR_.self::$module_name."/js/".$layout.$postfix);
                }
            }
        }
    }
    public static function GetAllPrdExclude($orderby = 'id_product',$order = 'DESC')
    {
        $excludeprd = '';
        $excludeprds = '';
        if($orderby == 'id_product'){
            $order_init = ' p.`id_product` '.$order;
        }elseif($orderby == 'price'){
            $order_init = ' p.`price` '.$order;
        }elseif($orderby == 'date_add'){
            $order_init = ' p.`date_add` '.$order;
        }elseif($orderby == 'date_upd'){
            $order_init = ' p.`date_upd` '.$order;
        }else{
            $order_init = ' p.`id_product` DESC ';
        }
        $sql = 'SELECT p.`id_product` as id
                FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').'
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` '.Shop::addSqlRestrictionOnLang('pl').')
                WHERE pl.`id_lang` = '.(int)Context::getContext()->language->id.' ORDER BY '.$order_init;
        $allproducts =  Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if(isset($allproducts) && !empty($allproducts)){
            foreach($allproducts as $allprd){
                $excludeprd .= $allprd['id'].',';
            }
            if(isset($excludeprd) && !empty($excludeprd)){
                $excludeprds = substr($excludeprd,0,-1);
            }
        }
        return $excludeprds;
    }
    public static function GetProductsByID($ids = NULL)
    {
        if($ids == NULL)
            return false;
            $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity,
             pl.`description`, pl.`description_short`, product_attribute_shop.id_product_attribute, pl.`link_rewrite`,
              pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, image_shop.`id_image`, il.`legend`,
               m.`name` AS manufacturer_name FROM `' . _DB_PREFIX_ . 'product` p
            ' . Shop::addSqlAssociation('product', 'p') . ' LEFT JOIN ' . _DB_PREFIX_ . 'product_attribute pa ON (pa.id_product = p.id_product) 
            ' . Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1') . '
            ' . Product::sqlStock('p', 0, false) . ' LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON ( p.`id_product` = pl.`id_product` AND pl.`id_lang` = 
                ' . (int) Context::getContext()->language->id . Shop::addSqlRestrictionOnLang('pl') . '
            ) LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)' . Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1') . 
            ' LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) Context::getContext()->language->id . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`) WHERE  p.`id_product` IN(' . $ids . ') AND product_shop.`active` = 1  
            AND product_shop.`show_price` = 1 AND ((image_shop.id_image IS NOT NULL OR i.id_image IS NULL) OR (image_shop.id_image IS NULL AND i.cover=1)) AND (pa.id_product_attribute IS NULL OR 
                product_attribute_shop.default_on = 1)';
            $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            $mainResults = array();
            if(isset($result) && !empty($result)){
            	foreach ($result as $rslt) {
            		$mainResults[$rslt['id_product']] = $rslt;
            	}
            }
            return Product::getProductsProperties((int) Context::getContext()->language->id,$mainResults);
    }
    public static function GetProductsByCatID($category_id,$limit=4, $id_lang = null, $id_shop = null, $child_count = false, $order_by = 'id_product', $order_way = "DESC")
    {
        $context = Context::getContext(); 
        $id_lang = is_null($id_lang) ? $context->language->id : $id_lang ;
        $id_shop = is_null($id_shop) ? $context->shop->id : $id_shop ;
        $id_supplier = '';
        $active = true;
        $front = true;
        $sql = 'SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, 
        product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`,
         pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image, il.`legend`, m.`name` AS manufacturer_name,
          cl.`name` AS category_default, DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
                    DAY)) > 0 AS new, product_shop.price AS orderprice FROM `'._DB_PREFIX_.'category_product` cp LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product` 
        '.Shop::addSqlAssociation('product', 'p').' LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product`)
            '.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
            '.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).' LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').') LEFT JOIN `'._DB_PREFIX_.'image` i
                ON (i.`id_product` = p.`id_product`)'. Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').' LEFT JOIN `'._DB_PREFIX_.'image_lang` il
                ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.') LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
                ON m.`id_manufacturer` = p.`id_manufacturer` WHERE product_shop.`id_shop` = '.(int)$context->shop->id.' AND cp.`id_category` = '.(int)$category_id
                .($active ? ' AND product_shop.`active` = 1' : '') .($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') .($id_supplier ? ' AND p.id_supplier = '.(int)$id_supplier : '')
                .' GROUP BY product_shop.id_product';
        if (empty($order_by) || $order_by == 'position') $order_by = 'price';
        if (empty($order_way)) $order_way = 'DESC';
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add'  || $order_by == 'date_upd')
                $order_by_prefix = 'p';
        else if ($order_by == 'name')
                $order_by_prefix = 'pl';
        $sql .= " ORDER BY {$order_by_prefix}.{$order_by} {$order_way}";
        $sql .= ' LIMIT '.$limit.' '; 
       $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);  
        return Product::getProductsProperties($id_lang,$result);
    }
    // public static function GetBestSellProducts($limit = 10)
    // {
    //     if(Configuration::get('PS_CATALOG_MODE'))
    //         return false;
    //     $context = Context::getcontext();
    //     if(!($result = ProductSale::getBestSalesLight((int)$context->language->id,0,$limit)))
    //         return false;
    //     $currency = new Currency((int)$context->cookie->id_currency);
    //     $usetax = Product::getTaxCalculationMethod();
    //     if(isset($result) && !empty($result)){
    //             foreach ($result as &$row){
    //                 $row['price'] = Tools::displayPrice(Product::getPriceStatic((int)$row['id_product'], $usetax), $currency);
    //             }
    //         return $result;
    //     }else
    //         return false;
    // }
    public static function GetBestSellProducts($limit = 10)
    {
        if(Configuration::get('PS_CATALOG_MODE'))
            return false;
        $context = Context::getcontext();
        $result = ProductSale::getBestSales((int)$context->language->id,0,$limit);
        return $result;
    }
    public static function GetIndividualItem($items=NULL,$pref=NULL,$string=false)
    {
        if($pref == NULL)
            return false; 
        if($items == NULL)
            return false;  
        $results = array();
        $results_str = '';
        $items_arr = explode(",",$items);
        if(isset($items_arr) && !empty($items_arr)){
            foreach($items_arr as $item_ar){
                if(strpos($item_ar,$pref) !== false){
                    $results[] = str_replace($pref.'_',"",$item_ar);
                }
            }
            $results_str = implode(",",$results);
        }
        if($string == false)
            return $results;
        else
            return $results_str;
    }
    public static function GetProductProperties($product_type='new_product',$product_item=NULL,$limit=10,$order_by='id_product',$order_way='DESC')
    {
        $productproperties = array();
        $context = Context::getcontext();
            if($product_type == 'all_product'){
                $prd_ids = self::GetAllPrdExclude($order_by,$order_way);
                $productproperties = self::GetProductsByID($prd_ids);
            }elseif($product_type == 'featured_product'){
                $productproperties = self::GetProductsByCatID((int)Configuration::get('HOME_FEATURED_CAT'),(int)$limit,(int)$context->language->id, null, false, $order_by ,$order_way);
            }elseif($product_type == 'new_product'){
                $productproperties = Product::getNewProducts((int)$context->language->id, 0, (int)$limit,false,$order_by,$order_way);
            }elseif($product_type == 'best_product'){
                $productproperties = self::GetBestSellProducts((int)$limit);
            }elseif($product_type == 'specials_product'){
                $productproperties = Product::getPricesDrop((int)$context->language->id,0,(int)$limit,false,$order_by,$order_way);
            }elseif($product_type == 'category_product'){
                if(isset($product_item) && !empty($product_item)){
                    $category_arr = self::GetIndividualItem($product_item,"cat",false);
                    foreach($category_arr as $category_ar){
                        $cat_products = self::GetProductsByCatID($category_ar,(int)$limit,(int)$context->language->id, null, false, $order_by ,$order_way);
                        if(is_array($cat_products) && !empty($cat_products))
                            $productproperties = array_merge($productproperties,$cat_products);
                    }
                }
            }elseif($product_type == 'selected_product'){
                if(isset($product_item) && !empty($product_item)){
                    $prd_ids = self::GetIndividualItem($product_item,"prd",true);
                    $productproperties = self::GetProductsByID($prd_ids);
                }
            }elseif($product_type == 'manufacturer_product'){
                if(isset($product_item) && !empty($product_item)){
                    $brand_ids_arr = self::GetIndividualItem($product_item,"man",false);
                    foreach($brand_ids_arr as $brand_ids_ar){
                        $manufacturer = new Manufacturer((int)$brand_ids_ar, $context->language->id);
                        $man_products = $manufacturer->getProducts($brand_ids_ar, $context->language->id,1,(int)$limit, $order_by, $order_way);
                        if(is_array($man_products) && !empty($man_products))
                            $productproperties = array_merge($productproperties,$man_products);
                    }
                }
            }elseif($product_type == 'supplier_product'){
                if(isset($product_item) && !empty($product_item)){
                    $supplier_ids_arr = self::GetIndividualItem($product_item,"sup",false);
                    foreach($supplier_ids_arr as $supplier_ids_ar){
                        $sup_products = Supplier::getProducts($supplier_ids_ar, $context->language->id,1, $limit, $order_by, $order_way, false);
                        if(is_array($sup_products) && !empty($sup_products))
                            $productproperties = array_merge($productproperties,$sup_products);
                    }
                }
            }
        return $productproperties;
    }
}