<?php
use App\Config\Services;
use MX\MX_Controller;
class Admin extends MX_Controller
{
    public function __construct()
    {
        $this->load->library('administrator');
        $this->load->model('data_model');
        parent::__construct();
    }

    public function index()
    {
        // Prepare my data
        $data = array(
            'url' => $this->template->page_url,
            'items' => $this->data_model->GetTableAdmin(),
            'this'=>$this,
            'realms' => $this->realms->getRealms()
        );
        // Load my view
        $output = $this->template->loadPage("admin.tpl", $data);

        // Put my view in the main box with a headline
        $content = $this->administrator->box('Add Item', $output);

        // Output my content. The method accepts the same arguments as template->view
        $this->administrator->view($content, false, "modules/levelup/js/admin.js");
    }
    public function createGroup()
    {
        $data["entryitem"] = $this->input->post("ItemEntry");
        $data["count"] = $this->input->post("countitem");
        $data["active"] = $this->input->post("active");
        $data["realm"] =  $this->input->post("realm");
        $data["icon"] =  '';

        if(!$data['entryitem'])
        {
            die("The following fields can\'t be empty: [itemid]')");

        }
        if ($data["count"] == '' || empty($data["count"]) || is_null($data["count"])) {

            $data["count"] = 1;
        }
        if (preg_match("/,/", $data["entryitem"])) {

            $data["tooltip"] = 0;
            $data["quality"] = 4;
            if (!preg_match("/inv_.+/i", $data["icon"])) {
                $data["icon"] = "inv_misc_questionmark";
            }
        } else {
            $item_data = $this->realms->getRealm($data["realm"])->getWorld()->getItem($data["entryitem"]);
            if (!$item_data || empty($item_data) || is_null($item_data) || $item_data == 'empty') {
                die("Invalid item");
            }
            $data["name"] =  $item_data['name'];
            $data["tooltip"] = 1;
            $data["quality"] = $item_data['Quality'];

            if (!preg_match("/inv_.+/i", $data["icon"])) {
                $response = Services::curlrequest()->get($this->template->page_url . "icon/get/" . $data["realm"] . "/" . $data["entryitem"]);
                $data["icon"] = $response->getBody();
            }
            $Request= $this->data_model->addGroup($data) ;
            if($Request == '1')
            {
                die("1");
            }
            else
                die($Request);
        }
    }
    public function delete($id = false)
    {
        if(!$id || !is_numeric($id))
            die("Empty ID");
        if ($this->data_model->delete($id))

            die("1");
        else
            die("Error DB");
    }
}