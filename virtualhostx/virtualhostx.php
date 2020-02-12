<?PHP
/**
 * Plugin Name: VirtualHostX Shared Websites
 * Plugin URI: https://clickontyler.com/virtualhostx/wordpress/
 * Description: Allow WordPress to work with other domain names than the one it was initially installed on.
 * Version: 1.0
 * Author: Tyler Hall
 * Author URI: https://tyler.io
 */

VirtualHostX::activate();

class VirtualHostX
{
    public static $domain;

    public static function activate()
    {
        VirtualHostX::detectDomain();

        add_filter('content_url', [self::class, 'fixDomain']);
        add_filter('option_siteurl', [self::class, 'fixDomain']);
        add_filter('option_home', [self::class, 'fixDomain']);
        add_filter('plugins_url', [self::class, 'fixDomain']);
        add_filter('wp_get_attachment_url', [self::class, 'fixDomain']);
        add_filter('get_the_guid', [self::class, 'fixDomain']);
        add_filter('upload_dir', [self::class, 'fixUploadsDomain']);
        add_filter('allowed_http_origins', [self::class, 'insertAllowedOrigin']);
    }

    public static function detectDomain()
    {
        if(isset($_SERVER['HTTP_X_ORIGINAL_HOST'])) {
            $proto = empty($_SERVER['HTTP_X_FORWARDED_PROTO']) ? 'http' : (($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 'https' : 'http');
            $url = $proto . '://' . $_SERVER['HTTP_X_ORIGINAL_HOST'];
            if($proto == 'https') { $_SERVER['HTTPS'] = 'on'; }
        } else {
            $s = empty($_SERVER['HTTPS']) ? '' : ($_SERVER['HTTPS'] == 'on') ? 's' : '';
            $port = (($_SERVER['SERVER_PORT'] == '80') || ($_SERVER['SERVER_PORT'] == '443')) ? '' : (":".$_SERVER['SERVER_PORT']);
            $url = "http$s://" . $_SERVER['HTTP_HOST'] . $port;
        }
        self::$domain = $url;
    }

    public static function fixDomain($url)
    {
        preg_match_all("/\//", utf8_decode($url), $matches, PREG_OFFSET_CAPTURE);
        $pos = $matches[0][2][1];

        if(empty($pos)) {
            return self::$domain;
        }

        return self::$domain . substr($url, $pos);
    }

    public static function fixUploadsDomain($uploads)
    {
        $uploads['url'] = VirtualHostX::fixDomain($uploads['url']);
        $uploads['baseurl'] = VirtualHostX::fixDomain($uploads['baseurl']);
        return $uploads;
    }

    public static function insertAllowedOrigin($origins)
    {
        $origins[] = self::$domain;
        return $origins;
    }
}
