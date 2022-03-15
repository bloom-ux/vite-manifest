# ViteManifest

Get js and css dependencies from a [vitejs](https://vitejs.dev/) manifest file

## Usage

```php
$manifest = new Bloom_UX\ViteManifest( $manifest_file_full_path );
$manifest->setBaseUrl( 'https://domain.tld/assets/dist' );

$dependencies = $manifest->getEntryDeps( 'main.js' );

foreach ( $dependencies as $dep ) {
	if ( $dep->isEntry() ) {
		// load as javascript module
	} else if ( $dep instanceof JsDependency ) {
		// load as js
	} else {
		// loas as css
	}
}
```
