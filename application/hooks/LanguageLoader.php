 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LanguageLoader
{
  private $locale = array(
        "es" => "spanish",
        "fr" => "french",
        "en" => "english"
    );

   function initialize() {
       $ci =& get_instance();
       $uri =& load_class('URI', 'core');

       //$ci->load->helper('language');
       //$ci->load->helper('url');
       //$siteLang = $ci->session->userdata('site_lang');
       if ($uri->segment(1) == 'en' ||
            //$uri->segment(1) == 'fr'||
           $uri->segment(1) == 'es'
        ) {
            $lang = $this->locale[$uri->segment(1)];
            $ci->config->set_item('language',$lang);
            $ci->session->set_userdata("lang", $uri->segment(1));
            //print_r($lang);
        	  $ci->lang->load('frontend',$lang);
            $new = '';
            if (!empty($uri->segment(3))) { 
                $new = '/'.$uri->segment(3,''); 
            } 
            //redirect($ci->session->flashdata('redirectToCurrent'));
            redirect(base_url().$uri->segment(2,'').$new);
        }
        //print_r($ci->session->userdata('lang'));
        if ($ci->session->userdata('lang') == "es") {
            $lang = "spanish";
            $ci->config->set_item('language',$lang);
            $ci->session->set_userdata("lang",'es');
            $ci->lang->load('frontend',$lang);
        } elseif ($ci->session->userdata('lang') == "en") {
            $lang = "english";
            $ci->config->set_item('language',$lang);
            $ci->session->set_userdata("lang",'en');
            $ci->lang->load('frontend',$lang);
        }/*elseif ($this->session->userdata('lang') == "fr") {
            $lang = "french";
            $ci->config->set_item('language',$lang);
            $ci->session->set_userdata("lang",'fr');
            $ci->lang->load('frontend',$lang);
        }*/else {
            $lang = "spanish";
            $ci->config->set_item('language',$lang);
            $ci->session->set_userdata("lang",'es');
            $ci->lang->load('frontend',$lang);
            /*$lang = "english";
            $ci->config->set_item('language',$lang);
            $ci->session->set_userdata("lang",'en');
            $ci->lang->load('frontend',$lang);*/
        }

        //  $this->lang->load($moduleName, $lang);


       /*if ($siteLang) {
           $ci->lang->load('frontend',$siteLang);
       } else {
           $ci->lang->load('frontend','english');
       }*/
   }
}