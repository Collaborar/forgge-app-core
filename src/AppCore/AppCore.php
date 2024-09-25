<?php
namespace ForggeAppCore\AppCore;

use Forgge\Application\Application;
use ForggeAppCore\Assets\Assets;
use ForggeAppCore\Avatar\Avatar;
use ForggeAppCore\Config\Config;
use ForggeAppCore\Image\Image;
use ForggeAppCore\Sidebar\Sidebar;

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
	public function __construct( Application $app ) {
		$this->app = $app;
	}

	/**
	 * Shortcut to \ForggeAppCore\Assets\Assets.
	 *
	 * @return Assets
	 */
	public function assets(): Assets {
		return $this->app->resolve( 'forgge_app_core.assets.assets' );
	}

	/**
	 * Shortcut to \ForggeAppCore\Avatar\Avatar.
	 *
	 * @return Avatar
	 */
	public function avatar(): Avatar {
		return $this->app->resolve( 'forgge_app_core.avatar.avatar' );
	}

	/**
	 * Shortcut to \ForggeAppCore\Config\Config.
	 *
	 * @return Config
	 */
	public function config(): Config {
		return $this->app->resolve( 'forgge_app_core.config.config' );
	}

	/**
	 * Shortcut to \ForggeAppCore\Image\Image.
	 *
	 * @return Image
	 */
	public function image(): Image {
		return $this->app->resolve( 'forgge_app_core.image.image' );
	}

	/**
	 * Shortcut to \ForggeAppCore\Sidebar\Sidebar.
	 *
	 * @return Sidebar
	 */
	public function sidebar(): Sidebar {
		return $this->app->resolve( 'forgge_app_core.sidebar.sidebar' );
	}
}
