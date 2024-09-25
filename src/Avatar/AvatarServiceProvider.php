<?php
namespace ForggeAppCore\Avatar;

use Pimple\Container;
use Forgge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide avatar dependencies.
 *
 * @codeCoverageIgnore
 */
class AvatarServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( Container $container ): void {
		$container['forgge_app_core.avatar.avatar'] = function() {
			return new Avatar();
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( Container $container ): void {
		$container['forgge_app_core.avatar.avatar']->bootstrap();
	}
}
