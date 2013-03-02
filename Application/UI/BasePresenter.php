<?php
/**
 * BasePresenter.php
 *
 * @author  Jiří Šifalda <sifalda.jiri@gmail.com>
 * @date    02.03.13
 */

namespace Flame\CMS\AppBundle\Application\UI;

class BasePresenter extends \Flame\Application\UI\Presenter
{

	/**
	 * @autowire
	 * @var \Flame\Addons\FlashMessages\IFlashMessageControlFactory
	 */
	protected $flashMessagesControlFactory;

	/**
	 * @return \Flame\Addons\FlashMessages\FlashMessageControl
	 */
	protected function createComponentFlasheMessages()
	{
		return $this->flashMessagesControlFactory->create();
	}

	/**
	 * @return \Flame\CMS\UserBundle\Security\User
	 */
	public function getUser()
	{
		return parent::getUser();
	}

}
