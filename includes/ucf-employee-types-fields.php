<?php
/**
 * Responsible for additional taxonomy meta
 **/
if ( ! class_exists( 'UCF_Departments_Fields' ) ) {
	class UCF_Departments_Fields {
		/**
		 * Registers meta fields related to departments
		 * @author RJ Bruneel
		 * @since 1.0.0
		 **/
		public static function register_meta_fields() {
			add_action( 'departments_add_form_fields', array( 'UCF_Departments_Fields', 'add_fields' ), 10, 1 );
			add_action( 'departments_edit_form_fields', array( 'UCF_Departments_Fields', 'edit_fields' ), 10, 2 );
			add_action( 'created_departments', array( 'UCF_Departments_Fields', 'save_departments_meta' ), 10, 2 );
			add_action( 'edited_departments', array( 'UCF_Departments_Fields', 'edited_departments_meta' ), 10, 2 );
		}

		/**
		 * Adds meta fields for departments
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $taxonomy WP_Taxonomy | The taxonomy object
		 **/
		public static function add_fields( $taxonomy ) {
			// Get colleges if the plugin is installed.
			$colleges = self::get_colleges();
		?>
			<div class="form-field term-group">
				<label for="departments_alias"><?php _e( 'Department Alias', 'ucf_departments' ); ?></label>
				<input type="text" id="departments_alias" name="departments_alias">
			</div>
			<div class="form-field term-group">
				<label for="departments_website"><?php _e( 'Department Website', 'ucf_departments' ); ?></label>
				<input type="url" id="departments_website" name="departments_website">
			</div>
			<?php if ( $colleges ) : ?>
			<div class="form-field term-group">
				<label for="departments_college"><?php _e( 'College', 'ucf_departments' ); ?></label>
				<select id="departments_college" name="departments_college">
					<option value=""> --- Choose College --- </option>
				<?php foreach( $colleges as $key => $value ) : ?>
					<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
		<?php
		}

		/**
		 * Adds meta fields for departments in the edit screen
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $term WP_Term | The term object
		 * @param $taxonomy WP_Taxonomy | The taxonomy object
		 **/
		public static function edit_fields( $term, $taxonomy ) {
			$alias = get_term_meta( $term->term_id, 'departments_alias', true );
			$website = get_term_meta( $term->term_id, 'departments_website', true );
			$college = intval( get_term_meta( $term->term_id, 'departments_college', true ) );

			// Cast if set
			$college = $college !== 0 ? $college : null;

			$colleges = self::get_colleges();

		?>
			<tr class="form-field term-group-wrap">
				<th scope="row"><label for="departments_alias"><?php _e( 'Department Alias', 'ucf_departments' ); ?></label></th>
				<td><input type="text" id="departments_alias" name="departments_alias" value="<?php echo $alias; ?>"></td>
			</tr>
			<tr class="form-field term-group-wrap">
				<th scope="row"><label for="departments_website"><?php _e( 'Department Website', 'ucf_departments' ); ?></label></th>
				<td><input type="url" id="departments_website" name="departments_website" value="<?php echo $website; ?>"></td>
			</tr>
			<?php if ( $colleges ) : ?>
			<tr class="form-field term-group-wrap">
				<th scope="row"><label for="departments_college"><?php _e( 'College', 'ucf_departments' ); ?></label></th>
				<td>
					<select id="departments_college" name="departments_college">
						<option value=""> --- Choose College --- </option>
					<?php foreach( $colleges as $key => $value ) : ?>
						<option value="<?php echo $key; ?>"<?php echo ( $college === $key ) ? ' selected' : ''; ?>><?php echo $value; ?></option>
					<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<?php endif; ?>
		<?php
		}

		/**
		 * Saves departments meta data
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $term_id int | The term id
		 * @param $tt_id int | The taxonomy term id
		 **/
		public static function save_departments_meta( $term_id, $tt_id ) {
			if ( isset( $_POST['departments_alias'] ) && '' !== $_POST['departments_alias'] ) {
				$alias = sanitize_text_field( $_POST['departments_alias'] );
				add_term_meta( $term_id, 'departments_alias', $alias, true );
			}

			if ( isset( $_POST['departments_website'] ) && '' !== $_POST['departments_website'] ) {
				$website = sanitize_url( $_POST['departments_website'] );
				add_term_meta( $term_id, 'departments_website', $website, true );
			}

			if ( isset( $_POST['departments_college'] ) && '' !== $_POST['departments_college'] ) {
				$college_id = absint( $_POST['departments_college'] );
				add_term_meta( $term_id, 'departments_college', $college_id, true );
			}
		}

		/**
		 * Saved departments meta data on edit
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $term_id int | The term id
		 * @param $tt_id int | The taxonomy term id
		 **/
		public static function edited_departments_meta( $term_id, $tt_id ) {
			if ( isset( $_POST['departments_alias'] ) && '' !== $_POST['departments_alias'] ) {
				$alias = sanitize_text_field( $_POST['departments_alias'] );
				update_term_meta( $term_id, 'departments_alias', $alias );
			}

			if ( isset( $_POST['departments_website'] ) && '' !== $_POST['departments_website'] ) {
				$website = sanitize_url( $_POST['departments_website'] );
				update_term_meta( $term_id, 'departments_website', $website );
			}

			if ( isset( $_POST['departments_college'] ) && '' !== $_POST['departments_college'] ) {
				$college_id = intval( $_POST['departments_college'] );
				update_term_meta( $term_id, 'departments_college', $college_id );
			}
		}

		/**
		 * Returns an array of colleges (slug=>name)
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @return mixed (array|bool) | Returns an array of colleges if available. False if not installed
		 **/
		private static function get_colleges() {
			// If the colleges taxonomy is not installed
			// return false.
			if ( ! taxonomy_exists( 'colleges' ) ) {
				return false;
			}

			$retval = array();

			$colleges = get_terms( array(
				'taxonomy'   => 'colleges',
				'hide_empty' => false
			) );

			foreach( $colleges as $college ) {
				$retval[$college->term_id] = $college->name;
			}

			return $retval;
		}
	}
}
