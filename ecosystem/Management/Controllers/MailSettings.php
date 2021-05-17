<?php

namespace Ecosystem\Management\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Authentication\Models\MailClient;

class MailSettings extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Management\\Views\\settings\\';

	public function index()
	{
		$this->userLib->has_privilege_or_exception('view_user');

		$data['page_title'] = 'Mail Settings';
        $data['clients'] = (new MailClient())->findAll();
        return view($this->_setPagePath($this->viewPath, 'mail-clients'), $data);
	}

	public function setClient()
	{
		// create validation rules
		$rules = [
			'client'  => [
				'label'  => 'Mail Client',
				'rules' => 'required|integer',
			],
		];

		// set validation rules
		$this->validation->setRules($rules);
		// validate request
		if (! $this->validation->withRequest($this->request)->run()) { // fails
			if ($this->request->isAJAX()) { // if ajax request
				return $this->response->setJSON($this->validation->getErrors());    
			} else {
				return redirect()->back()->withInput()->with('error', 'Invalid details found!'); // return to sign up page
			}
		} else { // passes
			$client = (int) $this->request->getVar('client');

			$mc = new MailClient();
			try {
				if ($mc->update(null, ['is_active' => '0'])) {
					$result = $mc->update(['id' => $client], ['is_active' => '1']);
				}
			} catch (\ReflectionException $e) {
				//throw $e;
			}

			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result = true or false
			} else {
				if ($result) {
					$this->session->setFlashData('success', 'Mail Client Selected');
				} else {
					$this->session->setFlashData('error', 'Unable to Select Mail Client');
				}
			}
			return redirect()->to('/manage/mail/clients');
		}
	}

	public function templates()
	{
		$this->userLib->has_privilege_or_exception('view_user');

		$data['page_title'] = 'Mail Templates';
        $data['templates'] = service('mailTemplateLib')->get_templates();
        return view($this->_setPagePath($this->viewPath, 'mail-templates'), $data);
	}

	public function showTemplate($id = null)
	{
		$this->userLib->has_privilege_or_exception('edit_user');

		if ($id) {
			$template = service('mailTemplateLib')->find_template_by_id($id);
			if ($template) {
				$data['page_title'] = 'Edit Mail Template';
				$data['validation'] = $this->validation;
				$data['template'] = $template;
				return view($this->_setPagePath($this->viewPath, 'edit-template'), $data);
			}
		}
        return redirect()->to('/manage/templates')->with('error', 'Template not found');
	}

	public function editTemplate($id = null)
	{
		if ($id) {
			// create validation rules
			$rules = [
				'sender'  => [
                    'label'  => 'Sender Email',
                    'rules' => 'required|valid_email|min_length[3]',
                ],
                'sender_name' => [
                    'label'  => 'Sender Name',
                    'rules' => 'required|min_length[3]',
                ],
                'subject' => [
                    'label'  => 'Mail Template Subject',
                    'rules' => 'required|min_length[5]',
                ],
                'template_html' => [
                    'label'  => 'HTML Mail',
                    'rules' => 'required|min_length[5]',
                ],
                'template_text'  => [
                    'label'  => 'Text Mail',
                    'rules' => 'required|min_length[5]',
                ],
			];

			// set validation rules
			$this->validation->setRules($rules);
			// validate request
			if (! $this->validation->withRequest($this->request)->run()) { // fails
				if ($this->request->isAJAX()) { // if ajax request
					return $this->response->setJSON($this->validation->getErrors());    
				} else {
					return redirect()->back()->withInput()->with('error', 'Invalid details found!'); // return to sign up page
				}
			} else { // passes
				$data = [
                    'id' => $id,
                    'mail_from' => $this->request->getVar('sender'),
                    'from_name' => $this->request->getVar('sender_name'),
                    'subject' => $this->request->getVar('subject'),
                    'template_html' => $this->request->getVar('template_html'),
                    'template_text' => $this->request->getVar('template_text'),
                ];
				
				$result = service('mailTemplateLib')->update_template($data); // add role
				if ($this->request->isAJAX()) { // if request is ajax
					return $this->response->setJSON($result); // $result['error'] or $result['success']
				} else {
					if (isset($result['success'])) {
						$this->session->setFlashData('success', 'Template updated successfully');
					} else {
						$this->session->setFlashData('error', 'Unable to update Template');
					}
				}
				return redirect()->back();
			}
		}
	}

}
