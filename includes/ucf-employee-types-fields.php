<?php
/**
 * Responsible for additional taxonomy meta
 **/
if ( ! class_exists( 'UCF_Employee_Types_Fields' ) ) {
	class UCF_Employee_Types_Fields {
		/**
		 * Registers meta fields related to employee types
		 * @author RJ Bruneel
		 * @since 1.0.0
		 **/
		public static function register_meta_fields() {
			add_action( 'employee_types_add_form_fields', array( 'UCF_Employee_Types_Fields', 'add_fields' ), 10, 1 );
			add_action( 'employee_types_edit_form_fields', array( 'UCF_Employee_Types_Fields', 'edit_fields' ), 10, 2 );
			add_action( 'created_employee_types', array( 'UCF_Employee_Types_Fields', 'save_employee_types_meta' ), 10, 2 );
			add_action( 'edited_employee_types', array( 'UCF_Employee_Types_Fields', 'edited_employee_types_meta' ), 10, 2 );
		}

		/**
		 * Adds meta fields for employee types
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $taxonomy WP_Taxonomy | The taxonomy object
		 **/
		public static function add_fields( $taxonomy ) {
			// Get colleges if the plugin is installed.
			$colleges = self::get_colleges();
		?>
			<div class="form-field term-group">
				<label for="employee_types_alias"><?php _e( 'Employee Type Alias', 'ucf_employee_types' ); ?></label>
				<input type="text" id="employee_types_alias" name="employee_types_alias">
			</div>
			<div class="form-field term-group">
				<label for="employee_types_website"><?php _e( 'Employee Type Website', 'ucf_employee_types' ); ?></label>
				<input type="url" id="employee_types_website" name="employee_types_website">
			</div>
			<?php if ( $colleges ) : ?>
			<div class="form-field term-group">
				<label for="employee_types_college"><?php _e( 'College', 'ucf_employee_types' ); ?></label>
				<select id="employee_types_college" name="employee_types_college">
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
		 * Adds meta fields for employee types in the edit screen
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $term WP_Term | The term object
		 * @param $taxonomy WP_Taxonomy | The taxonomy object
		 **/
		public static function edit_fields( $term, $taxonomy ) {
			$alias = get_term_meta( $term->term_id, 'employee_types_alias', true );
			$website = get_term_meta( $term->term_id, 'employee_types_website', true );
			$college = intval( get_term_meta( $term->term_id, 'employee_types_college', true ) );

			// Cast if set
			$college = $college !== 0 ? $college : null;

			$colleges = self::get_colleges();

		?>
			<tr class="form-field term-group-wrap">
				<th scope="row"><label for="employee_types_alias"><?php _e( 'Employee Type Alias', 'ucf_employee_types' ); ?></label></th>
				<td><input type="text" id="employee_types_alias" name="employee_types_alias" value="<?php echo $alias; ?>"></td>
			</tr>
			<tr class="form-field term-group-wrap">
				<th scope="row"><label for="employee_types_website"><?php _e( 'Employee Type Website', 'ucf_employee_types' ); ?></label></th>
				<td><input type="url" id="employee_types_website" name="employee_types_website" value="<?php echo $website; ?>"></td>
			</tr>
			<?php if ( $colleges ) : ?>
			<tr class="form-field term-group-wrap">
				<th scope="row"><label for="employee_types_college"><?php _e( 'College', 'ucf_employee_types' ); ?></label></th>
				<td>
					<select id="employee_types_college" name="employee_types_college">
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
		 * Saves employee types meta data
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $term_id int | The term id
		 * @param $tt_id int | The taxonomy term id
		 **/
		public static function save_employee_types_meta( $term_id, $tt_id ) {
			if ( isset( $_POST['employee_types_alias'] ) && '' !== $_POST['employee_types_alias'] ) {
				$alias = sanitize_text_field( $_POST['employee_types_alias'] );
				add_term_meta( $term_id, 'employee_types_alias', $alias, true );
			}

			if ( isset( $_POST['employee_types_website'] ) && '' !== $_POST['employee_types_website'] ) {
				$website = sanitize_url( $_POST['employee_types_website'] );
				add_term_meta( $term_id, 'employee_types_website', $website, true );
			}

			if ( isset( $_POST['employee_types_college'] ) && '' !== $_POST['employee_types_college'] ) {
				$college_id = absint( $_POST['employee_types_college'] );
				add_term_meta( $term_id, 'employee_types_college', $college_id, true );
			}
		}

		/**
		 * Saved employee types meta data on edit
		 * @author RJ Bruneel
		 * @since 1.0.0
		 * @param $term_id int | The term id
		 * @param $tt_id int | The taxonomy term id
		 **/
		public static function edited_employee_types_meta( $term_id, $tt_id ) {
			if ( isset( $_POST['employee_types_alias'] ) && '' !== $_POST['employee_types_alias'] ) {
				$alias = sanitize_text_field( $_POST['employee_types_alias'] );
				update_term_meta( $term_id, 'employee_types_alias', $alias );
			}

			if ( isset( $_POST['employee_types_website'] ) && '' !== $_POST['employee_types_website'] ) {
				$website = sanitize_url( $_POST['employee_types_website'] );
				update_term_meta( $term_id, 'employee_types_website', $website );
			}

			if ( isset( $_POST['employee_types_college'] ) && '' !== $_POST['employee_types_college'] ) {
				$college_id = intval( $_POST['employee_types_college'] );
				update_term_meta( $term_id, 'employee_types_college', $college_id );
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
