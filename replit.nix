{ pkgs }: {
	deps = [
    (pkgs.php.buildEnv {
    	extraConfig = "
      error_reporting=On
      upload_max_filesize=16M
      post_max_size=16M
      zend_extension=${pkgs.phpExtensions.xdebug}/lib/php/extensions/xdebug.so
      xdebug.var_display_max_depth=10
      ;xdebug.mode=profile
      ;xdebug.output_dir= ./debug/
      ";
    })
    pkgs.phpExtensions.curl
    pkgs.phpExtensions.mbstring
    pkgs.phpExtensions.pdo
    pkgs.phpExtensions.opcache
    pkgs.phpExtensions.imagick
    pkgs.phpExtensions.mysqli
    pkgs.phpExtensions.xdebug
    pkgs.phpPackages.composer
    pkgs.mysql80
	];
}