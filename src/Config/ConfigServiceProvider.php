<?php
namespace ForggeAppCore\Config;

use Pimple\Container;
use Forgge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide assets dependencies.
 *
 * @codeCoverageIgnore
 */
class ConfigServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( Container $container ): void {
		$container['forgge_app_core.config.config'] = function( $c ) {
			return new Config( $c[ FORGGE_CONFIG_KEY ]['app_core']['path'] );
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( Container $container ): void {
		// Nothing to bootstrap.
	}
}
