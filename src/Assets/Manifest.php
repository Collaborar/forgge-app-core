<?php
namespace ForggeAppCore\Assets;

use Forgge\Helpers\MixedType;
use ForggeAppCore\Concerns\JsonFileNotFoundException;
use ForggeAppCore\Concerns\ReadsJsonTrait;

class Manifest {
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
		return MixedType::normalizePath( $this->path . DIRECTORY_SEPARATOR . 'dist' . DIRECTORY_SEPARATOR . 'manifest.json' );
	}

	/**
	 * {@inheritDoc}
	 */
	protected function load( string $file ): array {
		try {
			return $this->traitLoad( $file );
		} catch ( JsonFileNotFoundException $e ) {
			// We used to throw an exception here but it just causes confusion for new users.
		}

		return [];
	}
}
