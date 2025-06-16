<?php
/**
 * Plugin Name: MySpace Top Friends
 * Plugin URI: https://example.com
 * Description: A nostalgic MySpace-style friends display widget with configurable settings
 * Version: 1.0.0
 * Author: Damon Noisette
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MYSPACE_FRIENDS_VERSION', '1.0.0');
define('MYSPACE_FRIENDS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MYSPACE_FRIENDS_PLUGIN_URL', plugin_dir_url(__FILE__));

class MySpaceTopFriends {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        add_shortcode('myspace_friends', array($this, 'display_friends_shortcode'));
        register_activation_hook(__FILE__, array($this, 'activate'));
    }
    
    public function init() {
        $this->create_friend_post_type();
    }
    
    public function activate() {
        $this->create_friend_post_type();
        flush_rewrite_rules();
        
        // Set default options
        if (!get_option('myspace_friends_settings')) {
            $defaults = array(
                'owner_name' => get_bloginfo('name'),
                'friends_count' => 'plenty of',
                'show_count' => 8,
                'online_probability' => 30
            );
            add_option('myspace_friends_settings', $defaults);
        }
    }
    
    public function create_friend_post_type() {
        $args = array(
            'public' => true,
            'label' => 'Friends',
            'labels' => array(
                'name' => 'Friends',
                'singular_name' => 'Friend',
                'add_new' => 'Add New Friend',
                'add_new_item' => 'Add New Friend',
                'edit_item' => 'Edit Friend',
                'new_item' => 'New Friend',
                'view_item' => 'View Friend',
                'search_items' => 'Search Friends',
                'not_found' => 'No friends found',
                'not_found_in_trash' => 'No friends found in trash'
            ),
            'supports' => array('title', 'thumbnail'),
            'menu_icon' => 'dashicons-groups',
            'show_in_menu' => true,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_admin_bar' => false,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => false,
            'query_var' => false
        );
        register_post_type('myspace_friend', $args);
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('myspace-friends-style', MYSPACE_FRIENDS_PLUGIN_URL . 'assets/style.css', array(), MYSPACE_FRIENDS_VERSION);
    }
    
    public function add_admin_menu() {
        add_options_page(
            'MySpace Friends Settings',
            'MySpace Friends',
            'manage_options',
            'myspace-friends-settings',
            array($this, 'admin_page')
        );
    }
    
    public function admin_init() {
        register_setting('myspace_friends_settings', 'myspace_friends_settings');
        
        add_settings_section(
            'myspace_friends_main',
            'Main Settings',
            null,
            'myspace-friends-settings'
        );
        
        add_settings_field(
            'owner_name',
            'Owner Name',
            array($this, 'owner_name_callback'),
            'myspace-friends-settings',
            'myspace_friends_main'
        );
        
        add_settings_field(
            'friends_count',
            'Friends Count Text',
            array($this, 'friends_count_callback'),
            'myspace-friends-settings',
            'myspace_friends_main'
        );
        
        add_settings_field(
            'show_count',
            'Number of Friends to Show',
            array($this, 'show_count_callback'),
            'myspace-friends-settings',
            'myspace_friends_main'
        );
        
        add_settings_field(
            'online_probability',
            'Online Status Probability (%)',
            array($this, 'online_probability_callback'),
            'myspace-friends-settings',
            'myspace_friends_main'
        );
    }
    
    public function owner_name_callback() {
        $options = get_option('myspace_friends_settings');
        $value = isset($options['owner_name']) ? $options['owner_name'] : get_bloginfo('name');
        echo '<input type="text" name="myspace_friends_settings[owner_name]" value="' . esc_attr($value) . '" />';
        echo '<p class="description">The name that appears in "Name has plenty of Friends"</p>';
    }
    
    public function friends_count_callback() {
        $options = get_option('myspace_friends_settings');
        $value = isset($options['friends_count']) ? $options['friends_count'] : 'plenty of';
        echo '<input type="text" name="myspace_friends_settings[friends_count]" value="' . esc_attr($value) . '" />';
        echo '<p class="description">Text to display (e.g., "plenty of", "405", "tons of")</p>';
    }
    
    public function show_count_callback() {
        $options = get_option('myspace_friends_settings');
        $value = isset($options['show_count']) ? $options['show_count'] : 8;
        echo '<input type="number" name="myspace_friends_settings[show_count]" value="' . esc_attr($value) . '" min="1" max="20" />';
        echo '<p class="description">How many friends to display at once</p>';
    }
    
    public function online_probability_callback() {
        $options = get_option('myspace_friends_settings');
        $value = isset($options['online_probability']) ? $options['online_probability'] : 30;
        echo '<input type="number" name="myspace_friends_settings[online_probability]" value="' . esc_attr($value) . '" min="0" max="100" />';
        echo '<p class="description">Percentage chance a friend will show as "Online Now!"</p>';
    }
    
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>MySpace Friends Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('myspace_friends_settings');
                do_settings_sections('myspace-friends-settings');
                submit_button();
                ?>
            </form>
            
            <div style="margin-top: 30px; padding: 20px; background: #f1f1f1; border-radius: 5px;">
                <h3>How to Use</h3>
                <p><strong>Step 1:</strong> Add friends by going to <a href="<?php echo admin_url('edit.php?post_type=myspace_friend'); ?>">Friends</a> in your admin menu</p>
                <p><strong>Step 2:</strong> For each friend, set a title (their name) and featured image (their photo)</p>
                <p><strong>Step 3:</strong> Use the shortcode <code>[myspace_friends]</code> in any post, page, or widget to display your friends</p>
                <p><strong>Step 4:</strong> Customize the settings above to personalize your friends display</p>
            </div>
        </div>
        <?php
    }
    
    public function display_friends_shortcode($atts) {
        $atts = shortcode_atts(array(
            'count' => null
        ), $atts);
        
        $options = get_option('myspace_friends_settings');
        $owner_name = isset($options['owner_name']) ? $options['owner_name'] : get_bloginfo('name');
        $friends_count = isset($options['friends_count']) ? $options['friends_count'] : 'plenty of';
        $show_count = $atts['count'] ? $atts['count'] : (isset($options['show_count']) ? $options['show_count'] : 8);
        $online_probability = isset($options['online_probability']) ? $options['online_probability'] : 30;
        
        // Get friends
        $friends = get_posts(array(
            'post_type' => 'myspace_friend',
            'posts_per_page' => $show_count,
            'post_status' => 'publish',
            'orderby' => 'menu_order',
            'order' => 'ASC'
        ));
        
        if (empty($friends)) {
            return '<div class="myspace-friends-container"><p>No friends added yet. <a href="' . admin_url('post-new.php?post_type=myspace_friend') . '">Add some friends!</a></p></div>';
        }
        
        ob_start();
        ?>
        <div class="myspace-friends-container">
            <div class="myspace-friends-header">
                <span class="friends-title"><?php echo esc_html($owner_name); ?>'s Top Friends</span>
            </div>
            <div class="myspace-friends-content">
                <div class="friends-count">
                    <?php echo esc_html($owner_name); ?> has <strong><?php echo esc_html($friends_count); ?></strong> Friends.
                </div>
                <div class="friends-grid">
                    <?php foreach ($friends as $friend): ?>
                        <div class="friend-item">
                            <div class="friend-name"><?php echo esc_html($friend->post_title); ?></div>
                            <div class="friend-photo">
                                <?php 
                                if (has_post_thumbnail($friend->ID)) {
                                    echo get_the_post_thumbnail($friend->ID, array(100, 100));
                                } else {
                                    echo '<div class="no-photo">No Photo</div>';
                                }
                                ?>
                            </div>
                            <?php if (rand(1, 100) <= $online_probability): ?>
                                <div class="online-status">
                                    <img src="<?php echo MYSPACE_FRIENDS_PLUGIN_URL; ?>assets/online-icon.png" alt="Online" class="online-icon">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="view-all-friends">
                    <!-- <a href="#" onclick="return false;">View All of <?php echo esc_html($owner_name); ?>'s Friends</a> -->
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the plugin
new MySpaceTopFriends();
