<?php
class ControllerExtensionModulePhpinfo extends Controller {
    private $version = '1.0 (19.12.2019)';
    private $error = array();
    
	public function index() {
            
		$this->load->language('extension/module/phpinfo');
                
                $data['text_refresh'] = $this->language->get('text_refresh');
                $data['tab_main'] = $this->language->get('tab_main');
                $data['tab_info'] = $this->language->get('tab_info');
                $data['info_version'] = $this->version;
                $data['info_about'] = $this->language->get('info_about');
                $data['info_developer'] = $this->language->get('info_developer');
                $data['info_coffee'] = $this->language->get('info_coffee');
                $data['info_support'] = $this->language->get('info_support');
                $data['error_permission'] = $this->language->get('error_permission');                
		
		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/phpinfo', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['refresh'] = $this->url->link('extension/module/phpinfo', 'user_token=' . $this->session->data['user_token'], true);

                $data['php_info'] = $this->embedded_phpinfo();
                
                $data['header'] = $this->load->controller('common/header');
                $data['column_left'] = $this->load->controller('common/column_left');
                $data['footer'] = $this->load->controller('common/footer');

                $this->response->setOutput($this->load->view('extension/module/phpinfo', $data));
	}
        
        private function embedded_phpinfo()
        {
            ob_start();
            phpinfo();
            $phpinfo = ob_get_contents();
            ob_end_clean();
            $phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
            $phpinfopage = "
                <style type='text/css'>
                    #phpinfo {}
                    #phpinfo pre {margin: 0; font-family: monospace;}
                    #phpinfo a:link {color: #009; text-decoration: none; background-color: #fff;}
                    #phpinfo a:hover {text-decoration: underline;}
                    #phpinfo table {border-collapse: collapse; border: 0; width: 934px; box-shadow: 1px 2px 3px #ccc;}
                    #phpinfo .center {text-align: center;}
                    #phpinfo .center table {margin: 1em auto; text-align: left;}
                    #phpinfo .center th {text-align: center !important;}
                    #phpinfo td, th {border: 1px solid #666; font-size: 75%; vertical-align: baseline; padding: 4px 5px;}
                    #phpinfo h1 {font-size: 150%;}
                    #phpinfo h2 {font-size: 125%;}
                    #phpinfo .p {text-align: left;}
                    #phpinfo .e {background-color: #ccf; width: 300px; font-weight: bold;}
                    #phpinfo .h {background-color: #99c; font-weight: bold;}
                    #phpinfo .v {background-color: #ddd; max-width: 300px; overflow-x: auto; word-wrap: break-word;}
                    #phpinfo .v i {color: #999;}
                    #phpinfo img {float: right; border: 0;}
                    #phpinfo hr {width: 934px; background-color: #ccc; border: 0; height: 1px;}
                </style>
                <div id='phpinfo'>
                    $phpinfo
                </div>
                ";
            return $phpinfopage;
        }


}
