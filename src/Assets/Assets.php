<?php
namespace ForggeAppCore\Assets;

use Forgge\Helpers\MixedType;
use Forgge\Helpers\Url;
use ForggeAppCore\Config\Config;

class Assets {
	/**
	 * App root path.
	 *
	 * @var string
	 */
	protected string $path = '';

	/**
	 * App root URL.
	 *
	 * @var string
	 */
	protected string $url = '';

	/**
	 * Config.
	 *
	 * @var Config
	 */
	protected ?Config $config = null;

	/**
	 * Manifest.
	 *
	 * @var Manifest
	 */
	protected ?Manifest $manifest = null;

	/**
	 * Filesystem.
	 *
	 * @var \WP_Filesystem_Base
	 */
	protected ?\WP_Filesystem_Base $filesystem = null;

	/**
	 * Constructor.
	 *
	 * @param string              $path
	 * @param string              $url
	 * @param Config              $config
	 * @param Manifest            $manifest
	 * @param \WP_Filesystem_Base $filesystem
	 */
	public function __construct( string $path, string $url, Config $config, Manifest $manifest, \WP_Filesystem_Base $filesystem ) {
		$this->path = MixedType::removeTrailingSlash( $path );
		$this->url = Url::removeTrailingSlash( $url );
		$this->config = $config;
		$this->manifest = $manifest;
		$this->filesystem = $filesystem;
	}

	/**
	 * Remove the protocol from an http/https url.
	 *
	 * @param  string $url
	 * @return string
	 */
	protected function removeProtocol( string $url ): string {
		return (string) preg_replace( '~^https?:~i', '', $url );
	}

	/**
	 * Get if a url is external or not.
	 *
	 * @param  string  $url
	 * @param  string  $home_url
	 * @return bool
	 */
	protected function isExternalUrl( string $url, string $home_url ): bool {
		$delimiter = '~';
		$pattern_home_url = preg_quote( $home_url, $delimiter );
		$pattern = $delimiter . '^' . $pattern_home_url . $delimiter . 'i';
		return ! preg_match( $pattern, $url );
	}

	/**
	 * Generate a version for a given asset src.
	 *
	 * @param  string          $src
	 * @return int|bool
	 */
	protected function generateFileVersion( string $src ): int|bool {
		// Normalize both URLs in order to avoid problems with http, https
		// and protocol-less cases.
		$src = $this->removeProtocol( $src );
		$home_url = $this->removeProtocol( WP_CONTENT_URL );
		$version = false;

		if ( ! $this->isExternalUrl( $src, $home_url ) ) {
			// Generate the absolute path to the file.
			$file_path = MixedType::normalizePath( str_replace(
				[$home_url, '/'],
				[WP_CONTENT_DIR, DIRECTORY_SEPARATOR],
				$src
			) );

			if ( $this->filesystem->exists( $file_path ) ) {
				// Use the last modified time of the file as a version.
				$version = $this->filesystem->mtime( $file_path );
			}
		}

		return $version;
	}

	/**
	 * Get the public URL to the app root.
	 *
	 * @return string
	 */
	public function getUrl(): string {
		return $this->url;
	}

	/**
	 * Get the public URL to a generated asset based on manifest.json.
	 *
	 * @param string $asset
	 *
	 * @return string
	 */
	public function getAssetUrl( string $asset ): string {
		// Path with unix-style slashes.
		$path = $this->manifest->get( $asset, '' );

		if ( ! $path ) {
			return '';
		}

		$url = wp_parse_url( $path );

		if ( isset( $url['scheme'] ) ) {
			// Path is an absolute URL.
			return $path;
		}

		// Path is relative.
		return $this->getUrl() . '/dist/' . $path;
	}

	/**
	 * 
	 * Get the directory to a generated asset.
	 *
	 * @param string $asset
	 * @return string
	 */
	public function getAssetDir( string $asset ): string {
		$file = implode( DIRECTORY_SEPARATOR, [ $this->path, 'dist', $asset ] );

		if ( ! $this->filesystem->exists( $file ) ) {
			return '';
		}

		return $file;
	}

	/**
	 * Get the public URL to a generated JS or CSS bundle.
	 * Handles hot reloading.
	 *
	 * @param string  $name Source basename (no extension).
	 * @param '.js'|'.css'  $extension Source extension - '.js' or '.css'.
	 * @return string
	 */
	public function getBundleUrl( string $name, string $extension ): string {
		$file = $this->getAssetDir( "{$name}{$extension}" );

		if ( ! $this->filesystem->exists( $file ) ) {
			return false;
		}

		$development = implode( DIRECTORY_SEPARATOR, [ $this->path, 'dist', 'development.json' ] );
		$is_development = $this->filesystem->exists( $development );
		$is_hot = false;

		if ( $is_development ) {
			$json = json_decode( $this->filesystem->get_contents( $development ) );
			$is_hot = $json->hot;
		}

		$url_path = ( '.css' === $extension && is_rtl() ) ? "{$name}-rtl" : $name;

		if ( $is_hot ) {
			$hot_url = wp_parse_url( $this->config->get( 'development.hotUrl', 'http://localhost/' ) );
			$hot_port = $this->config->get( 'development.port', 3000 );

			return "{$hot_url['scheme']}://{$hot_url['host']}:{$hot_port}/{$url_path}{$extension}";
		}

		return "{$this->getUrl()}/dist/{$url_path}{$extension}";
	}

	/**
	 * Enqueue a style, dynamically generating a version for it.
	 *
	 * @param  string        $handle
	 * @param  string        $src
	 * @param  array<string> $dependencies
	 * @param  string        $media
	 * @return void
	 */
	public function enqueueStyle( string $handle, string $src, array $dependencies = [], string $media = 'all' ): void {
		wp_enqueue_style( $handle, $src, $dependencies, $this->generateFileVersion( $src ), $media );
	}

	/**
	 * Enqueue a script, dynamically generating a version for it.
	 *
	 * @param  string        $handle
	 * @param  string        $src
	 * @param  array<string> $dependencies
	 * @param  bool       $in_footer
	 * @return void
	 */
	public function enqueueScript( string $handle, string $src, array $dependencies = [], bool $in_footer = false ): void {
		wp_enqueue_script( $handle, $src, $dependencies, $this->generateFileVersion( $src ), $in_footer );
	}

	/**
	 * Add favicon meta.
	 *
	 * @return void
	 */
	public function addFavicon(): void {
		if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
			// allow users to override the favicon using the WordPress Customizer
			return;
		}

		$favicon_url = apply_filters( 'forgge_app_core_favicon_url', $this->getAssetUrl( 'images/favicon.ico' ) );

		echo '<link rel="shortcut icon" href="' . $favicon_url . '" />' . "\n";
	}
}
