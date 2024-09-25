<?php
namespace ForggeAppCore\Config;

use Forgge\Helpers\MixedType;
use ForggeAppCore\Concerns\ReadsJsonTrait;

class Config {
	use ReadsJsonTrait {
		load as traitLoad;
	}

	/**
	 * App root path.
	 *
	 * @var string
	 */
	protected string $path = '';

	/**
	 * Constructor.
	 *
	 * @param string $path
	 */
	public function __construct( string $path ) {
		$this->path = $path;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function getJsonPath(): string {
		return MixedType::normalizePath( $this->path . DIRECTORY_SEPARATOR . 'config.json' );
	}
}
