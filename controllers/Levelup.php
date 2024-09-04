<?php

use MX\MX_Controller;

class levelup extends MX_Controller
{
    private $characters;
    private $total;
    private $Items;
    private $count;
    public function __construct()
    {
        parent::__construct();

        $this->user->userArea();
        //Load the Config
        $this->load->config('levelup');
        $this->load->model('data_model');
    }
    private function init()
    {
        $this->characters = $this->user->getCharacters($this->user->getId());

        foreach ($this->characters as $realm_key => $realm) {
            if (is_array($realm['characters'])) {
                foreach ($realm['characters'] as $character_key => $character) {
                    $this->characters[$realm_key]['characters'][$character_key]['avatar'] = $this->realms->formatAvatarPath($character);
                }
            }

            $this->count =$this->data_model->CountTableItems($realm['realmId']);
            $this->Items =$this->data_model->GetTableRealm($realm['realmId']);

        }
        $this->total = 0;
        foreach ($this->characters as $realm) {
            if ($realm['characters']) {
                $this->total += count($realm['characters']);
            }
        }
    }
    public function index()
    {
        $this->init();
        $this->template->setTitle("Character LevelUp");

        clientLang('cant_afford', 'levelup');
        clientLang('purchase', 'levelup');
        clientLang('want_to_buy', 'levelup');
        clientLang('processing', 'levelup');
        clientLang('no_realm_selected', 'levelup');
        clientLang('no_char_selected', 'levelup');

        //Load the content
        $content_data = [
            "characters" => $this->characters,
            "url" => $this->template->page_url,
            "total" => $this->total,
            "dp" => $this->user->getDp(),
            "service_cost" => $this->config->item("price"),
            'maxlevel' => $this->config->item("max_level"),
            'description' => $this->config->item("description"),
            'prices' => $this->config->item("price_lvl"),
            "config" => $this->config,
            "this" =>$this,
            "count" => $this->count,
            "Items" => $this->Items

        ];
        $page_content = $this->template->loadPage("levelup.tpl", $content_data);
        //Load the page
        $page_data = array(
            "module" => "default",
            "headline" => "Character Levelup",
            "content" => $page_content
        );

        $page = $this->template->loadPage("page.tpl", $page_data);

        $this->template->view($page, "modules/levelup/css/levelup.css", "modules/levelup/js/levelup.js");
    }
    public function submit()
    {
        $characterGuid = $this->input->post('guid');
        $realmId = $this->input->post('realm');
        $price = $this->input->post('price');
        $prices = $this->config->item("price_lvl");

        $levelup = array_search($price, (array)$prices);




        // Make sure the realm actually supports console commands
        if (!$this->realms->getRealm($realmId)->getEmulator()->hasConsole()) {
            die(lang("not_support", "levelup"));
        }

        if ($characterGuid && $realmId) {
            $realmConnection = $this->realms->getRealm($realmId)->getCharacters();
            $realmConnection->connect();

            // Make sure the character exists
            if (!$realmConnection->characterExists($characterGuid)) {
                die(lang("character_does_not_exist", "levelup"));
            }

            // Make sure the character belongs to this account
            if (!$realmConnection->characterBelongsToAccount($characterGuid, $this->user->getId())) {
                die(lang("character_does_not_belong_account", "levelup"));
            }

            //Get the character name
            $CharacterName = $realmConnection->getNameByGuid($characterGuid);

            //Make sure we've got the name
            if (!$CharacterName) {
                die(lang("unable_resolve_character_name", "levelup"));
            }


           $check = $this->data_model->getLevelCharacter($characterGuid,$realmId,$levelup);

            if($check)
            {
                die(lang("not_authorized", "levelup"));
            }

            //Check if the user can afford the service
            if ($this->user->getDp() >= $price) {
                //Execute the command
                $this->realms->getRealm($realmId)->getEmulator()->sendCommand('.char level ' . $CharacterName . ' ' . $levelup);

                //Update Donation Points
                if ($price > 0) {
                    $this->user->setDp($this->user->getDp() - $price);
                }
                //// SendItems
                $cart = $this->data_model->GetTable();
                if($cart)
                {
                    $items = [];
                    // Load all items
                    foreach ($cart as $item) {
                        // Load the item
                        $items[$item['id']] = $this->data_model->getItem($item['id']);
                    }
                    $realmItems = [];
                    // Make sure all realms are online
                    foreach ($cart as $item) {
                        $realm = $this->realms->getRealm($items[$item['id']]['realm']);

                        // Create a realm item array if it doesn't exist
                        if (!isset($realmItems[$realm->getId()])) {
                            $realmItems[$realm->getId()] = [];
                        }
                    }
                    // Send all items
                    foreach ($cart as $item) {

                        if (!isset($characterGuid)) {
                            die("no character");
                        }

                        // Make sure the character exists
                        if (!$this->realms->getRealm($items[$item['id']]['realm'])->getCharacters()->characterExists($characterGuid)) {
                            die("character_exists");
                        }

                        // Make sure the character belongs to this account
                        if (!$this->realms->getRealm($items[$item['id']]['realm'])->getCharacters()->characterBelongsToAccount($characterGuid, $this->user->getId())) {
                            die("character_not_mine");
                        }

                        // Make sure the character array exists in the realm array
                        if (!isset($realmItems[$items[$item['id']]['realm']][$characterGuid])) {
                            $realmItems[$items[$item['id']]['realm']][$characterGuid] = array();
                        }

                        // Check for multiple items
                        if (preg_match("/,/", $items[$item['id']]['entryitem'])) {
                            // Split it per item ID
                            $temp['id'] = explode(",", $items[$item['id']]['entryitem']);
                            $temp['count'] = explode(",", $items[$item['id']]['count']);
                            // Loop through the item IDs
                            foreach ($temp['id'] as $key => $id) {
                                // Add them individually to the array
                                $itemCount = isset($temp['count'][$key]) ? $temp['count'][$key] : 1;
                                for($i = 0; $i < $itemCount; $i++) {
                                    array_push($realmItems[$items[$item['id']]['realm']][$characterGuid], array('id' => $id));
                                }
                            }
                        } else {
                            $itemCount = $items[$item['id']]['count'];
                            for($i = 0; $i < $itemCount; $i++) {
                                array_push($realmItems[$items[$item['id']]['realm']][$characterGuid], array('id' => $items[$item['id']]['entryitem']));
                            }
                        }
                    }
                    foreach ($realmItems as $realm => $characters)
                    {
                        foreach ($characters as $character => $items)
                        {
                            $this->realms->getRealm($realm)->getEmulator()->sendItems($CharacterName, $this->config->item("title_items"), $this->config->item("body_items"), $items);
                        }
                    }
                }
                if($this->config->item('gold_count') != 0 ) {
                    $this->data_model->SendMoney($realmId,$CharacterName);
                }
                ///
                //Successful
                die(lang("successfully", "levelup"));

            } else {
                die(lang("dont_enough_Donation_Points", "levelup"));
            }

        } else {
            die(lang("no_selected_service", "levelup"));
        }
    }

}
