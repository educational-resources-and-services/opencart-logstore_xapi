<?php
// admin/controller/extension/module/logstore_xapi.php
class ControllerExtensionModuleLogstoreXapi extends Controller {
    private $error = array();
 
    public function index() {
        $this->load->language('extension/module/html');
        $this->load->language('extension/module/logstore_xapi');
 
        $this->document->setTitle($this->language->get('heading_title'));
 
        $this->load->model('setting/setting');
 
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('logstore_xapi', $this->request->post);
                 
            $this->session->data['success'] = $this->language->get('text_success');
     
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL'));
        }
 
        $data['heading_title'] = $this->language->get('heading_title');
 
        $data['text_edit'] = $this->language->get('text_edit');
 
        $data['entry_endpoint'] = $this->language->get('entry_endpoint');
        $data['entry_username'] = $this->language->get('entry_username');
        $data['entry_password'] = $this->language->get('entry_password');
        $data['entry_max_batch_size'] = $this->language->get('entry_max_batch_size');
 
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
 
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
 
        if (isset($this->error['endpoint'])) {
            $data['error_endpoint'] = $this->error['endpoint'];
        } else {
            $data['error_endpoint'] = '';
        }
 
        if (isset($this->error['max_batch_size'])) {
            $data['error_max_batch_size'] = $this->error['max_batch_size'];
        } else {
            $data['error_max_batch_size'] = '';
        }
 
        $data['breadcrumbs'] = array();
 
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
        );
 
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL')
        );
 
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/logstore_xapi', 'token=' . $this->session->data['token'], 'SSL')
        );
 
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', 'SSL');
 
        if (isset($this->request->post['endpoint'])) {
            $data['endpoint'] = $this->request->post['endpoint'];
        } elseif(isset($this->config->get['endpoint'])) {
            $data['endpoint'] = $this->config->get['endpoint'];
        } else {
            $data['endpoint'] = 'test';
        }
 
        if (isset($this->request->post['username'])) {
            $data['username'] = $this->request->post['username'];
        } elseif(isset($this->config->get['username'])) {
            $data['username'] = $this->config->get['username'];
        } else {
            $data['username'] = '';
        }
 
        if (isset($this->request->post['password'])) {
            $data['password'] = $this->request->post['password'];
        } elseif(isset($this->config->get['password'])) {
            $data['password'] = $this->config->get['password'];
        } else {
            $data['password'] = '';
        }
 
        if (isset($this->request->post['max_batch_size'])) {
            $data['max_batch_size'] = $this->request->post['max_batch_size'];
        } elseif(isset($this->config->get['max_batch_size'])) {
            $data['max_batch_size'] = $this->config->get['max_batch_size'];
        } else {
            $data['max_batch_size'] = '';
        }
 
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
 
        $this->response->setOutput($this->load->view('extension/module/logstore_xapi.tpl', $data));
    }
 
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/logstore_xapi')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
 
        if ($this->request->post['endpoint'] !== '' && !filter_var($this->request->post['endpoint'], FILTER_VALIDATE_URL)) {
            $this->error['endpoint'] = $this->language->get('error_endpoint');
        }
 
        if ($this->request->post['max_batch_size'] !== '' && !is_numeric($this->request->post['max_batch_size'])) {
            $this->error['max_batch_size'] = $this->language->get('error_max_batch_size');
        }
 
        return !$this->error;
    }
}
?>