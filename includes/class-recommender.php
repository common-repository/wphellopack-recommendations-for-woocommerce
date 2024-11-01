<?php

namespace WPHelloPack;

/**
 * Recommened products handling class
 *
 *
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Recommender class.
 */
class Recommender {

    /**
     * WordPress database access abstraction class.
     *
     * @var Object
     */
    public $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    /**
     * Fetch recommendations for the given category id
     * @param int $categoryId
     * @return array
     */
    public function get_recommended_products_by_100_strategy( array $categoryIds ) : array {
        return $this->wpdb->get_results( $this->wpdb->prepare( "
                SELECT P.id as ID FROM %1s AS P JOIN %1s AS TR
                ON P.id = TR.object_id AND P.post_type = 'product' AND P.post_status = 'publish'
                WHERE TR.term_taxonomy_id = %d LIMIT 12
            ", [ $this->wpdb->prefix . 'posts',
                 $this->wpdb->prefix . 'term_relationships', 
                 $categoryIds[0] 
               ] 
        ) );
    }

    /**
     * Fetch recommendations for the given category id
     * @param int $categoryId
     * @return array
     */
    public function get_recommended_products_by_5050_strategy( array $categoryIds ) : array {
        return $this->wpdb->get_results( $this->wpdb->prepare( "
                (SELECT P.id as ID FROM %1s AS P JOIN %1s TR
                ON P.id = TR.object_id AND P.post_type = 'product' AND P.post_status = 'publish'
                WHERE TR.term_taxonomy_id = %d LIMIT 6)
                UNION
                (SELECT P.id as ID FROM %1s AS P JOIN %1s TR
                ON P.id = TR.object_id AND P.post_type = 'product' AND P.post_status = 'publish'
                WHERE TR.term_taxonomy_id = %d LIMIT 6)
            ", [ 
                $this->wpdb->prefix . 'posts', 
                $this->wpdb->prefix . 'term_relationships', 
                $categoryIds[ 0 ], 
                $this->wpdb->prefix . 'posts', 
                $this->wpdb->prefix . 'term_relationships', 
                $categoryIds[ 1 ] 
              ] 
        ) );
    }
    
    /**
     * Fetch recommendations for the given category id
     * @param int $categoryId
     * @return array
     */
    public function get_recommended_products_by_661717_strategy( array $categoryIds ) : array {
        return $this->wpdb->get_results( $this->wpdb->prepare( '
                (SELECT P.id as ID FROM %1s AS P JOIN %1s TR
                ON P.id = TR.object_id AND P.post_type = "product" AND P.post_status = "publish"
                WHERE TR.term_taxonomy_id = %d LIMIT 6)
                UNION
                (SELECT P.id as ID FROM %1s AS P JOIN %1s TR
                ON P.id = TR.object_id AND P.post_type = "product" AND P.post_status = "publish"
                WHERE TR.term_taxonomy_id = %d LIMIT 4)
                UNION
                (SELECT P.id as ID FROM %1s AS P JOIN %1s TR
                ON P.id = TR.object_id AND P.post_type = "product" AND P.post_status = "publish"
                WHERE TR.term_taxonomy_id = %d LIMIT 2)
            ', [ $this->wpdb->prefix . 'posts',
                 $this->wpdb->prefix . 'term_relationships', 
                 $categoryIds[ 0 ], 
                 $this->wpdb->prefix . 'posts', 
                 $this->wpdb->prefix . 'term_relationships', 
                 $categoryIds[ 1 ], 
                 $this->wpdb->prefix . 'posts', 
                 $this->wpdb->prefix . 'term_relationships', 
                 $categoryIds[ 2 ] 
            ] 
        ) );
    }    

}
