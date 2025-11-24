<?php
require_once __DIR__ . '/../commons/function.php';
require_once __DIR__ . '/../models/CustomerModel.php';

class CustomerController {
    public $model;
    public function __construct() { 
        $conn = get_connection();
        $this->model = new CustomerModel($conn);
    }

    public function index() {
        $customers = $this->model->getAll();
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/customers/list.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function create() {
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/customers/create.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model->insert($_POST['ho_ten'], $_POST['email'], $_POST['sdt'], $_POST['dia_chi']);
            header("Location: ?act=customers");
        }
    }

    public function edit() {
        $id = $_GET['id'];
        $customer = $this->model->getById($id);
        require_once __DIR__ . '/../views/layouts/header.php';
        require_once __DIR__ . '/../views/customers/edit.php';
        require_once __DIR__ . '/../views/layouts/footer.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model->update($_POST['id'], $_POST['ho_ten'], $_POST['email'], $_POST['sdt'], $_POST['dia_chi']);
            header("Location: ?act=customers");
        }
    }

    public function delete() {
        if(isset($_GET['id'])) {
            $this->model->delete($_GET['id']);
        }
        header("Location: ?act=customers");
    }
}
?>