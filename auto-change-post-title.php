<?php
    /**
     * Plugin Name:       Auto change post title
     * Plugin URI:        https://outsourcingvn.com/
     * Description:       Automatic change post title and permalink.
     * Version:           1.0.1
     * Requires at least: 5.2
     * Requires PHP:      7.2
     * Author:            OutsourcingVN
     * Author URI:        https://outsourcingvn.com/contact-us/
     * License:           GPL v2 or later
     * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
     * Text Domain:       auto-change-post-title
     * Domain Path:       /languages
     */

    function acpt_add_settings_page() {
        add_menu_page( 
            'Auto change post title', 
            'Auto Change Post Title', 
            'manage_options', 
            'acpt-example-plugin', 
            'acpt_render_plugin_settings_page',
            'dashicons-admin-customizer',
            5 
        );

        add_option('acpt_error_notice');
    }
    add_action( 'admin_menu', 'acpt_add_settings_page' );

    function acpt_render_plugin_settings_page() {
        ?>
        <form action="options.php" method="post">
            <?php 
                settings_fields( 'acpt_example_plugin_options' );
                do_settings_sections( 'acpt_example_plugin' ); 
            ?>
            <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
        </form>
        <?php
    }

    function acpt_register_settings() {
        $posts = get_posts(array('post_type'=> 'case27_listing_type'));

        register_setting( 'acpt_example_plugin_options', 'acpt_example_plugin_options', 'acpt_example_plugin_options_validate' );
        add_settings_section( 'api_settings', 'Auto Change Post Title Settings', 'acpt_plugin_section_text', 'acpt_example_plugin' );

        add_settings_field( 'acpt_plugin_setting_id_page', 'Update posts from page number', 'acpt_plugin_setting_id_page', 'acpt_example_plugin', 'api_settings' );
        add_settings_field( 'acpt_plugin_setting_post_type', 'Posts type', 'acpt_plugin_setting_post_type', 'acpt_example_plugin', 'api_settings' );
        if(!empty($posts)) {
            add_settings_field( 'acpt_plugin_setting_types', 'Listing types', 'acpt_plugin_setting_types', 'acpt_example_plugin', 'api_settings' );
        }
        add_settings_field( 'acpt_plugin_setting_results_limit', 'Add in front of the title', 'acpt_plugin_setting_results_limit', 'acpt_example_plugin', 'api_settings' );
        add_settings_field( 'acpt_plugin_setting_title_after', 'Added after the title', 'acpt_plugin_setting_title_after', 'acpt_example_plugin', 'api_settings' );
        add_settings_field( 'acpt_plugin_setting_replace', 'Enter the string to Replace or Delete', 'acpt_plugin_setting_replace', 'acpt_example_plugin', 'api_settings' );
        add_settings_field( 'acpt_plugin_setting_alternate', 'Enter the replacement string', 'acpt_plugin_setting_alternate', 'acpt_example_plugin', 'api_settings' );
    }
    add_action('admin_init', 'acpt_register_settings');

    function acpt_plugin_section_text() {
        echo '<p>Here you can set all the options to edit the post title by pages</p>';
    }
    
    function acpt_plugin_setting_id_page() {
        $options = get_option( 'acpt_example_plugin_options' );
        ?>
            <input id="acpt_plugin_setting_id_page" name="acpt_example_plugin_options[id_page]" type="text" value="<?php echo isset($options['id_page']) ? esc_attr( $options['id_page'] ) : ''; ?>" style="width: 18.6rem;" />
        <?php
    }

    function acpt_plugin_setting_post_type() {
        $post_type = get_post_types(array('show_ui' => true),'names');
        unset( $post_type['attachment'] );

        $options = get_option( 'acpt_example_plugin_options' );

        echo "<select id='acpt_plugin_setting_post_type' name='acpt_example_plugin_options[type_posts]' style='width: 18.6rem;'>";
            echo "<option values=''>-- Please select the posts you want --</option>";
            foreach ( $post_type as $post_type ) {
                ?>
                    <option value="<?php echo esc_attr($post_type) ?>" <?php selected(isset($options['type_posts']) ? $options['type_posts'] : '', $post_type); ?>><?php echo esc_html__( $post_type, 'type_posts' ) ?></option>
                <?php 
            }
        echo '</select>'; 
    }

    function acpt_plugin_setting_types() {
        $posts = get_posts(array('post_type'=> 'case27_listing_type'));
        
        $options = get_option( 'acpt_example_plugin_options' );
        if(!empty($posts)) {
            echo "<select id='acpt_plugin_setting_types' name='acpt_example_plugin_options[type]'>";
                echo "<option values=''>-- Please choose one of the items below --</option>";
                foreach ( $posts as $post ) {
                    ?>
                        <option value="<?php echo esc_attr($post->post_name) ?>" <?php selected(isset($options['type']) ? $options['type'] : '', $post->post_name); ?>><?php echo esc_html__( $post->post_title, 'type' ) ?></option>
                    <?php 
                }
            echo '</select>'; 
        }
    }
    
    function acpt_plugin_setting_results_limit() {
        $options = get_option( 'acpt_example_plugin_options' );
        ?>
            <input id="acpt_plugin_setting_results_limit" name="acpt_example_plugin_options[results_limit]" type="text" value="<?php echo isset($options['results_limit']) ? esc_attr( $options['results_limit'] ) : ''; ?>" style="width: 18.6rem;" />
        <?php
    }

    function acpt_plugin_setting_title_after() {
        $options = get_option( 'acpt_example_plugin_options' );
        ?>
            <input id="acpt_plugin_setting_title_after" name="acpt_example_plugin_options[title_after]" type="text" value="<?php echo isset($options['title_after']) ? esc_attr( $options['title_after'] ) : ''; ?>" style="width: 18.6rem;" />
        <?php
    }

    function acpt_plugin_setting_replace() {
        $options = get_option( 'acpt_example_plugin_options' );
        ?>
            <input id="acpt_plugin_setting_replace" name="acpt_example_plugin_options[replace_title]" type="text" value="<?php echo isset($options['replace_title']) ? esc_attr( $options['replace_title'] ) : ''; ?>" style="width: 18.6rem;" />
        <?php
    }

    function acpt_plugin_setting_alternate() {
        $options = get_option( 'acpt_example_plugin_options' );
        ?>
            <input id="acpt_plugin_setting_alternate" name="acpt_example_plugin_options[alternate_title]" type="text" value="<?php echo isset($options['alternate_title']) ? esc_attr( $options['alternate_title'] ) : ''; ?>" style="width: 18.6rem;" />
        <?php
    }

    function acpt_start_with($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack,0,$length) === $needle);
    }

    function acpt_ends_with($haystack, $needle) {
        $length = strlen($needle);
        if($length == 0) {
            return true;
        }

        return (substr($haystack, - $length) === $needle);
    }

    add_filter( 'cron_schedules', 'acpt_settings_plugin_cron' );
    function acpt_settings_plugin_cron( $schedules ) { 
        $schedules['per_minute'] = array(
            'interval' => 60,
            'display'  => esc_html__( 'One Minute' ), );
        return $schedules;
    }

    add_action( 'wp', 'acpt_auto_update' );
    function acpt_auto_update() {
        if ( ! wp_next_scheduled( 'acpt_auto_post_tilte' ) ) {
            wp_schedule_event(time(), 'per_minute', 'acpt_auto_post_tilte');
        }
    }

    add_action('acpt_auto_post_tilte', 'acpt_change_title');
    function acpt_change_title() {
        date_default_timezone_set(get_option('timezone_string'));
        $str_time = date(get_option('time_format')) .' '. date(get_option('date_format'));

        $options = get_option('acpt_example_plugin_options');
        $paged = $options['id_page'];
        $posts_types = $options['type_posts'];
        $listing_type = $options['type'];
        $str_front = $options['results_limit'];
        $str_after = $options['title_after'];
        $str_replace = $options['replace_title'];
        $str_replacement = $options['alternate_title'];
        $listing_type_id = '';

        $total_posts = wp_count_posts($posts_types)->publish;
        $total_page = ceil($total_posts / 20);
        $dem = 0;

        $query = new WP_Query(array('post_type' => $posts_types, 'paged' => $paged, 'posts_per_page' => 20, 'orderby' => 'p', 'order' => 'DESC'));
        if($query->have_posts()) {
            while($query->have_posts()) {
                $query->the_post();
                $id_post = get_the_id();
                $title_post = get_the_title();

                if($posts_types == 'job_listing') {
                    $listing_type_id = get_post_meta($id_post, '_case27_listing_type', true);
                }

                $dem++;

                if($paged <= $total_page && ($listing_type_id === $listing_type || $posts_types != 'job_listing')) {
                    if($str_replace != "" && $str_replacement == "") {
                        $new_title = str_replace($str_replace, ' ', $title_post);
                        $new_link = strtolower(str_replace(' ', '-', $new_title));
                    }

                    if($str_replace != "" && $str_replacement != "") {
                        if(strpos($title_post, $str_replacement) == false){
                            $new_title = str_replace($str_replace, $str_replacement, $title_post);
                            $new_link = strtolower(str_replace(' ', '-', $new_title));
                        } 
                    }

                    if($str_after != "" || $str_front != "") {

                        $front_title = acpt_start_with($title_post, $str_front);
                    
                        $after_title = acpt_ends_with($title_post, $str_after);
                        
                        if($front_title == true && $after_title == false)
                        {
                            if($str_replace != "") {
                                $new_title_replace = str_replace($str_replace, ' ', $title_post);
                                $new_title = $new_title_replace.' '.$str_after;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                            elseif($str_replace != "" && $str_replacement != "") 
                            {
                                $new_title_replace = str_replace($str_replace, $str_replacement, $title_post);
                                $new_title = $new_title_replace.' '.$str_after;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                            else{
                                $new_title = $title_post.' '.$str_after;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                        }
                        elseif($front_title == true && $after_title == true) 
                        {
                            if($str_replace != "") {
                                $new_title_replace = str_replace($str_replace, ' ', $title_post);
                                $new_title = $new_title_replace;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                            elseif($str_replace != "" && $str_replacement != "") 
                            {
                                $new_title_replace = str_replace($str_replace, $str_replacement, $title_post);
                                $new_title = $new_title_replace;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                            else
                            {
                                $new_title = $title_post;
                                $new_link = strtolower(str_replace(' ','-',$title_post));
                            }
                        }
                        elseif($front_title == false && $after_title == true) 
                        {
                            if($str_replace != "") {
                                $new_title_replace = str_replace($str_replace, ' ', $title_post);
                                $new_title = $str_front.' '.$new_title_replace;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                            elseif($str_replace != "" && $str_replacement != "") 
                            {
                                $new_title_replace = str_replace($str_replace, $str_replacement, $title_post);
                                $new_title = $new_title = $str_front.' '.$new_title_replace;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                            else
                            {
                                $new_title = $str_front.' '.$title_post;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                        }
                        elseif($after_title == false && $front_title == false) 
                        {
                            if($str_replace != "") {
                                $new_title_replace = str_replace($str_replace, ' ', $title_post);
                                $new_title = $str_front.' '.$new_title_replace.' '.$str_after;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                            elseif($str_replace != "" && $str_replacement != "") 
                            {
                                $new_title_replace = str_replace($str_replace, $str_replacement, $title_post);
                                $new_title = $str_front.' '.$new_title_replace.' '.$str_after;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                            else
                            {
                                $new_title = $str_front.' '.$title_post.' '.$str_after;
                                $new_link = strtolower(str_replace(' ','-',$new_title));
                            }
                        }
                    }

                    $result[] = wp_update_post(array(
                        'ID'            => $id_post,
                        'post_title'    => $new_title,
                        'post_name'     => $new_link,
                        'guid'          => $new_link,
                    ));
                }
            }
        }
        else{
            $message = __('There are no posts requested !!!');
            update_option('acpt_error_notice', $message);
            exit();
        }

        if($result > 0) {
            $message = 'Successfully update page '.$paged.' with the number of posts '.count($result).' / '.$dem.' at '.$str_time;
            update_option('acpt_error_notice', $message);
        }
        else {
            $message = __('The update data is already in the log. Please enter other data !!!');
            update_option('acpt_error_notice', $message);
        }

        if($paged <= $total_page && $paged != "") {
            $options['id_page'] = $paged + 1;
            update_option('acpt_example_plugin_options', $options);
        }
        else{
            $message = __('Update completed at '.$str_time);
            update_option('acpt_error_notice', $message);
        }
    }

    function acpt_show_notices() {
        $options = get_option('acpt_example_plugin_options', null);
        if(isset($options) && !empty($options['id_page'])) {     
            $paged = !empty($options['id_page']) ? $options['id_page'] : ''; 
            $listing_type = !empty($options['type']) ? $options['type'] : '';
            $str_front = !empty($options['results_limit']) ? $options['results_limit'] : '';
            $str_after = !empty($options['title_after']) ? $options['title_after'] : '';
            $str_replace = !empty($options['replace_title']) ? $options['replace_title'] : '';
            $str_replacement = !empty($options['alternate_title']) ? $options['alternate_title'] : '';

            if($paged != "" || $listing_type != "" || $str_front != "" || $str_after != "" || $str_replace != "" || $str_replacement != "") {
                $acpt_notices = get_option('acpt_error_notice');
                if(!empty($acpt_notices))
                {
                    $class = 'notice notice-warning is-dismissible';
                    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $acpt_notices ));
                }
            }
        }
    }
    add_action('admin_notices', 'acpt_show_notices');

