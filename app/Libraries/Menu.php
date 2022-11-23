<?php
namespace App\Libraries;
use \Firebase\JWT\JWT;

class Menu{
    public $Db;

    public function __construct()
    {
        $this->Db = \Config\Database::connect();
        helper([ 'common']);
    }
        
    public function display(){

        $url  = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s" : "") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
        $rolesMenuOutput = $rolesSubmenuOutput = '';
        $user_role = session()->get('USR_ROLE_ID');

        $sql = "SELECT ROLE_ID, MENU_ID, MENU_NAME, MENU_URL, MENU_ICON, MENU_CSS_CLASS, ROLE_MENU_ID, ROLE_PERM_STATUS FROM FLXY_MENU LEFT JOIN FLXY_USER_ROLE_PERMISSION ON MENU_ID = ROLE_MENU_ID WHERE PARENT_MENU_ID = 0 AND SHOW_IN_MENU = '1' AND MENU_STATUS = '1' AND ROLE_PERM_STATUS = '1' AND ROLE_ID = ".$user_role." ORDER BY MENU_DIS_SEQ ASC ";
        $responseMenu = $this->Db->query($sql)->getResultArray();  

        if(!empty($responseMenu)){
            foreach($responseMenu as $menu){
                $misc = $addToggle = $submenu_url ='';
                $submenu_url_array = array();
                $subMenu = array();

                //SUBMENU

                $sql = "SELECT MENU_ID, MENU_NAME, MENU_CSS_CLASS, MENU_URL FROM FLXY_MENU WHERE MENU_STATUS = 1 AND SHOW_IN_MENU = 1 AND PARENT_MENU_ID = ".$menu['MENU_ID']." AND PARENT_MENU_ID > 0 ORDER BY MENU_DIS_SEQ ASC"; 
                $subMenu = $this->Db->query($sql)->getResultArray();
                $subMenuCount = $this->Db->query($sql)->getNumRows();
                $menu_url = $menu['MENU_URL'];
                if($menu_url == ''){
                    $menu_url =  ($menu['MENU_NAME'] == 'Dashboard') ? base_url().'/':'javascript:void(0)';                   
                }
                else
                    $menu_url = base_url().'/'.$menu_url;
                
                $rolesSubmenuOutput = '';
                    
                if($subMenuCount > 0){
                    $addToggle = 'menu-toggle';
                    $submenu_item_active = '';
                                          
                    $rolesSubmenuOutput.='<ul class="menu-sub">';
                    foreach($subMenu as $smenu){
                        $submenu_url = ($smenu['MENU_URL'] == '') ? 'javascript:void(0)' : base_url($smenu['MENU_URL']);
                        $submenu_url_array[] = $submenu_url;

                        $submenu_item_active = (isset($url) && ($url == $submenu_url)) ? 'active' : '' ; 
                            
                        $rolesSubmenuOutput.= <<<EOD
                            {$misc}   
                            <li class="menu-item {$submenu_item_active}">
                                <a href="{$submenu_url}" class="menu-link {$smenu['MENU_CSS_CLASS']}">
                                
                                    <div data-i18n="{$smenu['MENU_NAME']}">{$smenu['MENU_NAME']}</div>
                                </a>
                            </li>                         
                        
                            EOD;
                    }
                    $rolesSubmenuOutput.='</ul>';

                }
                else
                    $submenu_url_array[] = $menu_url;

                ///END SUBMENU 
            

                $menu_item_active = '';               
                
               $menu_item_active = (isset($url) && (in_array($url, $submenu_url_array))) ? 'active open' : '' ;   

                $menu_icon = $menu['MENU_ICON'];
                if($menu['MENU_NAME'] == 'Support')
                $misc = '<!-- Misc -->
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Misc</span></li>';

                $rolesMenuOutput.= <<<EOD
                    {$misc}   
                    <li class="menu-item {$menu_item_active}" >
                        <a href="{$menu_url}" class="menu-link {$addToggle} {$menu['MENU_CSS_CLASS']}" >
                        <i class="menu-icon {$menu_icon}"></i>
                            <div data-i18n="{$menu['MENU_NAME']}">{$menu['MENU_NAME']}</div>
                        </a>
                        {$rolesSubmenuOutput}
                    </li>                         
                
                EOD;
            }
        }
       

        return $rolesMenuOutput;
       
    }

   
}
