<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if( ! defined('PATH_THIRD')) { define('PATH_THIRD', APPPATH . 'third_party'); };
require_once PATH_THIRD . 'zenbu/addon.setup.php';

class Zenbu_total_revisions_ext {

	var $name				= ZENBU_NAME;
	var $version 			= ZENBU_VER;
	var $description		= ZENBU_DESCRIPTION;
	var $settings_exist		= ZENBU_SETTINGS_EXIST;
	var $docs_url			= 'https://zenbustudio.com/software/docs/zenbu';
	var $settings        	= array();

	/**
	 * Constructor
	 *
	 * @param 	mixed	Settings array or empty string if none exist.
	 */
	public function __construct($settings='')
	{
		$this->settings			= $settings;

		//	----------------------------------------
		//	Load Session Libraries if not available
		//	(eg. in cp_js_end hook) - EE 2.6
		//	----------------------------------------

		// Get the old last_call first, just to be sure we have it

		$old_last_call = ee()->extensions->last_call;

		if ( ! isset(ee()->session) || ! isset(ee()->session->userdata) )
        {

            if (file_exists(APPPATH . 'libraries/Localize.php'))
            {
                ee()->load->library('localize');
            }

            if (file_exists(APPPATH . 'libraries/Remember.php'))
            {
                ee()->load->library('remember');
            }

            if (file_exists(APPPATH.'libraries/Session.php'))
            {
                ee()->load->library('session');
            }
        }

		ee()->lang->loadfile('zenbu_total_revisions');


	} // END __construct()

	// --------------------------------------------------------------------


	/**
	 * @param $output
	 *
	 * @return mixed
	 */
	public function zenbu_modify_results($output)
	{
		//	----------------------------------------
		//	Pick up any output data from other addons
		//  that use the same hook.
		//	----------------------------------------

		if (ee()->extensions->last_call !== FALSE)
		{
			$output = ee()->extensions->last_call;
		}

		//	----------------------------------------
		//	$output['display_settings'] contains the data for
		//  the columns that are selected to be displayed in Zenbu.
		//  Add to this array to add a column.
		//	----------------------------------------

		$output['display_settings'][] = [
			'fieldId' => null,
			'fieldType' => 'total_revisions',
			'order' => 1,
		];

		//	----------------------------------------
		//	$output['data'] contains the result data,
		//  with formatting for display in Zenbu.
		//  Each array element "row" contains the data
		//  for one entry. You can add to the array
		//  within each row to add data to a custom column.
		//	----------------------------------------

		$output['data'] = array_map(function($row) {
			$total_revisions =  ee('Model')->get('ChannelEntryVersion')->filter('entry_id', $row['entry_id'])->count();
			return $row + ['total_revisions' => $total_revisions ? $total_revisions : '0'];
		}, $output['data']);

		// Here's another way to do the above:
		//
		// foreach($output['data'] as $key => $row)
		// {
		//	$total_revisions =  ee('Model')->get('ChannelEntryVersion')->filter('entry_id', $row['entry_id'])->count();
		// 	$output['data'][$key]['Total Revisions'] = $total_revisions ? $total_revisions : '0';
		// }

		//	----------------------------------------
		//	Return your modified result array.
		//	----------------------------------------

		return $output;
	} // END entry_save_and_close_redirect()

	// --------------------------------------------------------------------

	/**
	 * @return string
	 */
	public function zenbu_main_content_end()
	{
		$output = '';

		if (ee()->extensions->last_call !== FALSE)
		{
			$output = ee()->extensions->last_call;
		}

		//	----------------------------------------
		//	The zenbu_main_content_end hook
		//  can be used to add markup or scripts at the
		//  very end of the Zenbu main page.
		//  Here, we're registering a language string
		//  so that "total_revisions" is outputted as
		//  "Total Revisions" in the Zenbu column.
		//	----------------------------------------

		$output .= '<script>languageStrings.total_revisions = "' . lang('total_revisions') . '"</script>';

		return $output;
	}

	/**
	 * Settings Form
	 *
	 * @param	Array	Settings
	 * @return 	string
	 */
	public function settings_form()
	{
		ee()->load->helper('form');
		ee()->load->library('table');

		$query = ee()->db->query("SELECT settings FROM exp_extensions WHERE class = '".__CLASS__."'");

		if($query->num_rows() > 0)
		{
			foreach($query->result_array() as $result)
			{
				$settings = unserialize($result['settings']);
				if(!empty($settings))
				{
					// Do something if settings are present
				}
			}
		}

		$vars = array();

		$vars['settings'] = array(
			);


		return 'Settings';
	} // END settings_form()

	// --------------------------------------------------------------------

	/**
	* Save Settings
	*
	* This public function provides a little extra processing and validation
	* than the generic settings form.
	*
	* @return void
	*/
	public function save_settings()
	{
		if (empty($_POST))
		{
			show_error(ee()->lang->line('unauthorized_access'));
		}

		unset($_POST['submit']);

		$settings = $_POST;

		ee()->db->where('class', __CLASS__);
		ee()->db->update('extensions', array('settings' => serialize($settings)));

		ee()->session->set_flashdata(
			'message_success',
		 	ee()->lang->line('preferences_updated')
		);
	} // END save_settings()

	// --------------------------------------------------------------------

	/**
	 * Install the extension
	 */
	public function activate_extension()
	{
		$data[] = array(
			'class'    => __CLASS__,
			'method'   => "zenbu_modify_results",
			'hook'     => "zenbu_modify_results",
			'settings' => serialize(array()),
			'priority' => 100,
			'version'  => $this->version,
			'enabled'  => "y"
		);

		$data[] = array(
			'class'    => __CLASS__,
			'method'   => "zenbu_main_content_end",
			'hook'     => "zenbu_main_content_end",
			'settings' => serialize(array()),
			'priority' => 100,
			'version'  => $this->version,
			'enabled'  => "y"
		);

		// insert in database
		foreach($data as $key => $data)
		{
			ee()->db->insert('exp_extensions', $data);
		}
	}


	/**
	 * Uninstall the extension
	 */
	public function disable_extension()
	{
	  ee()->db->where('class', __CLASS__);
	  ee()->db->delete('exp_extensions');
	}


	 /**
	 * Update Extension
	 *
	 * This public function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return 	mixed	void on update / false if none
	 */
	public function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}

		if ($current < $this->version)
		{
			// Update to version 1.0
		}

		ee()->db->where('class', __CLASS__);
		ee()->db->update(
					'extensions',
					array('version' => $this->version)
		);

	}
}
// END CLASS