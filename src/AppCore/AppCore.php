<?php
namespace ForggeAppCore\AppCore;

use Forgge\Application\Application;

/**
 * Main communication channel with the theme.
 */
class AppCore {
	/**
	 * Application instance.
	 *
	 * @var Application
	 */
	protected $app = null;

	/**
	 * Constructor.
	 *
	 * @param Application $app
	 */
	public function __construct( $app ) {
		$this->app = $app;
	}

	/**
	 * Shortcut to \ForggeAppCore\Assets\Assets.
	 *
	 * @return \ForggeAppCore\Assets\Assets
	 */
	public function assets() {
		return $this->app->resolve( 'forgge_app_core.assets.assets' );
	}

	/**
	 * Shortcut to \ForggeAppCore\Avatar\Avatar.
	 *
	 * @return \ForggeAppCore\Avatar\Avatar
	 */
	public function avatar() {
		return $this->app->resolve( 'forgge_app_core.avatar.avatar' );
	}

	/**
	 * Shortcut to \ForggeAppCore\Config\Config.
	 *
	 * @return \ForggeAppCore\Config\Config
	 */
	public function config() {
		return $this->app->resolve( 'forgge_app_core.config.config' );
	}

	/**
	 * Shortcut to \ForggeAppCore\Image\Image.
	 *
	 * @return \ForggeAppCore\Image\Image
	 */
	public function image() {
		return $this->app->resolve( 'forgge_app_core.image.image' );
	}

	/**
	 * Shortcut to \ForggeAppCore\Sidebar\Sidebar.
	 *
	 * @return \ForggeAppCore\Sidebar\Sidebar
	 */
	public function sidebar() {
		return $this->app->resolve( 'forgge_app_core.sidebar.sidebar' );
	}
}
