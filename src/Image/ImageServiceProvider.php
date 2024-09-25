<?php
namespace ForggeAppCore\Image;

use Pimple\Container;
use Forgge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide image dependencies.
 *
 * @codeCoverageIgnore
 */
class ImageServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( Container $container ): void {
		$container['forgge_app_core.image.image'] = function( $c ) {
			return new Image( $c[ FORGGE_APPLICATION_FILESYSTEM_KEY ] );
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( Container $container ): void {
		// Nothing to bootstrap.
	}
}
