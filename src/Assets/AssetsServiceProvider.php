<?php
namespace ForggeAppCore\Assets;

use Pimple\Container;
use Forgge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide assets dependencies.
 *
 * @codeCoverageIgnore
 */
class AssetsServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( Container $container ): void {
		$container['forgge_app_core.assets.manifest'] = function( $c ) {
			return new Manifest( $c[ FORGGE_CONFIG_KEY ]['app_core']['path'] );
		};

		$container['forgge_app_core.assets.assets'] = function( $container ) {
			return new Assets(
				$container[ FORGGE_CONFIG_KEY ]['app_core']['path'],
				$container[ FORGGE_CONFIG_KEY ]['app_core']['url'],
				$container['forgge_app_core.config.config'],
				$container['forgge_app_core.assets.manifest'],
				$container[ FORGGE_APPLICATION_FILESYSTEM_KEY ]
			);
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( Container $container ): void {
		// Nothing to bootstrap.
	}
}
