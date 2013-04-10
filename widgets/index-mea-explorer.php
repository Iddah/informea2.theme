<?php
class MEAExplorerWidget extends WP_Widget {

    function MEAExplorerWidget() {
        $options = array(
            'classname' => 'MEAExplorerWidget',
            'description' => 'MEA Explorer index page widget',
        );
        $this->WP_Widget('MEAExplorerWidget', 'MEA Explorer', $options);
    }


    function form($instance) {
        $instance = wp_parse_args((array)$instance, array());
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        return $instance;
    }

    function widget($args, $instance) {
        $search2 = new InformeaSearch2($_GET);
        ?>
        <div class="index-explorer">
            <form action="/search" method="get">
                <input type="hidden" name="q_tab" value="2"/>

                <div class="title">
                    <img src="<?php bloginfo('template_directory'); ?>/images/btn-explorer-transp.png" />
                </div>
                <div class="content">
                    <label for="q_freetext">Search</label>
                    <input type="text" id="q_freetext" name="q_freetext" class="freetext" size="40" />
                    <a class="button orange" href="javascript:$('#search_index').submit();">
                        <span>Search</span>
                    </a>
                    <a class="btn-advanced-search" href="javascript:void(0);">
                        <span>Advanced</span>
                    </a>
                    <ul class="advanced hidden">
                        <li class="section">
                            <p>Topics</p>
                            <div class="filters">
                                <ul>
                                    <li>
                                        <input id="index_treaty_biodiversity" type="checkbox" checked="checked" />
                                        <label for="index_treaty_biodiversity">Biodiversity</label>
                                    </li>
                                    <li>
                                        <input id="index_treaty_chemicals" type="checkbox" checked="checked" />
                                        <label for="index_treaty_chemicals">Chemicals / Waste</label>
                                    </li>
                                    <li>
                                        <input id="index_treaty_climate" type="checkbox" checked="checked" />
                                        <label for="index_treaty_climate">Climate / Ozone / Deserts</label>
                                    </li>
                                    <li>
                                        <input id="index_treaty_other" type="checkbox" checked="checked" />
                                        <label for="index_treaty_other">Other (Regional, etc)</label>
                                    </li>
                                </ul>
                                <?php
                                $ts = $search2->ui_get_treaties_ids();
                                foreach ($ts as $t_id) {
                                    ?>
                                    <input type="checkbox" id="q_treaty_index_<?php echo $t_id; ?>" name="q_treaty[]"
                                           value="<?php echo $t_id; ?>" checked="checked" class="hidden" />
                                <?php
                                }
                                ?>
                            </div>
                        </li>
                        <li class="section">
                            <p>Search in</p>
                            <div class="filters">
                                <ul>
                                    <li>
                                        <input type="checkbox" id="index_q_use_decisions" name="q_use_decisions" checked="checked" value="1"/>
                                        <label for="index_q_use_decisions">Decisions &amp; Resolutions</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="index_q_use_treaties" name="q_use_treaties" checked="checked" value="1"/>
                                        <label for="index_q_use_treaties">Treaties</label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="index_q_use_meetings" name="q_use_meetings" value="1" class="checkbox" checked="checked"/>
                                        <label for="index_q_use_meetings" class="check-label label click-filters-use-events">Meetings</label>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="section">
                            <p>Keywords</p>
                            <div class="filters">
                                <select id="q_term_index" name="q_term[]" multiple="multiple" class="hidden">
                                    <?php
                                    $terms_page = new Thesaurus(NULL);
                                    $terms = $terms_page->suggest_vocabulary_terms();
                                    $sterms = $search2->get_terms();
                                    foreach ($terms as $term) {
                                        $search2->ui_write_option($term->id, $term->term, in_array(intval($term->id), $sterms));
                                    }
                                    ?>
                                </select>
                                <div id="index_and_or_radiobuttons" class="hidden">
                                    <input type="radio" id="q_term_and_index" name="q_term_or" value="and" checked="checked">
                                    <label for="q_term_and_index">AND</label>

                                    <input type="radio" id="q_term_or_index" name="q_term_or" value="or">
                                    <label for="q_term_or_index">OR</label>
                                </div>
                                <div id="index-search-terms"></div>
                                <div class="clear"></div>
                            </div>
                        </li>
                        <li class="section">
                            <p>Time filter</p>
                            <div class="filters">
                                <?php include(dirname(__FILE__) . '/../imea_pages/explorer/inc.date_interval.php'); ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </form>
        </div>
        <div class="margin-bottom-10"></div>
    <?php
    }
}

add_action('widgets_init', create_function('', 'return register_widget("MEAExplorerWidget");'));?>