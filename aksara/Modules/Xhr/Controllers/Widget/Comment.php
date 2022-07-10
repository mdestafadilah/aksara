<?php

namespace Aksara\Modules\Xhr\Controllers\Widget;

/**
 * XHR > Widget > Comment
 *
 * @author			Aby Dahana <abydahana@gmail.com>
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.4.0
 * @copyright		(c) 2022 - Aksara Laboratory
 */

class Comment extends \Aksara\Laboratory\Core
{
	private $_table									= 'comments';
	
	public function __construct()
	{
		parent::__construct();
		
		//$this->set_permission();
		$this->permission->must_ajax();
		$this->limit(null);
	}
	
	public function index()
	{
		if(service('request')->getPost('_token'))
		{
			return $this->_validate_form();
		}
		
		$this->set_title('comment')
		->set_icon('mdi mdi-comment-multiple')
		
		->set_relation
		(
			'user_id',
			'app__users.user_id',
			'{app__users.photo} {app__users.username} {app__users.first_name} {app__users.last_name}'
		)
		->where
		(
			array
			(
				'post_id'							=> service('request')->getGet('post_id'),
				'comment_type'						=> service('request')->getGet('type')
			)
		)
		->order_by('comment_id', 'DESC')
		
		->render($this->_table);
	}
	
	private function _validate_form()
	{
		if(!get_userdata('is_logged'))
		{
			return throw_exception(400, array('comments' => phrase('please_sign_in_to_comment')));
		}
		
		$this->form_validation->setRule('post_id', phrase('post'), 'required');
		$this->form_validation->setRule('comment_type', phrase('comment_type'), 'required');
		$this->form_validation->setRule('comments', phrase('comments'), 'required');
		
		if($this->form_validation->run(service('request')->getPost()) === false)
		{
			return throw_exception(400, $this->form_validation->getErrors());
		}
		
		$this->model->insert
		(
			$this->_table,
			array
			(
				'reply_id'							=> (service('request')->getPost('reply_id') ? service('request')->getPost('reply_id') : 0),
				'post_id'							=> service('request')->getPost('post_id'),
				'user_id'							=> get_userdata('user_id'),
				'comment_type'						=> service('request')->getPost('comment_type'),
				'comments'							=> htmlspecialchars(service('request')->getPost('comments')),
				'created_timestamp'					=> date('Y-m-d H:i:s'),
				'updated_timestamp'					=> date('Y-m-d H:i:s'),
				'anonymous'							=> (service('request')->getPost('anonymous') ? 1 : 0),
				'status'							=> 0
			)
		);
		
		$html										= '
			<div class="row mb-3">
				<div class="col-2 col-lg-1 pt-3">
					<img src="' . get_image('users', get_userdata('photo'), 'thumb') . '" class="img-fluid rounded-circle" />
				</div>
				<div class="col-10 col-lg-11">
					<div class="position-relative">
						<div class="dropdown position-absolute end-0">
							<button class="btn btn-link btn-sm dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="mdi mdi-format-list-checks"></i>
							</button>
							<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
								<li>
									<a class="dropdown-item" href="#">
										' . phrase('approve') . '
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="#">
										' . phrase('report') . '
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="#">
										' . phrase('hide') . '
									</a>
								</li>
							</ul>
						</div>
					</div>
					<div class="bg-light rounded-4 p-3 d-inline-block">
						<a href="' . base_url('user/' . get_userdata('username')) . '" class="--xhr">
							<b>
								' . get_userdata('first_name') . ' ' . get_userdata('last_name') . '
							</b>
						</a>
						<br />
						<div>
							' . htmlspecialchars(service('request')->getPost('comments')) . '
						</div>
					</div>
					<div class="ps-3 pe-3">
						<a href="' . current_page('like') . '" class="--xhr text-sm">
							<b>
								' . phrase('like') . '
							</b>
						</a>
						 &middot; 
						<a href="' . current_page('reply') . '" class="--xhr text-sm">
							<b>
								' . phrase('reply') . '
							</b>
						</a>
						 &middot; 
						<span class="text-muted text-sm">
							' . time_ago(date('Y-m-d H:i:s')) . '
						</span>
					</div>
				</div>
			</div>
		';
		
		return make_json
		(
			array
			(
				'html'								=> $html,
				'prepend_to'						=> '#comment-container'
			)
		);
	}
}
