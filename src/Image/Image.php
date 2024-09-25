<?php
namespace ForggeAppCore\Image;

use Forgge\Helpers\MixedType;

class Image {
	/**
	 * Filesystem.
	 *
	 * @var \WP_Filesystem_Base
	 */
	protected ?\WP_Filesystem_Base $filesystem = null;

	/**
	 * Constructor.
	 *
	 * @param \WP_Filesystem_Base $filesystem
	 */
	public function __construct( \WP_Filesystem_Base $filesystem ) {
		$this->filesystem = $filesystem;
	}

	/**
	 * Get a suitable name for a resized version of an image file.
	 *
	 * @param  string  $filepath
	 * @param  int $width
	 * @param  int $height
	 * @param  bool $crop
	 * @return string
	 */
	protected function getResizedFilename( string $filepath, int $width, int $height, bool $crop ): string {
		$filename = basename( $filepath );

		// match filename extension with dot
		// only the last extension will match when there are multiple ones
		$extension_pattern = '/(\.[^\.]+)$/';

		// add width, height and crop to filename
		$replacement = '-' . $width . 'x' . $height . ( $crop ? '-cropped' : '' ) . '$1';

		return preg_replace( $extension_pattern, $replacement, $filename );
	}

	/**
	 * Resize and store a copy of an image file.
	 *
	 * @param  string  $source
	 * @param  string  $destination
	 * @param  int $width
	 * @param  int $height
	 * @param  bool $crop
	 * @return string
	 */
	protected function store( string $source, string $destination, int $width, int $height, bool $crop ): string {
		if ( $this->filesystem->exists( $destination ) ) {
			return $destination;
		}

		$editor = wp_get_image_editor( $source );

		if ( is_wp_error( $editor ) ) {
			return '';
		}

		$editor->resize( $width, $height, $crop );
		$editor->save( $destination );

		return $destination;
	}

	/**
	 * Dynamically generate a thumbnail (if one is not already available) and return the url.
	 *
	 * @param  int $attachment_id
	 * @param  int $width
	 * @param  int $height
	 * @param  bool $crop
	 * @return string
	 */
	public function thumbnail( int $attachment_id, int $width, int $height, bool $crop = true ): string {
		$width = absint( $width );
		$height = absint( $height );

		$upload_dir = wp_upload_dir();
		$attachment = wp_get_attachment_metadata( $attachment_id );
		$source = MixedType::normalizePath( get_attached_file( $attachment_id ) );

		if ( ! $attachment || ! $this->filesystem->exists( $source ) ) {
			return '';
		}

		$attachment_subdirectory = preg_replace( '/\/?[^\/]+\z/', '', $attachment['file'] );
		$filename = $this->getResizedFilename( $source, $width, $height, $crop );
		$destination = MixedType::normalizePath( MixedType::normalizePath( $upload_dir['basedir'] ) . DIRECTORY_SEPARATOR . $attachment_subdirectory ) . DIRECTORY_SEPARATOR . $filename;

		$stored = $this->store( $source, $destination, $width, $height, $crop );

		if ( empty( $stored ) ) {
			return '';
		}

		$fileurl = trailingslashit( $upload_dir['baseurl'] ) . $attachment_subdirectory . '/' . $filename;

		return $fileurl;
	}
}
