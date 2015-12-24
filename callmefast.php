<?php

if (!defined('_PS_VERSION_'))
	exit;

class callmefast extends Module
{

	public function __construct()
	{
		$this->name = 'callmefast';
		$this->tab = 'front_office_features';
		$this->version = '1.0.0';
		$this->author = 'Ismail Albakov (wowsite.ru)';

		$this->bootstrap = true;

		parent::__construct();

		$this->displayName = $this->l('Call me form');
		$this->description = $this->l('Shows call me modal form');

		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
	}

	public function install()
	{
		return parent::install() &&
			$this->registerHook('displayNav') &&
			$this->registerHook('displayCallMeBtn') &&
			$this->registerHook('displayFooter');
	}

	public function uninstall()
	{
		return parent::uninstall();
	}

	// render hidden modal block in footer
	public function hookDisplayFooter($params)
	{
		$this->context->controller->addCSS($this->_path.'assets/css/modalcallme.css');
		$this->context->controller->addJS($this->_path.'assets/js/modalcallme.js');
		$this->context->controller->addJS($this->_path.'assets/js/jquery.arcticmodal-0.3.min.js');

		$token = md5($this->context->cookie->id_guest);

		$this->smarty->assign(array(
			'callme' => true,
			'token' => $token,
		));

		return $this->display(__FILE__, 'views/templates/hooks/call_me.tpl');
	}

	// render call me btn in contact menu (header)
	public function hookDisplayNav($params)
	{
		return $this->display(__FILE__, 'views/templates/hooks/callme_btn.tpl');
	}

	// render call me btn in contact menu (custom place in template)
	public function hookDisplayCallMeBtn($params)
	{
		return $this->hookDisplayNav($params);
	}

	// render response in modal form
	public function postEmail($data)
	{
		$validate = $this->validation($data);

		if ($validate && empty($validate['error']))
		{
			$template_vars = array(
				'{guest_name}' => $validate['name'],
				'{guest_phone}' => $validate['phone'],			
			);

			$shop_email = strval(Configuration::get('PS_SHOP_EMAIL'));

			if( Mail::Send($this->context->language->id, 'callme', Mail::l('Email Call Me', $this->context->language->id), $template_vars, $shop_email, null, null, null, null, null, dirname(__FILE__).'/mails/', false, $this->context->shop->id))
				return $this->viewStatus(true);
		} 
		else 
		{
			return $this->viewStatus(false);
		}

	}

	// validate incomming data
	public function validation($data)
	{
		if (empty($data['phone'])) {
			$data['error'] = 'phone is empty!';
			return $data;
			die();
		}

		if (empty($data['token'])) {
			$data['error'] = 'token is empty!';
			return $data;
			die();
		}

		$sended_token = substr(md5($data['token']), 0, 5);
		$current_token = substr(md5(md5($this->context->cookie->id_guest)), 0, 5);

		if ($sended_token != $current_token) {
			$data['error'] = 'token not valid!';
			return $data;
			die();
		}

		$data['phone'] = substr( htmlspecialchars( trim($data['phone'] ) ), 0, 20);
		$data['name'] = substr( htmlspecialchars( trim($data['name'] ) ), 0, 20);

		return $data;
	}

	// view where is mistake
	public function viewStatus($status) {
		if ($status) {
			echo '<script>$( "#callme-form" ).empty()</script><style> #callme-result { display: block!important; } </style>';
			echo $this->l('Thank you for contacting ! We will contact you shortly!');
		}
		else
		{
			echo "<style> .call_me_phone { border: 1px solid #F44336; } </style>";
		}		
	}

}