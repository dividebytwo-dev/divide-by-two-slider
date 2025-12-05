<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Divide_By_Two_Slider_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'divide_by_two_slider',
            __( 'Divide by Two Slider', 'divide-by-two-slider' ),
            array( 'description' => __( 'A simple draggable slider.', 'divide-by-two-slider' ) )
        );
    }

    public function form( $instance ) {
        $title  = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $slides = ! empty( $instance['slides'] ) ? $instance['slides'] : array();

        // Flickity settings
        $cellAlign = ! empty( $instance['cellAlign'] ) ? $instance['cellAlign'] : 'center';
        $contain = isset( $instance['contain'] ) ? (bool) $instance['contain'] : true;
        $autoPlay = isset( $instance['autoPlay'] ) ? $instance['autoPlay'] : 0;
        $wrapAround = isset( $instance['wrapAround'] ) ? (bool) $instance['wrapAround'] : true;
        $fade = isset( $instance['fade'] ) ? (bool) $instance['fade'] : false;
        $freeScroll = isset( $instance['freeScroll'] ) ? (bool) $instance['freeScroll'] : false;
        $prevNextButtons = isset( $instance['prevNextButtons'] ) ? (bool) $instance['prevNextButtons'] : true;
        $pageDots = isset( $instance['pageDots'] ) ? (bool) $instance['pageDots'] : true;
        $adaptiveHeight = isset( $instance['adaptiveHeight'] ) ? (bool) $instance['adaptiveHeight'] : false;
        $draggable = isset( $instance['draggable'] ) ? (bool) $instance['draggable'] : true;
        $initialIndex = isset( $instance['initialIndex'] ) ? intval( $instance['initialIndex'] ) : 0;
        $pauseAutoPlayOnHover = isset( $instance['pauseAutoPlayOnHover'] ) ? (bool) $instance['pauseAutoPlayOnHover'] : true;
        $groupCells = isset($instance['groupCells']) ? $instance['groupCells'] : 1;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php _e( 'Title:', 'divide-by-two-slider' ); ?>
            </label>
            <input 
                class="widefat"
                id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                type="text"
                value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <div class="dbt-slides-repeater">
            <?php
            if ( ! empty( $slides ) && is_array( $slides ) ) {
                foreach ( $slides as $index => $slide ) {
                    $this->slide_form( $index, $slide );
                }
            }
            ?>
        </div>

        <p>
            <button 
                type="button" 
                class="button dbt-add-slide" 
                data-name="<?php echo esc_attr( $this->get_field_name( 'slides' ) ); ?>">
                <?php _e( 'Add Slide', 'divide-by-two-slider' ); ?>
            </button>
        </p>

        <p>
            <button type="button" class="button dbt-toggle-settings"><?php _e( 'Slider Settings', 'divide-by-two-slider' ); ?></button>
        </p>

        <div class="dbt-slider-settings" style="display:none; border:1px solid #ddd; padding:10px; margin-bottom:10px;">
            <fieldset>
                <legend><?php _e( 'Slider Settings', 'divide-by-two-slider' ); ?></legend>
        
                <p>
                    <label><?php _e( 'Cell Align', 'divide-by-two-slider' ); ?></label>
                    <select name="<?php echo $this->get_field_name( 'cellAlign' ); ?>">
                        <option value="left" <?php selected( $cellAlign, 'left' ); ?>>Left</option>
                        <option value="center" <?php selected( $cellAlign, 'center' ); ?>>Center</option>
                        <option value="right" <?php selected( $cellAlign, 'right' ); ?>>Right</option>
                    </select>
                </p>
                
                <p>
                    <label for="<?php echo esc_attr($this->get_field_id('groupCells')); ?>">
                        <?php esc_html_e('Group Cells', 'textdomain'); ?>
                    </label>
                    <input 
                        id="<?php echo esc_attr($this->get_field_id('groupCells')); ?>" 
                        name="<?php echo esc_attr($this->get_field_name('groupCells')); ?>" 
                        type="number" 
                        value="<?php echo esc_attr($groupCells); ?>" 
                        min="1" 
                    />
                </p>
        
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'contain' ); ?>" <?php checked( $contain ); ?>> <?php _e( 'Contain', 'divide-by-two-slider' ); ?></p>
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'wrapAround' ); ?>" <?php checked( $wrapAround ); ?>> <?php _e( 'Wrap Around', 'divide-by-two-slider' ); ?></p>
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'fade' ); ?>" <?php checked( $fade ); ?>> <?php _e( 'Fade Transition', 'divide-by-two-slider' ); ?></p>
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'freeScroll' ); ?>" <?php checked( $freeScroll ); ?>> <?php _e( 'Free Scroll', 'divide-by-two-slider' ); ?></p>
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'prevNextButtons' ); ?>" <?php checked( $prevNextButtons ); ?>> <?php _e( 'Prev/Next Buttons', 'divide-by-two-slider' ); ?></p>
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'pageDots' ); ?>" <?php checked( $pageDots ); ?>> <?php _e( 'Page Dots', 'divide-by-two-slider' ); ?></p>
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'adaptiveHeight' ); ?>" <?php checked( $adaptiveHeight ); ?>> <?php _e( 'Adaptive Height', 'divide-by-two-slider' ); ?></p>
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'draggable' ); ?>" <?php checked( $draggable ); ?>> <?php _e( 'Draggable', 'divide-by-two-slider' ); ?></p>
                <p><input type="checkbox" name="<?php echo $this->get_field_name( 'pauseAutoPlayOnHover' ); ?>" <?php checked( $pauseAutoPlayOnHover ); ?>> <?php _e( 'Pause on Hover', 'divide-by-two-slider' ); ?></p>
        
                <p>
                    <label><?php _e( 'Auto Play (ms or 0 for off)', 'divide-by-two-slider' ); ?></label>
                    <input type="number" name="<?php echo $this->get_field_name( 'autoPlay' ); ?>" value="<?php echo esc_attr( $autoPlay ); ?>">
                </p>
        
                <p>
                    <label><?php _e( 'Initial Index', 'divide-by-two-slider' ); ?></label>
                    <input type="number" name="<?php echo $this->get_field_name( 'initialIndex' ); ?>" value="<?php echo esc_attr( $initialIndex ); ?>">
                </p>
            </fieldset>
        </div>
        
        <script>
           jQuery(document).ready(function($){
              $('.dbt-toggle-settings').on('click', function() {
                 $('.dbt-slider-settings').slideToggle();
              });
              
              $('.dbt-toggle-slide').on('click', function() {
                 $(this).closest('.dbt-slide-item').find('.dbt-slide-content').slideToggle();
              });
           });
        </script>
        <?php
    }

    private function slide_form( $index, $slide ) {
        $image  = ! empty( $slide['image'] ) ? esc_url( $slide['image'] ) : '';
        $caption = ! empty( $slide['caption'] ) ? $slide['caption'] : '';
        ?>
        <div class="dbt-slide-item" style="margin-bottom:15px; padding:10px; border:1px solid #ddd;">
            <div class="dbt-slide-header">
                <button type="button" class="dbt-toggle-slide">Toggle Slide</button>
            </div>

            <div class="dbt-slide-content">
                <p>
                    <input class="widefat dbt-slide-image" 
                           name="<?php echo $this->get_field_name( 'slides' ); ?>[<?php echo $index; ?>][image]" 
                           type="text" value="<?php echo esc_url( $image ); ?>" />
                    <button class="button dbt-upload-image"><?php _e( 'Select Image', 'divide-by-two-slider' ); ?></button>
                </p>

                <p>
                    <label><?php _e( 'Caption (HTML allowed):', 'divide-by-two-slider' ); ?></label>
                    <textarea
                        class="widefat"
                        rows="4"
                        name="<?php echo $this->get_field_name('slides') . "[{$index}][caption]"; ?>"
                    ><?php echo esc_textarea( $caption ); ?></textarea>
                </p>

                <p>
                    <button class="button dbt-remove-slide"><?php _e( 'Remove Slide', 'divide-by-two-slider' ); ?></button>
                    <button class="button dbt-duplicate-slide"><?php _e( 'Duplicate Slide', 'divide-by-two-slider' ); ?></button>
                </p>
            </div>
        </div>
        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = sanitize_text_field( $new_instance['title'] );

        // Save Flickity options
        $instance['cellAlign'] = sanitize_text_field( $new_instance['cellAlign'] );
        $instance['contain'] = ! empty( $new_instance['contain'] );
        $instance['autoPlay'] = intval( $new_instance['autoPlay'] );
        $instance['wrapAround'] = ! empty( $new_instance['wrapAround'] );
        $instance['fade'] = ! empty( $new_instance['fade'] );
        $instance['freeScroll'] = ! empty( $new_instance['freeScroll'] );
        $instance['prevNextButtons'] = ! empty( $new_instance['prevNextButtons'] );
        $instance['pageDots'] = ! empty( $new_instance['pageDots'] );
        $instance['adaptiveHeight'] = ! empty( $new_instance['adaptiveHeight'] );
        $instance['draggable'] = ! empty( $new_instance['draggable'] );
        $instance['initialIndex'] = intval( $new_instance['initialIndex'] );
        $instance['pauseAutoPlayOnHover'] = ! empty( $new_instance['pauseAutoPlayOnHover'] );
        $instance['groupCells'] = !empty($new_instance['groupCells']) ? intval($new_instance['groupCells']) : 1;

        if ( ! empty( $new_instance['slides'] ) && is_array( $new_instance['slides'] ) ) {
            $instance['slides'] = array();
            foreach ( $new_instance['slides'] as $slide ) {
                $instance['slides'][] = array(
                    'image'   => ! empty( $slide['image'] ) ? esc_url_raw( $slide['image'] ) : '',
                    'caption' => ! empty( $slide['caption'] ) ? wp_kses_post( $slide['caption'] ) : '',
                );
            }
        }

        return $instance;
    }

    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['slides'] ) && is_array( $instance['slides'] ) ) {
            $slider_settings = [
                'cellAlign'           => $instance['cellAlign'] ?? 'left',
                'contain'             => ! empty( $instance['contain'] ),
                'wrapAround'          => ! empty( $instance['wrapAround'] ),
                'fade'                => ! empty( $instance['fade'] ),
                'autoPlay'            => isset( $instance['autoPlay'] ) ? (int) $instance['autoPlay'] : false,
                'freeScroll'          => ! empty( $instance['freeScroll'] ),
                'prevNextButtons'     => ! empty( $instance['prevNextButtons'] ),
                'pageDots'            => ! empty( $instance['pageDots'] ),
                'adaptiveHeight'      => ! empty( $instance['adaptiveHeight'] ),
                'draggable'           => ! empty( $instance['draggable'] ),
                'initialIndex'        => isset( $instance['initialIndex'] ) ? (int) $instance['initialIndex'] : 0,
                'pauseAutoPlayOnHover'=> ! empty( $instance['pauseAutoPlayOnHover'] ),
                'groupCells'          => isset( $instance['groupCells'] ) ? (int) $instance['groupCells'] : 1,
            ];

            echo '<div class="dbt-slider-wrapper" data-settings="' . esc_attr( wp_json_encode( $slider_settings ) ) . '">';

            foreach ( $instance['slides'] as $slide ) {
                $img     = ! empty( $slide['image'] ) ? esc_url( $slide['image'] ) : '';
                $caption = ! empty( $slide['caption'] ) ? wp_kses_post( $slide['caption'] ) : '';

                echo '<div class="dbt-slide">';
                echo '<div class="dbt-slide-inner">';

                if ( $img ) {
                    echo '<div class="dbt-slide-image">';
                    echo '<img src="' . $img . '" alt="">';
                    echo '</div>';
                }

                if ( $caption ) {
                    echo '<div class="dbt-slide-caption">';
                    echo wp_kses_post( $caption );
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
            }

            echo '</div>';
        }

        echo $args['after_widget'];
    }
}