<?php

namespace Flame\CMS\AdminModule;

use Flame\Utils\Strings;

abstract class AdminPresenter extends \Flame\CMS\AppBundle\Application\UI\BasePresenter
{

	/**
	 * @var string
	 */
	protected $loginLink = ':Front:Sign:in';

	/**
	 * @autowire
	 * @var \Flame\Loaders\PresenterLoader
	 */
	protected $presenterLoader;

	/**
	 * @autowire
	 * @var \Flame\Components\NavbarBuilder\INavbarBuilderControlFactory
	 */
	protected $navbarBuilderControlFactory;

	public function startup()
	{
		parent::startup();

		if(!$this->getUser()->isAllowed($this->name, $this->view)){
			$this->flashMessage('Access denied', 'error');
			$this->redirect('Dashboard:');
		}
	}

	/**
	 * @param $element
	 */
	public function checkRequirements($element)
	{
		parent::checkRequirements($element);
		if (!$this->getUser()->isLoggedIn()) {
			$this->redirect($this->loginLink, array('backlink' => $this->storeRequest()));
		}
	}

	public function handleLogout()
	{
		$this->getUser()->logout(TRUE);
		$this->redirect($this->loginLink);
	}

	public function beforeRender()
	{
		parent::beforeRender();

		$this->template->breadCrumbs = $this->generateBreadCrumb();
	}

	/**
	 * @return array
	 */
	protected function generateBreadCrumb()
	{
		$parts = array(
			array('link' => $this->link('Dashboard:'), 'name'  => 'Admin'),
			array('link' => $this->link(':' . $this->name . ':'), 'name' => Strings::getLastPiece($this->name, ':')),
			array('link' => $this->link(':' . $this->name . ':' . $this->view), 'name' => $this->view),
		);


		if($parameter = $this->getParameter('id')){
			$parts[] = array('link' => $this->link(':' . $this->name . ':' . $this->view, array('id' => $parameter)), 'name' => $parameter);
		}

		return \Nette\ArrayHash::from($parts);
	}

	/**
	 * @return \Flame\Components\NavbarBuilder\NavbarBuilderControl
	 */
	protected function createComponentNavbarBuilder()
	{
		$control = $this->navbarBuilderControlFactory->create();
		$control->setTitle('Administration', 'Dashboard:');
		$control->displayUserbar();

		$navbar = $control->getNavbarControl();

		$items = $this->getNavItems();

		foreach($items as $item){
			if($this->getUser()->isAllowed('Admin:' . $item)){
				$navbar->addItem($item, $this->link($item . ':'));
			}
		}

		$userbar = $control->getUserbarControl();
		$userbar->addItem('Account settings', $this->link('User:edit'), 'icon-edit');
		$userbar->addItem('Change password', $this->link('User:password'), 'icon-lock');
		$userbar->setUserName($this->getUser()->getIdentity());

		return $control;
	}

	/**
	 * @return array
	 */
	protected function getNavItems()
	{
		$exlude = $this->getContextParameter('disabledPresenters', array('Admin'));;
		$presenters = $this->presenterLoader->load()->getPresentersName(__NAMESPACE__);

		if(count($exlude)){
			foreach($exlude as $name){
				$search = array_search($name, $presenters);
				unset($presenters[$search]);
			}
		}

		return $presenters;
	}

}
