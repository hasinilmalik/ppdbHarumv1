<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Setting_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = base_url() . 'setting/index.html?q=' . urlencode($q);
            $config['first_url'] = base_url() . 'setting/index.html?q=' . urlencode($q);
        } else {
            $config['base_url'] = base_url() . 'setting/index.html';
            $config['first_url'] = base_url() . 'setting/index.html';
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Setting_model->total_rows($q);
        $setting = $this->Setting_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'setting_data' => $setting,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
            'content' => 'setting/setting_list',
        );
        $this->load->view('layout', $data);
    }

    public function read($id) 
    {
        $row = $this->Setting_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'nama_setting' => $row->nama_setting,
		'nilai' => $row->nilai,
	    
'content'=>'setting/setting_read');

            $this->load->view('layout', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('setting'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('setting/create_action'),
	    'id' => set_value('id'),
	    'nama_setting' => set_value('nama_setting'),
	    'nilai' => set_value('nilai'),
	
'content'=>'setting/setting_form');
        $this->load->view('layout', $data);
    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'nama_setting' => $this->input->post('nama_setting',TRUE),
		'nilai' => $this->input->post('nilai',TRUE),
	    );

            $this->Setting_model->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('setting'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Setting_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('setting/update_action'),
		'id' => set_value('id', $row->id),
		'nama_setting' => set_value('nama_setting', $row->nama_setting),
		'nilai' => set_value('nilai', $row->nilai),
	    
'content'=>'setting/setting_form');
            $this->load->view('layout', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('setting'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'nama_setting' => $this->input->post('nama_setting',TRUE),
		'nilai' => $this->input->post('nilai',TRUE),
	    );

            $this->Setting_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('setting'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Setting_model->get_by_id($id);

        if ($row) {
            $this->Setting_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('setting'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('setting'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nama_setting', 'nama setting', 'trim|required');
	$this->form_validation->set_rules('nilai', 'nilai', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Setting.php */
/* Location: ./application/controllers/Setting.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-11-05 05:50:04 */
/* http://harviacode.com */