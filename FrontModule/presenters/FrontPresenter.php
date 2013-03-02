<?php

namespace Flame\CMS\FrontModule;

abstract class FrontPresenter extends \Flame\Application\UI\Presenter
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
	
	/**
	 * @autowire
	 * @var \Flame\CMS\TagBundle\Components\TagControlFactory
	 */
	protected $tagControlFactory;


	/**
	 * @autowire
	 * @var \Flame\Addons\FlashMessages\IFlashMessageControlFactory
	 */
	protected $flashMessagesControlFactory;

	public function startup()
	{
		parent::startup();

		if(!$this->getUser()->isAllowed($this->name, $this->view)){
			$this->flashMessage('Access denied', 'error');
			$this->redirect('Homepage:');
		}
	}

	/**
	 * @return \Flame\CMS\TagBundle\Components\TagControl
	 */
	protected function createComponentTagsControl()
	{
		return $this->tagControlFactory->create();
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

	/**
	 * @return \Flame\Addons\FlashMessages\FlashMessageControl
	 */
	protected function createComponentFlasheMessages()
	{
		return $this->flashMessagesControlFactory->create();
	}


}
