<?php
/**
 * Plugin Name: Mihdan: Can I Use
 * Plugin URI: https://www.kobzarev.com/
 * Description: Add Can I Use support tables to your WordPress web site thanks to this shortcode
 * Version: 1.0
 * Author: Mikhail Kobzarev
 * Author URI: https://www.kobzarev.com/
 * License: GPL
 *
 * @link https://github.com/andismith/caniuse-widget
 * @link https://github.com/Keyamoon/IcoMoon-Free/tree/master/SVG
 * @link http://chikuyonok.ru/2009/04/dl-tabs/
 *
 * @package mihdan-caniuse
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Mihdan_Caniuse' ) ) :


	final class Mihdan_Caniuse {

		/**
		 * Откуда брать данные
		 */
		const API = 'https://github.com/Fyrd/caniuse/raw/master/features-json/';

		/**
		 * Слаг плагина
		 */
		const SLUG = 'mihdan-caniuse';

		/**
		 * Путь к плагину
		 *
		 * @var string
		 */
		public static $dir_path;

		/**
		 * URL до плагина
		 *
		 * @var string
		 */
		public static $dir_uri;

		/**
		 * Хранит экземпляр класса
		 *
		 * @var null
		 */
		protected static $_instance = null;

		/**
		 * Защищиаемся от создания через new
		 *
		 * Mihdan_Caniuse constructor.
		 */
		private function __construct() {
			$this->hooks();
			$this->setup();
		}

		/**
		 * Защищаем от создания через клонирование
		 */
		private function __clone() {}

		/**
		 * Защищаем от создания через unserialize
		 */
		private function __wakeup() {}

		/**
		 * Получить экземпляр класса
		 *
		 * @return Mihdan_Caniuse
		 */
		public static function get_instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Setup Variables
		 */
		private function setup() {
			self::$dir_path = trailingslashit( plugin_dir_path( __FILE__ ) );
			self::$dir_uri  = trailingslashit( plugin_dir_url( __FILE__ ) );
		}

		/**
		 * Инициализация хуков
		 */
		private function hooks() {
			add_shortcode( 'caniuse', array( $this, 'shortcode' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'init', array( $this, 'init' ) );
		}

		public function init() {
			add_filter( 'mce_external_plugins', array( $this, 'wptuts_add_buttons' ) );
			add_filter( 'mce_buttons', array( $this, 'wptuts_register_buttons' ) );
		}

		public function wptuts_add_buttons( $plugin_array ) {
			$plugin_array['caniuse_plugin'] = self::$dir_uri . 'admin/tinymce/tinymce.js';
			return $plugin_array;
		}
		public function wptuts_register_buttons( $buttons ) {
			$buttons[] = 'caniuse_button';
			//array_push( $buttons, 'caniuse' );
			//var_dump($buttons);
			return $buttons;
		}

		public function enqueue_scripts() {
			//wp_register_style( self::SLUG, self::$dir_uri . 'assets/css/caniuse.css', array(), null );
			wp_register_script( self::SLUG, '//cdn.jsdelivr.net/gh/ireade/caniuse-embed/caniuse-embed.min.js', array(), null, true );
			//wp_register_script( self::SLUG, self::$dir_uri . 'assets/js/caniuse.js', array(), null, true );
		}

		public function shortcode( $atts ) {
			$atts = shortcode_atts( array(
				'feature' => 'audio',
			), $atts, 'caniuse' );



			//if ( ! empty( $_GET['preview']) ) {

				wp_enqueue_script( self::SLUG );
				wp_enqueue_style( self::SLUG );

				ob_start(); ?>

				<div class="mihdan-caniuse" data-feature="<?php echo esc_attr( $atts['feature'] ); ?>"></div>
				<p class="ciu_embed" data-feature="<?php echo esc_attr( $atts['feature'] ); ?>" data-periods="future_1,current,past_1,past_2" data-accessible-colours="false"></p>

				<?php

				$tpl = ob_get_contents();
				ob_end_clean();

				return $tpl;
			//}


		}
	}

	function Mihdan_Caniuse() {
		return Mihdan_Caniuse::get_instance();
	}

	$GLOBALS['mihdan_caniuse'] = Mihdan_Caniuse();

endif;