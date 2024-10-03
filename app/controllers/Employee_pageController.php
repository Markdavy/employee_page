<?php 
/**
 * Employee_page Page Controller
 * @category  Controller
 */
class Employee_pageController extends BaseController{
	function __construct(){
		parent::__construct();
		$this->tablename = "employee_page";
	}
	/**
     * List page records
     * @param $fieldname (filter record by a field) 
     * @param $fieldvalue (filter field value)
     * @return BaseView
     */
	function index($fieldname = null , $fieldvalue = null){
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$fields = array("id", 
			"first_name", 
			"middle_name", 
			"last_name", 
			"address", 
			"password", 
			"birthdate", 
			"contact_number", 
			"civil_status", 
			"email", 
			"work_email", 
			"employee_type", 
			"start_date", 
			"monthly_salary", 
			"account_bonus", 
			"client", 
			"position", 
			"employment_status", 
			"start_shift_day", 
			"end_shift_day", 
			"shift_time_in", 
			"shift_time_out", 
			"lunch_break_start", 
			"lunch_break_end", 
			"sss_number", 
			"sss_contribution", 
			"pagibig_number", 
			"pagibig_contribution", 
			"philhealth_number", 
			"philhealth_contribution", 
			"tin_number", 
			"tax_percentage");
		$pagination = $this->get_pagination(5); // get current pagination e.g array(page_number, page_limit)
		//search table record
		if(!empty($request->search)){
			$text = trim($request->search); 
			$search_condition = "(
				employee_page.id LIKE ? OR 
				employee_page.first_name LIKE ? OR 
				employee_page.middle_name LIKE ? OR 
				employee_page.last_name LIKE ? OR 
				employee_page.address LIKE ? OR 
				employee_page.password LIKE ? OR 
				employee_page.birthdate LIKE ? OR 
				employee_page.contact_number LIKE ? OR 
				employee_page.civil_status LIKE ? OR 
				employee_page.email LIKE ? OR 
				employee_page.work_email LIKE ? OR 
				employee_page.employee_type LIKE ? OR 
				employee_page.start_date LIKE ? OR 
				employee_page.monthly_salary LIKE ? OR 
				employee_page.account_bonus LIKE ? OR 
				employee_page.client LIKE ? OR 
				employee_page.position LIKE ? OR 
				employee_page.employment_status LIKE ? OR 
				employee_page.start_shift_day LIKE ? OR 
				employee_page.end_shift_day LIKE ? OR 
				employee_page.shift_time_in LIKE ? OR 
				employee_page.shift_time_out LIKE ? OR 
				employee_page.lunch_break_start LIKE ? OR 
				employee_page.lunch_break_end LIKE ? OR 
				employee_page.sss_number LIKE ? OR 
				employee_page.sss_contribution LIKE ? OR 
				employee_page.pagibig_number LIKE ? OR 
				employee_page.pagibig_contribution LIKE ? OR 
				employee_page.philhealth_number LIKE ? OR 
				employee_page.philhealth_contribution LIKE ? OR 
				employee_page.tin_number LIKE ? OR 
				employee_page.tax_percentage LIKE ?
			)";
			$search_params = array(
				"%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%","%$text%"
			);
			//setting search conditions
			$db->where($search_condition, $search_params);
			 //template to use when ajax search
			$this->view->search_template = "employee_page/search.php";
		}
		if(!empty($request->orderby)){
			$orderby = $request->orderby;
			$ordertype = (!empty($request->ordertype) ? $request->ordertype : ORDER_TYPE);
			$db->orderBy($orderby, $ordertype);
		}
		else{
			$db->orderBy("employee_page.id", ORDER_TYPE);
		}
		if($fieldname){
			$db->where($fieldname , $fieldvalue); //filter by a single field name
		}
		$tc = $db->withTotalCount();
		$records = $db->get($tablename, $pagination, $fields);
		$records_count = count($records);
		$total_records = intval($tc->totalCount);
		$page_limit = $pagination[1];
		$total_pages = ceil($total_records / $page_limit);
		$data = new stdClass;
		$data->records = $records;
		$data->record_count = $records_count;
		$data->total_records = $total_records;
		$data->total_page = $total_pages;
		if($db->getLastError()){
			$this->set_page_error();
		}
		$page_title = $this->view->page_title = "Employee Page";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		$this->render_view("employee_page/list.php", $data); //render the full page
	}
	/**
     * View record detail 
	 * @param $rec_id (select record by table primary key) 
     * @param $value value (select record by value of field name(rec_id))
     * @return BaseView
     */
	function view($rec_id = null, $value = null){
		$request = $this->request;
		$db = $this->GetModel();
		$rec_id = $this->rec_id = urldecode($rec_id);
		$tablename = $this->tablename;
		$fields = array("id", 
			"first_name", 
			"middle_name", 
			"last_name", 
			"address", 
			"password", 
			"birthdate", 
			"contact_number", 
			"civil_status", 
			"email", 
			"work_email", 
			"employee_type", 
			"start_date", 
			"monthly_salary", 
			"account_bonus", 
			"client", 
			"position", 
			"employment_status", 
			"start_shift_day", 
			"end_shift_day", 
			"shift_time_in", 
			"shift_time_out", 
			"lunch_break_start", 
			"sss_number", 
			"sss_contribution", 
			"pagibig_number", 
			"pagibig_contribution", 
			"philhealth_number", 
			"philhealth_contribution", 
			"tin_number", 
			"tax_percentage", 
			"lunch_break_end");
		if($value){
			$db->where($rec_id, urldecode($value)); //select record based on field name
		}
		else{
			$db->where("employee_page.id", $rec_id);; //select record based on primary key
		}
		$record = $db->getOne($tablename, $fields );
		if($record){
			$page_title = $this->view->page_title = "View  Employee Page";
		$this->view->report_filename = date('Y-m-d') . '-' . $page_title;
		$this->view->report_title = $page_title;
		$this->view->report_layout = "report_layout.php";
		$this->view->report_paper_size = "A4";
		$this->view->report_orientation = "portrait";
		}
		else{
			if($db->getLastError()){
				$this->set_page_error();
			}
			else{
				$this->set_page_error("No record found");
			}
		}
		return $this->render_view("employee_page/view.php", $record);
	}
	/**
     * Insert new record to the database table
	 * @param $formdata array() from $_POST
     * @return BaseView
     */
	function add($formdata = null){
		if($formdata){
			$db = $this->GetModel();
			$tablename = $this->tablename;
			$request = $this->request;
			//fillable fields
			$fields = $this->fields = array("first_name","middle_name","last_name","address","password","birthdate","contact_number","civil_status","email","work_email","employee_type","start_date","monthly_salary","account_bonus","client","position","employment_status","start_shift_day","end_shift_day","shift_time_in","shift_time_out","lunch_break_start","lunch_break_end","sss_number","sss_contribution","pagibig_number","pagibig_contribution","philhealth_number","philhealth_contribution","tin_number","tax_percentage");
			$postdata = $this->format_request_data($formdata);
			$cpassword = $postdata['confirm_password'];
			$password = $postdata['password'];
			if($cpassword != $password){
				$this->view->page_error[] = "Your password confirmation is not consistent";
			}
			$this->rules_array = array(
				'first_name' => 'required',
				'middle_name' => 'required',
				'last_name' => 'required',
				'address' => 'required',
				'password' => 'required',
				'birthdate' => 'required',
				'contact_number' => 'required|numeric',
				'civil_status' => 'required',
				'email' => 'required|valid_email',
				'work_email' => 'required|valid_email',
				'employee_type' => 'required',
				'start_date' => 'required',
				'monthly_salary' => 'required',
				'account_bonus' => 'required',
				'client' => 'required',
				'position' => 'required',
				'employment_status' => 'required',
				'start_shift_day' => 'required',
				'end_shift_day' => 'required',
				'shift_time_in' => 'required',
				'shift_time_out' => 'required',
				'lunch_break_start' => 'required',
				'lunch_break_end' => 'required',
				'sss_number' => 'required',
				'sss_contribution' => 'required',
				'pagibig_number' => 'required',
				'pagibig_contribution' => 'required',
				'philhealth_number' => 'required',
				'philhealth_contribution' => 'required',
				'tin_number' => 'required',
				'tax_percentage' => 'required',
			);
			$this->sanitize_array = array(
				'first_name' => 'sanitize_string',
				'middle_name' => 'sanitize_string',
				'last_name' => 'sanitize_string',
				'address' => 'sanitize_string',
				'birthdate' => 'sanitize_string',
				'contact_number' => 'sanitize_string',
				'civil_status' => 'sanitize_string',
				'email' => 'sanitize_string',
				'work_email' => 'sanitize_string',
				'employee_type' => 'sanitize_string',
				'start_date' => 'sanitize_string',
				'monthly_salary' => 'sanitize_string',
				'account_bonus' => 'sanitize_string',
				'client' => 'sanitize_string',
				'position' => 'sanitize_string',
				'employment_status' => 'sanitize_string',
				'start_shift_day' => 'sanitize_string',
				'end_shift_day' => 'sanitize_string',
				'shift_time_in' => 'sanitize_string',
				'shift_time_out' => 'sanitize_string',
				'lunch_break_start' => 'sanitize_string',
				'lunch_break_end' => 'sanitize_string',
				'sss_number' => 'sanitize_string',
				'sss_contribution' => 'sanitize_string',
				'pagibig_number' => 'sanitize_string',
				'pagibig_contribution' => 'sanitize_string',
				'philhealth_number' => 'sanitize_string',
				'philhealth_contribution' => 'sanitize_string',
				'tin_number' => 'sanitize_string',
				'tax_percentage' => 'sanitize_string',
			);
			$this->filter_vals = true; //set whether to remove empty fields
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$password_text = $modeldata['password'];
			//update modeldata with the password hash
			$modeldata['password'] = $this->modeldata['password'] = password_hash($password_text , PASSWORD_DEFAULT);
			if($this->validated()){
				$rec_id = $this->rec_id = $db->insert($tablename, $modeldata);
				if($rec_id){
					$this->set_flash_msg("Record added successfully", "success");
					return	$this->redirect("employee_page");
				}
				else{
					$this->set_page_error();
				}
			}
		}
		$page_title = $this->view->page_title = "Add New Employee Page";
		$this->render_view("employee_page/add.php");
	}
	/**
     * Update table record with formdata
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function edit($rec_id = null, $formdata = null){
		$request = $this->request;
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		 //editable fields
		$fields = $this->fields = array("id","first_name","middle_name","last_name","address","password","birthdate","contact_number","civil_status","email","work_email","employee_type","start_date","monthly_salary","account_bonus","client","position","employment_status","start_shift_day","end_shift_day","shift_time_in","shift_time_out","lunch_break_start","lunch_break_end","sss_number","sss_contribution","pagibig_number","pagibig_contribution","philhealth_number","philhealth_contribution","tin_number","tax_percentage");
		if($formdata){
			$postdata = $this->format_request_data($formdata);
			$cpassword = $postdata['confirm_password'];
			$password = $postdata['password'];
			if($cpassword != $password){
				$this->view->page_error[] = "Your password confirmation is not consistent";
			}
			$this->rules_array = array(
				'first_name' => 'required',
				'middle_name' => 'required',
				'last_name' => 'required',
				'address' => 'required',
				'password' => 'required',
				'birthdate' => 'required',
				'contact_number' => 'required|numeric',
				'civil_status' => 'required',
				'email' => 'required|valid_email',
				'work_email' => 'required|valid_email',
				'employee_type' => 'required',
				'start_date' => 'required',
				'monthly_salary' => 'required',
				'account_bonus' => 'required',
				'client' => 'required',
				'position' => 'required',
				'employment_status' => 'required',
				'start_shift_day' => 'required',
				'end_shift_day' => 'required',
				'shift_time_in' => 'required',
				'shift_time_out' => 'required',
				'lunch_break_start' => 'required',
				'lunch_break_end' => 'required',
				'sss_number' => 'required',
				'sss_contribution' => 'required',
				'pagibig_number' => 'required',
				'pagibig_contribution' => 'required',
				'philhealth_number' => 'required',
				'philhealth_contribution' => 'required',
				'tin_number' => 'required',
				'tax_percentage' => 'required',
			);
			$this->sanitize_array = array(
				'first_name' => 'sanitize_string',
				'middle_name' => 'sanitize_string',
				'last_name' => 'sanitize_string',
				'address' => 'sanitize_string',
				'birthdate' => 'sanitize_string',
				'contact_number' => 'sanitize_string',
				'civil_status' => 'sanitize_string',
				'email' => 'sanitize_string',
				'work_email' => 'sanitize_string',
				'employee_type' => 'sanitize_string',
				'start_date' => 'sanitize_string',
				'monthly_salary' => 'sanitize_string',
				'account_bonus' => 'sanitize_string',
				'client' => 'sanitize_string',
				'position' => 'sanitize_string',
				'employment_status' => 'sanitize_string',
				'start_shift_day' => 'sanitize_string',
				'end_shift_day' => 'sanitize_string',
				'shift_time_in' => 'sanitize_string',
				'shift_time_out' => 'sanitize_string',
				'lunch_break_start' => 'sanitize_string',
				'lunch_break_end' => 'sanitize_string',
				'sss_number' => 'sanitize_string',
				'sss_contribution' => 'sanitize_string',
				'pagibig_number' => 'sanitize_string',
				'pagibig_contribution' => 'sanitize_string',
				'philhealth_number' => 'sanitize_string',
				'philhealth_contribution' => 'sanitize_string',
				'tin_number' => 'sanitize_string',
				'tax_percentage' => 'sanitize_string',
			);
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			$password_text = $modeldata['password'];
			//update modeldata with the password hash
			$modeldata['password'] = $this->modeldata['password'] = password_hash($password_text , PASSWORD_DEFAULT);
			if($this->validated()){
				$db->where("employee_page.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount(); //number of affected rows. 0 = no record field updated
				if($bool && $numRows){
					$this->set_flash_msg("Record updated successfully", "success");
					return $this->redirect("employee_page");
				}
				else{
					if($db->getLastError()){
						$this->set_page_error();
					}
					elseif(!$numRows){
						//not an error, but no record was updated
						$page_error = "No record updated";
						$this->set_page_error($page_error);
						$this->set_flash_msg($page_error, "warning");
						return	$this->redirect("employee_page");
					}
				}
			}
		}
		$db->where("employee_page.id", $rec_id);;
		$data = $db->getOne($tablename, $fields);
		$page_title = $this->view->page_title = "Edit  Employee Page";
		if(!$data){
			$this->set_page_error();
		}
		return $this->render_view("employee_page/edit.php", $data);
	}
	/**
     * Update single field
	 * @param $rec_id (select record by table primary key)
	 * @param $formdata array() from $_POST
     * @return array
     */
	function editfield($rec_id = null, $formdata = null){
		$db = $this->GetModel();
		$this->rec_id = $rec_id;
		$tablename = $this->tablename;
		//editable fields
		$fields = $this->fields = array("id","first_name","middle_name","last_name","address","password","birthdate","contact_number","civil_status","email","work_email","employee_type","start_date","monthly_salary","account_bonus","client","position","employment_status","start_shift_day","end_shift_day","shift_time_in","shift_time_out","lunch_break_start","lunch_break_end","sss_number","sss_contribution","pagibig_number","pagibig_contribution","philhealth_number","philhealth_contribution","tin_number","tax_percentage");
		$page_error = null;
		if($formdata){
			$postdata = array();
			$fieldname = $formdata['name'];
			$fieldvalue = $formdata['value'];
			$postdata[$fieldname] = $fieldvalue;
			$postdata = $this->format_request_data($postdata);
			$this->rules_array = array(
				'first_name' => 'required',
				'middle_name' => 'required',
				'last_name' => 'required',
				'address' => 'required',
				'password' => 'required',
				'birthdate' => 'required',
				'contact_number' => 'required|numeric',
				'civil_status' => 'required',
				'email' => 'required|valid_email',
				'work_email' => 'required|valid_email',
				'employee_type' => 'required',
				'start_date' => 'required',
				'monthly_salary' => 'required',
				'account_bonus' => 'required',
				'client' => 'required',
				'position' => 'required',
				'employment_status' => 'required',
				'start_shift_day' => 'required',
				'end_shift_day' => 'required',
				'shift_time_in' => 'required',
				'shift_time_out' => 'required',
				'lunch_break_start' => 'required',
				'lunch_break_end' => 'required',
				'sss_number' => 'required',
				'sss_contribution' => 'required',
				'pagibig_number' => 'required',
				'pagibig_contribution' => 'required',
				'philhealth_number' => 'required',
				'philhealth_contribution' => 'required',
				'tin_number' => 'required',
				'tax_percentage' => 'required',
			);
			$this->sanitize_array = array(
				'first_name' => 'sanitize_string',
				'middle_name' => 'sanitize_string',
				'last_name' => 'sanitize_string',
				'address' => 'sanitize_string',
				'birthdate' => 'sanitize_string',
				'contact_number' => 'sanitize_string',
				'civil_status' => 'sanitize_string',
				'email' => 'sanitize_string',
				'work_email' => 'sanitize_string',
				'employee_type' => 'sanitize_string',
				'start_date' => 'sanitize_string',
				'monthly_salary' => 'sanitize_string',
				'account_bonus' => 'sanitize_string',
				'client' => 'sanitize_string',
				'position' => 'sanitize_string',
				'employment_status' => 'sanitize_string',
				'start_shift_day' => 'sanitize_string',
				'end_shift_day' => 'sanitize_string',
				'shift_time_in' => 'sanitize_string',
				'shift_time_out' => 'sanitize_string',
				'lunch_break_start' => 'sanitize_string',
				'lunch_break_end' => 'sanitize_string',
				'sss_number' => 'sanitize_string',
				'sss_contribution' => 'sanitize_string',
				'pagibig_number' => 'sanitize_string',
				'pagibig_contribution' => 'sanitize_string',
				'philhealth_number' => 'sanitize_string',
				'philhealth_contribution' => 'sanitize_string',
				'tin_number' => 'sanitize_string',
				'tax_percentage' => 'sanitize_string',
			);
			$this->filter_rules = true; //filter validation rules by excluding fields not in the formdata
			$modeldata = $this->modeldata = $this->validate_form($postdata);
			if($this->validated()){
				$db->where("employee_page.id", $rec_id);;
				$bool = $db->update($tablename, $modeldata);
				$numRows = $db->getRowCount();
				if($bool && $numRows){
					return render_json(
						array(
							'num_rows' =>$numRows,
							'rec_id' =>$rec_id,
						)
					);
				}
				else{
					if($db->getLastError()){
						$page_error = $db->getLastError();
					}
					elseif(!$numRows){
						$page_error = "No record updated";
					}
					render_error($page_error);
				}
			}
			else{
				render_error($this->view->page_error);
			}
		}
		return null;
	}
	/**
     * Delete record from the database
	 * Support multi delete by separating record id by comma.
     * @return BaseView
     */
	function delete($rec_id = null){
		Csrf::cross_check();
		$request = $this->request;
		$db = $this->GetModel();
		$tablename = $this->tablename;
		$this->rec_id = $rec_id;
		//form multiple delete, split record id separated by comma into array
		$arr_rec_id = array_map('trim', explode(",", $rec_id));
		$db->where("employee_page.id", $arr_rec_id, "in");
		$bool = $db->delete($tablename);
		if($bool){
			$this->set_flash_msg("Record deleted successfully", "success");
		}
		elseif($db->getLastError()){
			$page_error = $db->getLastError();
			$this->set_flash_msg($page_error, "danger");
		}
		return	$this->redirect("employee_page");
	}
}
