<?php
/**
 * Smart Table of Contents Elementor Widget.
 *
 * @package WP_ContentKit
 */

namespace WP_ContentKit\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use WP_ContentKit\TOC_Parser;
use WP_ContentKit\Heading_Validator;
use WP_ContentKit\Admin_Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TOC_Widget
 */
class TOC_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wpck_smart_toc';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'Smart Table of Contents', 'wp-contentkit' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'general', 'theme-elements-single' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array
	 */
	public function get_keywords() {
		return array( 'toc', 'table of contents', 'daftar isi', 'contentkit', 'seo', 'heading' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'wpck-toc-frontend' );
	}

	/**
	 * Get script dependencies.
	 *
	 * @return array
	 */
	public function get_script_depends() {
		return array( 'wpck-toc-frontend' );
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls() {
		$default_options = Admin_Settings::get_options();

		// ==================== CONTENT TAB ====================
		$this->start_controls_section(
			'section_content_toc',
			array(
				'label' => __( 'Pengaturan Table of Contents', 'wp-contentkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'toc_title',
			array(
				'label'       => __( 'Judul TOC', 'wp-contentkit' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ! empty( $default_options['toc_default_title'] ) ? $default_options['toc_default_title'] : __( 'Daftar Isi', 'wp-contentkit' ),
				'placeholder' => __( 'Masukkan judul TOC...', 'wp-contentkit' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => __( 'HTML Tag Judul', 'wp-contentkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'div'  => 'div',
					'p'    => 'p',
					'span' => 'span',
				),
			)
		);

		$this->add_control(
			'heading_levels',
			array(
				'label'       => __( 'Heading yang Disertakan', 'wp-contentkit' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'default'     => ! empty( $default_options['default_heading_levels'] ) ? $default_options['default_heading_levels'] : array( 'h2', 'h3', 'h4' ),
				'options'     => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'min_headings',
			array(
				'label'       => __( 'Batas Minimal Heading', 'wp-contentkit' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => ! empty( $default_options['min_headings_count'] ) ? $default_options['min_headings_count'] : 2,
				'min'         => 1,
				'max'         => 20,
				'description' => __( 'TOC akan disembunyikan jika jumlah heading pada artikel kurang dari angka ini.', 'wp-contentkit' ),
			)
		);

		$this->add_control(
			'numbering_style',
			array(
				'label'   => __( 'Format Penomoran', 'wp-contentkit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => ! empty( $default_options['toc_numbering'] ) ? $default_options['toc_numbering'] : 'decimal',
				'options' => array(
					'none'    => __( 'Tanpa Nomor (None)', 'wp-contentkit' ),
					'decimal' => __( 'Desimal Normal (1, 2, 3...)', 'wp-contentkit' ),
					'nested'  => __( 'Desimal Bertingkat (1.1, 1.2...)', 'wp-contentkit' ),
				),
			)
		);

		$this->add_control(
			'hierarchical',
			array(
				'label'        => __( 'Indentation Bertingkat', 'wp-contentkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Ya', 'wp-contentkit' ),
				'label_off'    => __( 'Tidak', 'wp-contentkit' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'collapsible',
			array(
				'label'        => __( 'Dapat Ditutup (Collapsible)', 'wp-contentkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Ya', 'wp-contentkit' ),
				'label_off'    => __( 'Tidak', 'wp-contentkit' ),
				'return_value' => 'yes',
				'default'      => ! empty( $default_options['toc_collapsible'] ) && '1' === $default_options['toc_collapsible'] ? 'yes' : 'no',
			)
		);

		$this->add_control(
			'default_state',
			array(
				'label'     => __( 'Status Default Awal', 'wp-contentkit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => ! empty( $default_options['toc_default_state'] ) ? $default_options['toc_default_state'] : 'expanded',
				'options'   => array(
					'expanded'  => __( 'Terbuka (Expanded)', 'wp-contentkit' ),
					'collapsed' => __( 'Tertutup (Collapsed)', 'wp-contentkit' ),
				),
				'condition' => array(
					'collapsible' => 'yes',
				),
			)
		);

		$this->add_control(
			'smooth_scroll',
			array(
				'label'        => __( 'Smooth Scroll', 'wp-contentkit' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Aktif', 'wp-contentkit' ),
				'label_off'    => __( 'Mati', 'wp-contentkit' ),
				'return_value' => 'yes',
				'default'      => ! empty( $default_options['toc_smooth_scroll'] ) && '1' === $default_options['toc_smooth_scroll'] ? 'yes' : 'no',
			)
		);

		$this->add_control(
			'scroll_offset',
			array(
				'label'       => __( 'Offset Sticky Header (px)', 'wp-contentkit' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => ! empty( $default_options['toc_scroll_offset'] ) ? $default_options['toc_scroll_offset'] : 80,
				'min'         => 0,
				'max'         => 300,
				'condition'   => array(
					'smooth_scroll' => 'yes',
				),
				'description' => __( 'Jarak jeda atas saat scroll berhenti agar judul tidak tertutup sticky header tema.', 'wp-contentkit' ),
			)
		);

		$this->end_controls_section();

		// ==================== HEADING VALIDATOR SECTION ====================
		$this->start_controls_section(
			'section_heading_validator',
			array(
				'label' => __( 'SEO Heading Hierarchy Validator', 'wp-contentkit' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'validator_description',
			array(
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<div class="wpck-validator-badge-info" style="font-size: 13px; line-height: 1.5; color: #475569;">' .
					__( 'Sistem ini memeriksa apakah struktur heading (H2-H6) pada postingan tersusun secara logis tanpa loncatan level yang membingungkan mesin pencari (SEO).', 'wp-contentkit' ) .
				'</div>',
				'content_classes' => 'wpck-validator-intro',
			)
		);

		$this->end_controls_section();

		// ==================== STYLE TAB: CONTAINER ====================
		$this->start_controls_section(
			'section_style_box',
			array(
				'label' => __( 'Kotak Container TOC', 'wp-contentkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'box_background',
				'label'    => __( 'Background', 'wp-contentkit' ),
				'types'    => array( 'classic', 'gradient' ),
				'selector' => '{{WRAPPER}} .wpck-toc-container',
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'box_border',
				'label'    => __( 'Border', 'wp-contentkit' ),
				'selector' => '{{WRAPPER}} .wpck-toc-container',
			)
		);

		$this->add_responsive_control(
			'box_border_radius',
			array(
				'label'      => __( 'Border Radius (px)', 'wp-contentkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpck-toc-container' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'box_shadow',
				'label'    => __( 'Box Shadow', 'wp-contentkit' ),
				'selector' => '{{WRAPPER}} .wpck-toc-container',
			)
		);

		$this->add_responsive_control(
			'box_padding',
			array(
				'label'      => __( 'Padding (px)', 'wp-contentkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpck-toc-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'box_margin',
			array(
				'label'      => __( 'Margin (px)', 'wp-contentkit' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpck-toc-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// ==================== STYLE TAB: HEADER ====================
		$this->start_controls_section(
			'section_style_header',
			array(
				'label' => __( 'Header & Judul', 'wp-contentkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'header_title_color',
			array(
				'label'     => __( 'Warna Judul', 'wp-contentkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpck-toc-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'header_typography',
				'label'    => __( 'Tipografi Judul', 'wp-contentkit' ),
				'selector' => '{{WRAPPER}} .wpck-toc-title',
			)
		);

		$this->add_control(
			'header_toggle_color',
			array(
				'label'     => __( 'Warna Tombol Toggle', 'wp-contentkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpck-toc-toggle-btn' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpck-toc-toggle-btn svg' => 'fill: {{VALUE}};',
				),
				'condition' => array(
					'collapsible' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'header_padding_bottom',
			array(
				'label'      => __( 'Jarak ke Daftar Isi (px)', 'wp-contentkit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wpck-toc-header' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// ==================== STYLE TAB: LIST & ITEMS ====================
		$this->start_controls_section(
			'section_style_items',
			array(
				'label' => __( 'Item & Tautan Daftar Isi', 'wp-contentkit' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'items_typography',
				'label'    => __( 'Tipografi Item', 'wp-contentkit' ),
				'selector' => '{{WRAPPER}} .wpck-toc-list a',
			)
		);

		$this->add_control(
			'item_color_normal',
			array(
				'label'     => __( 'Warna Teks Normal', 'wp-contentkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpck-toc-list a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpck-toc-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'item_color_hover',
			array(
				'label'     => __( 'Warna Teks Hover', 'wp-contentkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpck-toc-list a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wpck-toc-list a:hover .wpck-toc-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'item_color_active',
			array(
				'label'     => __( 'Warna Teks Active (Scroll)', 'wp-contentkit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpck-toc-item.wpck-active > a' => 'color: {{VALUE}}; font-weight: 600;',
					'{{WRAPPER}} .wpck-toc-item.wpck-active > a .wpck-toc-number' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_gap',
			array(
				'label'      => __( 'Jarak Antar Baris (px)', 'wp-contentkit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 2,
						'max' => 30,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wpck-toc-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'nested_indent',
			array(
				'label'      => __( 'Lebar Indent Bertingkat (px)', 'wp-contentkit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 5,
						'max' => 50,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .wpck-toc-sublist' => 'padding-left: {{SIZE}}{{UNIT}};',
				),
				'condition'  => array(
					'hierarchical' => 'yes',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render Table of Contents on the frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$is_editor = \Elementor\Plugin::$instance->editor->is_edit_mode();

		// Fetch post content.
		global $post;
		$content = '';
		if ( $post && ! empty( $post->post_content ) ) {
			$content = $post->post_content;
		}

		$allowed_levels = ! empty( $settings['heading_levels'] ) ? (array) $settings['heading_levels'] : array( 'h2', 'h3', 'h4' );
		$min_count      = isset( $settings['min_headings'] ) ? (int) $settings['min_headings'] : 2;
		$collapsible    = 'yes' === $settings['collapsible'];
		$initial_state  = ! empty( $settings['default_state'] ) ? $settings['default_state'] : 'expanded';
		$numbering      = ! empty( $settings['numbering_style'] ) ? $settings['numbering_style'] : 'decimal';
		$hierarchical   = 'yes' === $settings['hierarchical'];
		$smooth_scroll  = 'yes' === $settings['smooth_scroll'];
		$scroll_offset  = isset( $settings['scroll_offset'] ) ? (int) $settings['scroll_offset'] : 80;

		$parser = new TOC_Parser( array(
			'allowed_levels' => $allowed_levels,
			'min_headings'   => $min_count,
		) );

		$headings = $parser->extract_headings( $content );
		$count    = count( $headings );

		// Validate hierarchy.
		$validation = Heading_Validator::validate( $headings );

		// Editor Mode Display: Show Validator Badge if editing.
		if ( $is_editor ) {
			$this->render_editor_validator_box( $validation, $count, $min_count );
		}

		// Check if threshold met.
		if ( $count < $min_count ) {
			if ( $is_editor ) {
				echo '<div class="wpck-toc-editor-notice" style="border: 2px dashed #94a3b8; background: #f8fafc; padding: 18px; border-radius: 8px; text-align: center; color: #475569; font-family: sans-serif;">';
				echo '<strong>' . esc_html__( 'WP ContentKit Smart TOC', 'wp-contentkit' ) . '</strong><br>';
				printf(
					esc_html__( 'Ditemukan %1$d heading. TOC tidak ditampilkan di frontend karena batas minimal diatur ke %2$d heading.', 'wp-contentkit' ),
					$count,
					$min_count
				);
				echo '</div>';
			}
			return;
		}

		// CSS Classes.
		$container_classes = array(
			'wpck-toc-container',
			'wpck-toc-' . esc_attr( $initial_state ),
		);

		if ( $collapsible ) {
			$container_classes[] = 'wpck-is-collapsible';
		}

		if ( $hierarchical ) {
			$container_classes[] = 'wpck-is-hierarchical';
		}

		$title_tag = ! empty( $settings['title_tag'] ) ? tag_escape( $settings['title_tag'] ) : 'h3';
		$title_txt = ! empty( $settings['toc_title'] ) ? esc_html( $settings['toc_title'] ) : esc_html__( 'Daftar Isi', 'wp-contentkit' );

		?>
		<div class="<?php echo esc_attr( implode( ' ', $container_classes ) ); ?>"
			 data-smooth-scroll="<?php echo $smooth_scroll ? 'true' : 'false'; ?>"
			 data-scroll-offset="<?php echo esc_attr( $scroll_offset ); ?>"
			 role="navigation"
			 aria-label="<?php echo esc_attr( $title_txt ); ?>">

			<div class="wpck-toc-header">
				<<?php echo $title_tag; ?> class="wpck-toc-title">
					<?php echo $title_txt; ?>
				</<?php echo $title_tag; ?>>

				<?php if ( $collapsible ) : ?>
					<button type="button" class="wpck-toc-toggle-btn" aria-expanded="<?php echo 'expanded' === $initial_state ? 'true' : 'false'; ?>" aria-label="<?php esc_attr_e( 'Toggle Table of Contents', 'wp-contentkit' ); ?>">
						<span class="wpck-toggle-icon-open" aria-hidden="true">&#9660;</span>
						<span class="wpck-toggle-icon-close" aria-hidden="true">&#9650;</span>
					</button>
				<?php endif; ?>
			</div>

			<div class="wpck-toc-body" <?php echo ( $collapsible && 'collapsed' === $initial_state ) ? 'style="display:none;"' : ''; ?>>
				<?php
				if ( $hierarchical ) {
					$tree = TOC_Parser::build_hierarchy_tree( $headings );
					$this->render_nested_tree( $tree, $numbering );
				} else {
					$this->render_flat_list( $headings, $numbering );
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render nested hierarchy tree.
	 *
	 * @param array  $nodes Nodes tree.
	 * @param string $numbering Numbering style.
	 * @param string $prefix Prefix for nested numbering.
	 */
	private function render_nested_tree( $nodes, $numbering = 'decimal', $prefix = '' ) {
		if ( empty( $nodes ) ) {
			return;
		}

		$is_root = empty( $prefix );
		$list_class = $is_root ? 'wpck-toc-list' : 'wpck-toc-sublist';

		echo '<ul class="' . esc_attr( $list_class ) . '">';
		$counter = 0;

		foreach ( $nodes as $node ) {
			$counter++;
			$num_label = '';

			if ( 'nested' === $numbering ) {
				$num_label = ( $is_root ? $counter : $prefix . '.' . $counter ) . '. ';
			} elseif ( 'decimal' === $numbering ) {
				$num_label = $counter . '. ';
			}

			$has_children = ! empty( $node['children'] );

			echo '<li class="wpck-toc-item wpck-level-' . esc_attr( $node['level'] ) . '">';
			echo '<a href="#' . esc_attr( $node['id'] ) . '">';
			if ( ! empty( $num_label ) ) {
				echo '<span class="wpck-toc-number">' . esc_html( $num_label ) . '</span>';
			}
			echo '<span class="wpck-toc-text">' . esc_html( $node['title'] ) . '</span>';
			echo '</a>';

			if ( $has_children ) {
				$current_prefix = $is_root ? (string) $counter : $prefix . '.' . $counter;
				$this->render_nested_tree( $node['children'], $numbering, $current_prefix );
			}

			echo '</li>';
		}

		echo '</ul>';
	}

	/**
	 * Render flat list of headings.
	 *
	 * @param array  $headings Flat headings list.
	 * @param string $numbering Numbering style.
	 */
	private function render_flat_list( $headings, $numbering = 'decimal' ) {
		echo '<ul class="wpck-toc-list">';
		$counter = 0;

		foreach ( $headings as $h ) {
			$counter++;
			$num_label = '';
			if ( 'none' !== $numbering ) {
				$num_label = $counter . '. ';
			}

			echo '<li class="wpck-toc-item wpck-level-' . esc_attr( $h['level'] ) . '">';
			echo '<a href="#' . esc_attr( $h['id'] ) . '">';
			if ( ! empty( $num_label ) ) {
				echo '<span class="wpck-toc-number">' . esc_html( $num_label ) . '</span>';
			}
			echo '<span class="wpck-toc-text">' . esc_html( $h['title'] ) . '</span>';
			echo '</a>';
			echo '</li>';
		}

		echo '</ul>';
	}

	/**
	 * Render visual validator box in Elementor editor.
	 *
	 * @param array $validation Validation report.
	 * @param int   $count Found count.
	 * @param int   $min_count Threshold.
	 */
	private function render_editor_validator_box( $validation, $count, $min_count ) {
		$status = $validation['status'];
		$bg_color = '#f0fdf4';
		$border_color = '#86efac';
		$text_color = '#15803d';

		if ( Heading_Validator::STATUS_WARNING === $status ) {
			$bg_color = '#fffbeb';
			$border_color = '#fde047';
			$text_color = '#b45309';
		} elseif ( Heading_Validator::STATUS_PROBLEM === $status ) {
			$bg_color = '#fef2f2';
			$border_color = '#fca5a5';
			$text_color = '#b91c1c';
		}

		?>
		<div class="wpck-editor-validator-card" style="background: <?php echo esc_attr( $bg_color ); ?>; border: 1px solid <?php echo esc_attr( $border_color ); ?>; border-radius: 8px; padding: 12px 14px; margin-bottom: 16px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; line-height: 1.4;">
			<div style="font-weight: 700; color: <?php echo esc_attr( $text_color ); ?>; display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
				<span><?php echo esc_html( $validation['status_label'] ); ?></span>
				<span style="font-size: 11px; background: rgba(0,0,0,0.06); padding: 2px 6px; border-radius: 4px;"><?php printf( esc_html__( '%d Heading Terdeteksi', 'wp-contentkit' ), $count ); ?></span>
			</div>
			<p style="margin: 0; color: #334155; font-size: 12px;">
				<?php echo esc_html( $validation['summary'] ); ?>
			</p>

			<?php if ( ! empty( $validation['issues'] ) ) : ?>
				<div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed rgba(0,0,0,0.1);">
					<?php foreach ( array_slice( $validation['issues'], 0, 3 ) as $issue ) : ?>
						<div style="margin-bottom: 6px; font-size: 11.5px;">
							<strong style="color: <?php echo esc_attr( $text_color ); ?>;">[<?php echo esc_html( $issue['tag'] ); ?> #<?php echo esc_html( $issue['heading_idx'] ); ?>]</strong>
							<span style="color: #475569;"><?php echo esc_html( $issue['reason'] ); ?></span>
							<div style="color: #0284c7; font-style: italic; margin-top: 2px;">&bull; <?php echo esc_html( $issue['suggestion'] ); ?></div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}
}
