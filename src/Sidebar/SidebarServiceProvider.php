<?php
namespace ForggeAppCore\Sidebar;

use Pimple\Container;
use Forgge\ServiceProviders\ServiceProviderInterface;

/**
 * Provide sidebar dependencies.
 *
 * @codeCoverageIgnore
 */
class SidebarServiceProvider implements ServiceProviderInterface {
	/**
	 * {@inheritDoc}
	 */
	public function register( Container $container ): void {
		$container['forgge_app_core.sidebar.sidebar'] = function() {
			return new Sidebar();
		};
	}

	/**
	 * {@inheritDoc}
	 */
	public function bootstrap( Container $container ): void {
		// Nothing to bootstrap.
	}
}
