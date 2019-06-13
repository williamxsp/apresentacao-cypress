<?php
use \SleekDB\SleekDB as SleekDB;

class Controller
{
    private $store;
    const TABLE = 'tarefas';
    const DATA_DIR = './db';
    public function __construct()
    {
        $this->store  = SleekDB::store('news', self::DATA_DIR);
    }

    public function init()
    {
        header("Content-Type: application/json");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        if (isset($_GET['seed'])) {
            $this->store->delete();
            $this->store->insert([
                'id' => uniqid(),
                'title' => 'Seed',
                'done' => false
            ]);
        }
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $this->get();
            case 'POST':
                return $this->post($_GET['id']);
            case 'PUT':
                return $this->put();
            case 'DELETE':
                return $this->delete($_GET['id']);
            default:
                return $this->get();
        }
    }

    public function get()
    {
        if (isset($_GET['id'])) {
            return $this->getById($_GET['id']);
        }
        return $this->getAll();
    }


    public function getAll()
    {
        $this->response($this->store->fetch());
    }

    public function getById($id)
    {
        $this->response($this->store->where('id', '=', $id)->fetch());
    }

    public function post($id)
    {
        $data = [
            'title' => $_POST['title'],
            'done' => filter_var($_POST['done'], FILTER_VALIDATE_BOOLEAN)
        ];

        $this->store->where('id', '=', $id)->update($data);
        $this->response($this->store->where('id', '=', $id)->fetch());
    }

    public function delete($id)
    {
        $this->store->where('id', '=', $id)->delete();
        $this->response($this->store->where('id', '=', $id)->fetch());
    }


    public function put()
    {
        parse_str(file_get_contents("php://input"), $_PUT);
        $data = [
            'id' => uniqid(),
            'title' => $_PUT['title'],
            'done' => filter_var($_PUT['done'], FILTER_VALIDATE_BOOLEAN)
        ];

        
        $this->store->insert($data);
        $this->response($this->store->where('id', '=', $data['id'])->fetch());
    }

    private function response($data)
    {
        echo json_encode($data);
        die();
    }
}
