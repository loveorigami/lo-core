<?php
namespace lo\core\widgets\user;

use lo\core\widgets\App;

/**
 * Class User
 * Виджет формы авторизации
 * @package app\modules\main\widgets\user
 * @author Chernyavsky Denis <panopticum87@gmail.com>
 */
class User extends App
{

	/**
	 * @var array маршрут отправки формы авторизации
	 */
	public $signInRoute = ['/user/login'];

	/**
	 * @var array маршрут выхода
	 */
	public $signOutRoute = ['/user/logout'];

	/**
	 * @var array маршрут регистрации
	 */
	public $signUpRoute = ['/user/register'];

	/**
	 * @var array маршрут просмотра профиля
	 */
	public $viewRoute = ['/user/settings/profile'];

	/**
	 * @var array маршрут редактирования профиля
	 */
	public $updateRoute = ['/user/settings/profile/'];

	/**
	 * @var string селектор для ссылки по которой открывать виджет обратной связи в fancybox. Если не задан форма выводится на странице
	 */
	public $signInfancySelector;


	public function run()
	{
		return $this->render($this->tpl, [
			'signInRoute' => $this->signInRoute,
			'signOutRoute' => $this->signOutRoute,
			'signUpRoute' => $this->signUpRoute,
			'viewRoute' => $this->viewRoute,
			'updateRoute' => $this->updateRoute,
		]);
	}
}
