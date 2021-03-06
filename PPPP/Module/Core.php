<?php

/**
 *
 * Core Action class.
 *
 * @package PPPP
 * @since 0.7
 *
 */

Class PPPP_Module_Core extends PPPP_Module {

	public function add_hook() {
		add_action( "pre_get_posts", array($this, "pre_get_posts"));
	}

	public function pre_get_posts( $query ) {
		if($query->is_main_query() and !is_admin()) {
			$posts_per_page = get_option( "posts_per_page" );

			if($query->is_category()) {
				$query->set("posts_per_page", get_option( "posts_per_page_of_tax_category", $posts_per_page));
				return;
			}

			if($query->is_tag()) {
				$query->set("posts_per_page", get_option( "posts_per_page_of_tax_post_tag", $posts_per_page));
				return;
			}

			foreach (PPPP_Util::get_taxonomies() as $taxonomy) {
				if($query->is_tax( $taxonomy->name )) {
					$query->set("posts_per_page", get_option( "posts_per_page_of_tax_".$taxonomy->name, $posts_per_page));
					return;
				}
			}

			foreach (PPPP_Util::get_post_types() as $post_type) {
				if($query->is_post_type_archive( $post_type->name )) {
					$query->set("posts_per_page", get_option( "posts_per_page_of_cpt_".$post_type->name, $posts_per_page));
					return;
				}
			}


		}
	}
}
