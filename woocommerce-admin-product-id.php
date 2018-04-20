<?php
/*
  Plugin Name: WooCommerce Admin Product ID
  Plugin URI:
  Description: Display the product ID in the Products list in the WordPress admin.
  Version: 1.0.0
  Author: Barn2 Media
  Author URI: https://barn2.co.uk
  License: GPL-3.0
 */

class WooCommerce_Admin_Product_ID_Plugin {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_filter( 'manage_product_posts_columns', array( $this, 'products_table_add_id_column' ), 20 );
		add_filter( 'manage_product_posts_custom_column', array( $this, 'products_table_display_product_id' ), 10, 2 );
		add_filter( 'manage_edit-product_sortable_columns', array( $this, 'make_id_column_sortable' ) );
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'wcpid-style', plugins_url( 'assets/wc-admin-product-id.css', __FILE__ ) );
	}

	public function products_table_add_id_column( $columns ) {
		return $this->list_table_insert_after_column( $columns, 'name', 'id', __( 'ID' ) );
	}

	public function products_table_display_product_id( $column, $post_id ) {
		if ( 'id' === $column ) {
			if ( $post = get_post( $post_id ) ) {
				echo esc_html( $post->ID );
			}
		}
	}

	public function make_id_column_sortable( $columns ) {
		$custom = array(
			'id' => 'ID'
		);
		return wp_parse_args( $custom, $columns );
	}

	private function list_table_insert_after_column( $columns, $after_key, $insert_key, $insert_value ) {
		$new_columns = array();

		foreach ( $columns as $key => $column ) {
			if ( $after_key === $key ) {
				$new_columns[$key]			 = $column;
				$new_columns[$insert_key]	 = $insert_value;
			} else {
				$new_columns[$key] = $column;
			}
		}

		return $new_columns;
	}

}
new WooCommerce_Admin_Product_ID_Plugin();
