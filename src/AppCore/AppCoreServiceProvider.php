<?php
namespace ForggeAppCore\AppCore;

use Pimple\Container;
use Forgge\ServiceProviders\ExtendsConfigTrait;
use Forgge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide theme dependencies.
 *
 * @codeCoverageIgnore
 */
class AppCoreServiceProvider implements ServiceProviderInterface {
	use ExtendsConfigTrait;

	/**
	 * {@inheritDoc}
	 */
	public function register( Container $container ): void {
		$this->extendConfig( $container, 'app_core', [
			'path' => '',
			'url' => '',
		] );

		$container['forgge_app_core.app_core.app_core'] = function( $c ) {
			return new AppCore( $c[ FORGGE_APPLICATION_KEY ] );
		};

		$app = $container[ FORGGE_APPLICATION_KEY ];
		$app->alias( 'core', 'forgge_app_core.app_core.app_core' );
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( Container $container ): void {
		// Nothing to bootstrap.
	}
}
