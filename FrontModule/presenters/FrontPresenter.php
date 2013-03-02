<?php

namespace Flame\CMS\FrontModule;

abstract class FrontPresenter extends \Flame\CMS\AppBundle\Application\UI\BasePresenter
{

	/**
	 * @autowire
	 * @var \Flame\CMS\SettingBundle\Model\SettingFacade
	 */
	protected $settingFacade;
	
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
			$this->redirect('Homepage:');
		}
	}

	/**
	 * @return \Flame\Components\NavbarBuilder\NavbarBuilderControl
	 */
	protected function createComponentNavbar()
	{
		$control = $this->navbarBuilderControlFactory->create();

		$navbar = $control->getNavbarControl();

//		if(count($items = $this->menuFacade->getLastMenuLinkByPriority())){
//			foreach($items as $item){
//				$navbar->addItem($item->title, $item->url);
//			}
//		}

		return $control;
	}

}
