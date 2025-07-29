<?php
class UserController extends Controller {

    private $data = [];
    private $userModel;
                   
    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }
                    
    public function index() {
        // lấy các request params
        $request = new Request();
        $dataFields = $request->getFields();

        $keyword = $dataFields['keyword'] ?? '';
        $groups = $this->userModel->getGroups();
        $status = $this->userModel->getStatus();

        // thêm điều kiện lọc
        $condition = [];
        if (!empty($dataFields['group'])) {
            $condition['group_id'] = $dataFields['group'];
        }
        if (!empty($dataFields['status'])) {
            $condition['status'] = $dataFields['status'];
        }
        if (!empty($dataFields['keyword'])) {
            $condition['keyword'] = $dataFields['keyword'];
        }
        // Lấy danh sách người dùng
        $users = $this->userModel->getListUser($condition);
        
        $this->data['params']['users'] = $users;

        $this->data['params']['page_title'] = 'Quản lý người dùng';
        $this->data['params']['dataFields'] = $dataFields;
        $this->data['params']['groups'] = $groups;
        $this->data['params']['status'] = $status;
        $this->data['params']['keyword'] = $keyword;

        $this->data['action'] = Session::flash('action');
        $this->data['status'] = Session::flash('status');
        $this->data['icon'] = Session::flash('icon');
        $this->data['message'] = Session::flash('message');

        $this->data['content'] = 'users/index';
        $this->data['page_title'] = 'Quản lý người dùng';
        // Render ra view
        $this->render('layouts/client_layouts', $this->data);         
    }

    public function create() {
        $this->data['page_title'] = 'Thêm mới người dùng';
        $this->data['params']['page_title'] = 'Thêm mới người dùng';
        $this->data['params']['groups'] = $this->userModel->getGroups();
        $this->data['params']['status'] = $this->userModel->getStatus();

        $this->data['action'] = Session::flash('action');
        $this->data['status'] = Session::flash('status');
        $this->data['icon'] = Session::flash('icon');
        $this->data['message'] = Session::flash('message');

        $this->data['content'] = 'users/create';
        // Render ra view
        $this->render('layouts/client_layouts', $this->data);
    }

    public function store() {
        $request = new Request();
        if ($request->isPost()) {
            $dataFields = $request->getFields();
            // Validate dữ liệu
            $request->rules([
                'name' => ['required', 'min:5', 'max:30'],
                'email' => ['required', 'email', 'min:8', 'unique:users,email'],
                'password' => ['required', 'min:8'],
                'confirm_password' => ['required', 'min:8', 'match:password'],
                'group_id' => [
                    // callback
                    function ($attribute, $value, $fail) {
                        if (!in_array($value, array_column($this->userModel->getGroups(), 'id'))) {
                            $fail('Group không tồn tại');
                        }
                    }
                ],
                'status' => [
                    // callback
                    function ($attribute, $value, $fail) {
                        if (!in_array($value, array_keys($this->userModel->getStatus()))) {
                            $fail('Trạng thái không hợp lệ');
                        }
                    }
                ]
            ]);
            $request->message([
                'name.required' => 'Tên không được để trống',
                'name.min' => 'Tên phải phải có ít nhất 5 ký tự',
                'name.max' => 'Tên phải nhỏ hơn 30 ký tự',
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'email.min' => 'Email phải phải có ít nhất 8 ký tự',
                'email.unique' => 'Email đã tồn tại',
                'password.required' => 'Mật khẩu không được để trống',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'confirm_password.required' => 'Mật khẩu xác nhận không được để trống',
                'confirm_password.min' => 'Mật khẩu xác nhận phải phải có ít nhất 8 ký tự',
                'confirm_password.match' => 'Mật khẩu xác nhận không trùng khớp',
            ]);
            // Validate dữ liệu
            $validate = $request->validate();

            if (!$validate) {
                // Thông báo lỗi
                $this->flashMessage('Thêm người dùng', 'status', 'error', 'Có lỗi xảy ra trong quá trình thêm người dùng');
                return redirect('/users/create');
            }

            // Lưu người dùng mới
            $password = Hash::make($dataFields['password']);

            $userData = [
                'name' => $dataFields['name'],
                'email' => $dataFields['email'],
                'password' => $password,
                'group_id' => $dataFields['group_id'],
                'status' => $dataFields['status'],
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $userid = $this->userModel->insert($userData);

            if ($userid) {
                // Thông báo thành công
                $this->flashMessage('Thêm người dùng', 'success', 'success', 'Thêm người dùng thành công!');
                return redirect('/users');
            } else {
                // Thông báo lỗi
                $this->flashMessage('Thêm người dùng', 'error', 'error', 'Có lỗi xảy ra trong quá trình thêm người dùng');
                return redirect('/users/create');
            }
        }
        return redirect('/users/create');
    }

    public function edit($id) {

        // Kiểm tra xem người dùng có tồn tại hay không
        $user = $this->userModel->find($id);
        if (!$user) {
            // Thông báo lỗi nếu người dùng không tồn tại
            $this->flashMessage('Sửa người dùng', 'error', 'error', 'Người dùng không tồn tại');
            return redirect('/users');
        }

        $this->data['page_title'] = 'Sửa người dùng';
        $this->data['params']['page_title'] = 'Sửa người dùng';
        $this->data['params']['groups'] = $this->userModel->getGroups();
        $this->data['params']['status'] = $this->userModel->getStatus();
        $this->data['params']['user'] = $user;

        $this->data['action'] = Session::flash('action');
        $this->data['status'] = Session::flash('status');
        $this->data['icon'] = Session::flash('icon');
        $this->data['message'] = Session::flash('message');

        $this->data['content'] = 'users/edit';
        // Render ra view
        $this->render('layouts/client_layouts', $this->data);
    }

    public function update($id) {
        $request = new Request();
        if ($request->isPost()) {
            $dataFields = $request->getFields();
            // Validate dữ liệu
            $request->rules([
                'name' => ['required', 'min:5', 'max:30'],
                'email' => ['required', 'email', 'min:8', 'unique:users,email,' . $id],
                'password' => ['required', 'min:8'],
                'confirm_password' => ['required', 'min:8', 'match:password'],
                'group_id' => [
                    // callback
                    function ($attribute, $value, $fail) {
                        if (!in_array($value, array_column($this->userModel->getGroups(), 'id'))) {
                            $fail('Group không tồn tại');
                        }
                    }
                ],
                'status' => [
                    // callback
                    function ($attribute, $value, $fail) {
                        if (!in_array($value, array_keys($this->userModel->getStatus()))) {
                            $fail('Trạng thái không hợp lệ');
                        }
                    }
                ]
            ]);
            $request->message([
                'name.required' => 'Tên không được để trống',
                'name.min' => 'Tên phải phải có ít nhất 5 ký tự',
                'name.max' => 'Tên phải nhỏ hơn 30 ký tự',
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'email.min' => 'Email phải phải có ít nhất 8 ký tự',
                'email.unique' => 'Email đã tồn tại',
                'password.required' => 'Mật khẩu không được để trống',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'confirm_password.required' => 'Mật khẩu xác nhận không được để trống',
                'confirm_password.min' => 'Mật khẩu xác nhận phải phải có ít nhất 8 ký tự',
                'confirm_password.match' => 'Mật khẩu xác nhận không trùng khớp',
            ]);
            // Validate dữ liệu
            $validate = $request->validate();

            if (!$validate) {
                // Thông báo lỗi
                $this->flashMessage('Sửa người dùng', 'status', 'error', 'Có lỗi xảy ra trong quá trình sửa người dùng');
                return redirect('/users/edit/' . $id);
            }

            // Lưu thông tin đã sửa
            $password = Hash::make($dataFields['password']);
            $userData = [
                'name' => $dataFields['name'],
                'email' => $dataFields['email'],
                'password' => $password,
                'group_id' => $dataFields['group_id'],
                'status' => $dataFields['status'],
                'update_at' => date('Y-m-d H:i:s'),
            ];
            $result = $this->userModel->updateUser($id, $userData);

            if ($result) {
                // Thông báo thành công
                $this->flashMessage('Sửa thông tin người dùng', 'success', 'success', 'Sửa thông tin người dùng thành công!');
                return redirect('/users');
            } else {
                // Thông báo lỗi
                $this->flashMessage('Sửa thông tin người dùng', 'error', 'error', 'Có lỗi xảy ra trong quá trình sửa thông tin người dùng');
                return redirect('/users/create');
            }
        }
        return redirect('/users/edit/' . $id);
    }

    public function delete($id) {
        // Kiểm tra xem người dùng có tồn tại hay không
        $user = $this->userModel->find($id);
        if (!$user) {
            // Thông báo lỗi nếu người dùng không tồn tại
            $this->flashMessage('Xóa người dùng', 'error', 'error', 'Người dùng không tồn tại');
            return redirect('/users');
        }

        // Xóa người dùng
        $this->userModel->deleteUser($id);
        // Thông báo thành công
        $this->flashMessage('Xóa người dùng', 'success', 'success', 'Xóa người dùng thành công!');
        return redirect('/users');
    }

    public function deleteMany() {
        $request = new Request();
        $dataFields = $request->getFields();

        if (!empty($dataFields['ids'])) {
            // Kiểm tra nếu ids là một chuỗi thì chuyển đổi thành mảng
            if (is_string($dataFields['ids'])) {
                $ids = trim($dataFields['ids']);
                $ids = explode(',', $ids);
            } else {
                $ids = $dataFields['ids'];
            }

            // Chuyển đổi các giá trị trong mảng thành số nguyên
            $ids = array_map('intval', $ids);
            // Loại bỏ các giá trị trùng lặp và không hợp lệ
            $ids = array_filter(array_unique(($ids))); 
            // Kiểm tra nếu mảng ids rỗng
            if (empty($ids)) {
                $this->flashMessage('Xóa danh sách người dùng', 'status', 'error', 'Không có dữ liệu nào được chọn để xóa');
                redirect('/users');
            }

            // Xóa nhiều người dùng
            $this->userModel->deleteManyUsers($ids);
            // Thông báo thành công
            $this->flashMessage('Xóa danh sách người dùng', 'success', 'success', 'Xóa dữ liệu thành công!');
            redirect('/users');
        } else {
            // Thông báo lỗi nếu không có người dùng nào được chọn
            $this->flashMessage('Xóa danh sách người dùng','error', 'error', 'Không có dữ liệu nào được chọn để xóa!');
            redirect('/users');
        }

    }

    
}