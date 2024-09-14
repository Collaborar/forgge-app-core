<?php
namespace ForggeAppCore\Config;

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
	public function register( $container ) {
		$container['forgge_app_core.config.config'] = function( $c ) {
			return new Config( $c[ FORGGE_CONFIG_KEY ]['app_core']['path'] );
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( $container ) {
		// Nothing to bootstrap.
	}
}
