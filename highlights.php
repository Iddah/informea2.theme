<?php
/**
 * This class is the data provider for the 'Countries' section of the site.
 * @package InforMEA
 * @subpackage Theme
 * @since 1.0
 */

if (!class_exists('imea_highlights_page')) {
    class imea_highlights_page extends imea_page_base_page {

        public $categories;
        public $non_empty_categories;
        public $feed_mea; // Links between Feed and MEA (name)
        public $feeds; // Feeds by id
        public $mea_categories;
        public $mea_categories_slugs;
        private $treaties;

        function __construct() {
            global $post;
            $old_post = $post;
            $this->categories = array();

            $ob = new StdClass();
            $ob->title = "Climate &amp; Atmosphere";
            $ob->slug = "climate-change";
            $ob->link = get_bloginfo('url') . '/highlights/' . $ob->slug;
            $ob->image = get_bloginfo('template_directory') . '/images/spotlight/climate-change.jpg';
            $ob->treaties = '6,7,15';
            $this->categories[$ob->slug] = $ob;

            $ob = new StdClass();
            $ob->title = "Biological Diversity";
            $ob->slug = "biological-diversity";
            $ob->link = get_bloginfo('url') . '/highlights/' . $ob->slug;
            $ob->image = get_bloginfo('template_directory') . '/images/spotlight/other-biodiversity.jpg';
            $ob->treaties = '1,6,9,3,4,10,16,18,14';
            $this->categories[$ob->slug] = $ob;

            $ob = new StdClass();
            $ob->title = "Species";
            $ob->slug = "species";
            $ob->link = get_bloginfo('url') . '/highlights/' . $ob->slug;
            $ob->image = get_bloginfo('template_directory') . '/images/spotlight/species.jpg';
            $ob->treaties = '3,4,14,10';
            $this->categories[$ob->slug] = $ob;

            $ob = new StdClass();
            $ob->title = "Wetlands &amp; National Heritage Sites";
            $ob->slug = "wetlands-national-heritage-sites";
            $ob->link = get_bloginfo('url') . '/highlights/' . $ob->slug;
            $ob->image = get_bloginfo('template_directory') . '/images/spotlight/wetlands.jpg';
            $ob->treaties = '16,18';
            $this->categories[$ob->slug] = $ob;

            $ob = new StdClass();
            $ob->title = "Chemicals &amp; Waste";
            $ob->slug = "chemicals-waste";
            $ob->link = get_bloginfo('url') . '/highlights/' . $ob->slug;
            $ob->image = get_bloginfo('template_directory') . '/images/spotlight/biohazard.png';
            $ob->treaties = '2,20,5';
            $this->categories[$ob->slug] = $ob;

            $ob = new StdClass();
            $ob->title = "International Cooperation";
            $ob->slug = "international-cooperation";
            $ob->link = get_bloginfo('url') . '/highlights/' . $ob->slug;
            $ob->image = get_bloginfo('template_directory') . '/images/spotlight/cooperation.jpg';
            $ob->treaties = false;
            $this->categories[$ob->slug] = $ob;

            $ob = new StdClass();
            $ob->title = "Financing &amp; Trade";
            $ob->slug = "financing-trade";
            $ob->link = get_bloginfo('url') . '/highlights/' . $ob->slug;
            $ob->image = get_bloginfo('template_directory') . '/images/spotlight/trade.png';
            $ob->treaties = '3,20,14,8,2';
            $this->categories[$ob->slug] = $ob;

            $ob = new StdClass();
            $ob->title = "Drylands";
            $ob->slug = "drylands";
            $ob->link = get_bloginfo('url') . '/highlights/' . $ob->slug;
            $ob->image = get_bloginfo('template_directory') . '/images/spotlight/desertification.jpg';
            $ob->treaties = '19';
            $this->categories[$ob->slug] = $ob;

            // Store treaties, to show treaty logo whenever we don't have a picture for a post in the highlights section,
            // but that post has a treaty attached to it, as category. Try to match category with treaty name
            $tmp = new imea_treaties_page(NULL);
            $treaties = $tmp->get_treaties();
            foreach ($treaties as $treaty) {
                $this->treaties[slugify($treaty->short_title)] = $treaty;
            }

            $this->non_empty_categories = array();
            foreach ($this->categories as $cat) {
                $news = $this->search(NULL, $cat->slug);
                if (count($news->posts)) {
                    $this->non_empty_categories[] = $cat;
                }
            }

            $this->feed_mea = array();
            $this->feeds = array();
            $this->mea_categories = array();
            $this->mea_categories_slugs = array();
            $cat_meas = get_category_by_slug('meas');
            // Get all MEA categories
            if ($cat_meas) {
                $cat_meas = $cat_meas->cat_ID;
                $cat_meas = get_categories(array('hide_empty' => 0, 'name' => 'category_parent', 'parent' => $cat_meas));
                foreach ($cat_meas as $cat_mea) {
                    $this->mea_categories[$cat_mea->cat_ID] = $cat_mea->name;
                    $this->mea_categories_slugs[$cat_mea->cat_ID] = $cat_mea->slug;
                }
            }
            $feedwordpress = new FeedWordPress();
            foreach ($feedwordpress->syndicated_links() as $link) {
                $syndicated_link = new SyndicatedLink($link);
                if (!isset($syndicated_link->settings['cats'])) {
                    continue; // This feed does not have any category set (wrongly configured)
                }
                $this->feeds[$syndicated_link->id] = $syndicated_link;
                $link_categories = $syndicated_link->settings['cats'];
                foreach ($link_categories as $cat_str) {
                    $cat_id = explode('#', $cat_str);
                    $cat_id = explode('}', $cat_id[1]);
                    if (isset($this->mea_categories[$cat_id[0]])) {
                        $this->feed_mea[$syndicated_link->id] = $this->mea_categories[$cat_id[0]];
                    }
                }
            }
            // Restore the post, as it's changed by this function
            $post = $old_post;
        }


        function get_meas_subcategories() {
            $cat_meas = get_category_by_slug('meas');
            $ret = array();
            // Get all MEA categories
            if ($cat_meas) {
                $cat_meas = $cat_meas->cat_ID;
                $cat_meas = get_categories(array('hide_empty' => 0, 'name' => 'category_parent', 'parent' => $cat_meas));
                foreach ($cat_meas as $cat_mea) {
                    $ret[$cat_mea->cat_ID] = $cat_mea->name;
                }
            }
            return $ret;
        }


        function get_category_by_slug($slug) {
            if (isset($this->categories[$slug])) {
                return $this->categories[$slug];
            }
            return null;
        }


        function get_syndication_subcategories() {
            $cat_meas = get_category_by_slug('syndication');
            $ret = array();
            // Get all MEA categories
            if ($cat_meas) {
                $cat_meas = $cat_meas->cat_ID;
                $cat_meas = get_categories(array('hide_empty' => 0, 'name' => 'category_parent', 'parent' => $cat_meas));
                foreach ($cat_meas as $cat_mea) {
                    $ret[$cat_mea->cat_ID] = $cat_mea->name;
                }
            }
            return $ret;
        }


        /**
         * @param $query
         * @param null $category
         * @param int $page
         * @param int $limit
         * @return stdClass with property 'posts' containing the posts and max_num_pages total pages
         */
        function search($query, $category = NULL, $page = 0, $limit = 10) {
            global $post;
            $old_post = $post;
            $ret = new StdClass();
            $ret->posts = array();
            $args = array('post_date' => 'DATE(NOW())',
                'post_type' => 'post',
                'orderby' => 'post_date',
                'paged' => $page,
                'order' => 'DESC');
            if(!empty($category)) {
                $args['category_name'] = $category;
            }
            if(!empty($query)) {
                $args['s'] = $query;
            }
            $args['posts_per_page'] = $limit;
            $wpq = new WP_Query($args);
            $ret->max_num_pages = $wpq->max_num_pages;
            while ($wpq->have_posts()) {
                $wpq->the_post();
                $ob = new StdClass();
                $ob->id = get_the_ID();
                $ob->permalink = get_permalink();
                $ob->title = get_the_title();
                $ob->has_image = FALSE;
                $ob->time = strtotime($post->post_date);
                $ob->date = $post->post_date;
                $ob->date_formatted = format_mysql_date($post->post_date, 'j M Y');
                $ob->summary = $post->post_excerpt;
                $ob->content = $post->post_content;
                $ob->source = NULL;
                $custom_fields = get_post_custom();
                $feed_id = $custom_fields['syndication_feed_id'];
                $feed_id = $feed_id[0];
                $ob->source = $this->get_post_source($feed_id, $post->ID);
                $feed = null;
                if (isset($this->feeds[$feed_id])) {
                    $feed = $this->feeds[$feed_id];
                }
                $ob->image = $this->get_post_image($feed, $post);
                $ret->posts[] = $ob;
            }
            $post = $old_post;
            return $ret;
        }


        function get_index_news($limit = 2) {
            $ret = array();
            foreach ($this->mea_categories_slugs as $mea_category) {
                $ob = new StdClass();
                $ob->slug = $mea_category;
                $ob = $this->search(NULL, $mea_category, $limit);
                if (!empty($ob->posts)) {
                    foreach ($ob->posts as $p) {
                        if (!array_key_exists($p->id, $ret)) {
                            $ret[$p->id] = $p;
                        }
                    }
                }
            }
            // Sort posts by their date
            usort($ret, function ($a, $b) {
                if ($a->time == $b->time) {
                    return 0;
                }
                return ($a->time < $b->time) ? 1 : -1;
            });
            return $ret;
        }


        function get_post_source($feed_id, $post_id) {
            $ret = new stdClass();

            $post_categories = wp_get_post_categories($post_id);
            foreach ($post_categories as $post_category) {
                if (is_array($this->mea_categories) && array_key_exists($post_category, $this->mea_categories)) {
                    $ret = $this->mea_categories[$post_category];
                    break;
                }
            }

            if (empty($ret) && !empty($feed_id)) {
                if (isset($this->feed_mea[$feed_id])) {
                    $ret = $this->feed_mea[$feed_id];
                }
                // If we have no source, set source to the feed title
                $feed = new SyndicatedLink($feed_id);
                if (isset($feed->settings['link/name'])) {
                    $ret = $feed->settings['link/name'];
                } else {
                    if (isset($feed->settings['link/name'])) {
                        $ret = $feed->settings['feed/title'];
                    }
                }
            }

            // Last fallback for source
            if (empty($ret)) {
                $ret = 'Unknown';
            }
            return $ret;
        }


        function get_post_image($syndicated_link, $post) {
            global $post;
            // Try to get image from the post itself
            $tid = get_post_thumbnail_id($post->ID);
            if (!empty($tid)) {
                $arr = wp_get_attachment_image_src($tid, 'thumbnail', TRUE);
                if (!empty($arr)) {
                    return $arr[0];
                }
            }

            // Fetch post's feature image, if exists
            $custom_fields = get_post_custom();
            if (array_key_exists('featured_img_url', $custom_fields)) {
                if (is_array($custom_fields['featured_img_url'])) {
                    if (count($custom_fields['featured_img_url'])) {
                        shuffle($custom_fields['featured_img_url']);
                        return $custom_fields['featured_img_url'][0];
                    }
                } else {
                    return $custom_fields['featured_img_url'];
                }
            }

            // Try to get image from the feed itself
            if (!empty($syndicated_link->settings['link_image'])) {
                return $syndicated_link->settings['link_image'];
            }

            // Try to get it from the treaty (if attached to a treaty through 'categories')
            $post_categories = wp_get_post_categories($post->ID);
            foreach ($post_categories as $post_category) {
                if (is_array($this->mea_categories) && array_key_exists($post_category, $this->mea_categories)) {
                    $post_slug = slugify($this->mea_categories[$post_category]);
                    if (array_key_exists($post_slug, $this->treaties)) {
                        $treaty = $this->treaties[$post_slug];
                        return $treaty->logo_medium;
                    }
                }
            }

            // Put the default empty image
            return get_bloginfo('template_directory') . '/images/organization/nologo.png';
        }

        public static function get_post_categories($post) {
            $ret = array();
            $cat_syn = get_category_by_slug('syndication');
            $cat_meas = get_category_by_slug('meas');
            foreach (wp_get_post_categories($post->id) as $cat_id) {
                if(!in_array($cat_id, array($cat_syn->cat_ID, $cat_meas->cat_ID))) {
                    $ret[] = get_category($cat_id);
                }
            }
            return $ret;
        }

        static function get_rss_posts_filter_where($where='') {
            return $where . ' AND post_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH) ';
        }

        function get_rss_posts() {
            global $post;
            $ret = array();
            $args = array('post_date' => 'DATE(NOW())',
                'post_type' => 'post',
                'orderby' => 'post_date',
                'posts_per_page' => -1,
                'order' => 'DESC');

            add_filter('posts_where', array('imea_highlights_page', 'get_rss_posts_filter_where'));
            $wpq = new WP_Query($args);
            remove_filter('posts_where', array('imea_highlights_page', 'get_rss_posts_filter_where'));

            while ($wpq->have_posts()) {
                $wpq->the_post();
                $ob = new StdClass();
                $ob->id = get_the_ID();
                $ob->permalink = get_permalink();
                $ob->title = get_the_title();
                $ob->has_image = FALSE;
                $ob->time = strtotime($post->post_date);
                $ob->date = $post->post_date;
                $ob->date_formatted = format_mysql_date($post->post_date, 'j M Y');
                $ob->summary = $post->post_excerpt;
                $ob->content = $post->post_content;
                $ob->source = NULL; // Default
                $ob->categories = array();
                $custom_fields = get_post_custom();
                $post_categories = wp_get_post_categories($ob->id);
                foreach ($post_categories as $cat_id) {
                    $cat = get_category($cat_id);
                    if(!empty($cat->name)) {
                        $ob->categories[] = $cat->name;
                    }
                }

                $feed_id = null;
                if (isset($custom_fields['syndication_feed_id'])) {
                    $feed_id = $custom_fields['syndication_feed_id'];
                    $feed_id = $feed_id[0];
                }
                $ob->source = $this->get_post_source($feed_id, $post->ID);
                if (!empty($feed_id) && isset($this->feeds[$feed_id])) {
                    $ob->image = $this->get_post_image($this->feeds[$feed_id], $post);
                } else {
                    $ob->image = $this->get_post_image(null, $post);
                }
                $ob->has_image = !empty($ob->image);
                $ret[] = $ob;
            }
            return $ret;
        }


        function validate_add() {
            $this->actioned = TRUE;
            if (check_admin_referer('informea-admin_highlight_add_highlight')) {
                $val = new FormValidator();
                $val->addValidation("title", "req", "Please fill in the title");
                $val->addValidation("link", "req", "Please fill in the link");
                $valid = $val->ValidateForm();
                if (!$valid) {
                    $this->errors = $val->GetErrors();
                }

                $req_cat_syndication = get_request_value('cat_syndication', array(), FALSE);
                if (empty($req_cat_syndication)) {
                    $this->errors['cat_syndication'] = 'At least one topic must be selected';
                    $valid = FALSE;
                }
                $req_cat_mea = get_request_value('cat_mea', array(), FALSE);
                if (empty($req_cat_mea)) {
                    $this->errors['cat_mea'] = 'At least one MEA must be selected';
                    $valid = FALSE;
                }
                return $valid;
            }
            return FALSE;
        }


        function add() {
            $this->actioned = TRUE;
            global $user_ID;

            $title = get_request_value('title');
            $cat_syndication = get_request_value('cat_syndication', array(), FALSE);
            $cat_mea = get_request_value('cat_mea', array(), FALSE);
            $link = get_request_value('link');

            $post = array();
            $post['comment_status'] = 'closed';
            $post['ping_status'] = 'closed';
            $post['post_author'] = $user_ID;
            $post['post_date'] = date('Y-m-d H:i:s');
            $post['post_name'] = slugify($title);
            $post['post_parent'] = 0;
            $post['post_status'] = 'publish';
            $post['post_title'] = $title;
            $post['post_type'] = 'post';
            $post['post_content'] = sprintf('Read more <a href="%s">here</a>.', $link);

            $id = wp_insert_post($post, TRUE);
            if (is_int($id) && $id > 0) {
                wp_set_post_terms($id, array_merge($cat_syndication, $cat_mea), 'category');
                add_post_meta($id, 'syndication_permalink', $link);
                $this->success = TRUE;
            } else {
            }
        }
    }
}
?>
