<?php
class data_model extends CI_Model
{
    public function getItem($id)
    {
        $query = $this->db->query("SELECT * FROM levelup_items where active=1 and id=? ",array($id));
        if($query->getNumRows() > 0)
        {
            $result = $query->getResultArray();
            return $result[0];
        }
        else
        {
            return false;
        }
    }
    public function GetTableAdmin()
    {
        $query = $this->db->query("SELECT * FROM levelup_items ORDER BY `id` ASC");

        if($query && $query->getNumRows() > 0)
        {
            return  $query->getResultArray();
        }
        else
        {
            return false;
        }
    }
    public function CheckActive($id)
    {
        $query = $this->db->query("SELECT * FROM levelup_items where  id=? ",array($id));
        if($query->getNumRows() > 0)
        {
            $row = $query->getResultArray();
            $active =  $row[0]['active'];
            if($active)
                return 0;
            else
                return 1 ;
        }
        else
            return 0;
    }

    public function GetRealmName($realmid)
    {
        $query = $this->db->query("SELECT * FROM realms where  id =".$realmid);
        if($query->getNumRows() > 0)
        {
            $result=$query->getResultArray();
            return $result[0]['realmName'];
        }
        else
        {
            return false;
        }
    }
    public function GetTable()
    {
        $query = $this->db->query("SELECT * FROM levelup_items where active=1 ORDER BY `id` ASC");
        if($query && $query->getNumRows() > 0)
        {
            return  $query->getResultArray();
        }
        else
        {
            return false;
        }
    }
    public function GetTableRealm($realm)
    {
        $query = $this->db->query("SELECT * FROM levelup_items where active=1 and realm=? ORDER BY `id` ASC",array($realm));
        if($query && $query->getNumRows() > 0)
        {
            return  $query->getResultArray();
        }
        else
        {
            return false;
        }
    }
    public function CountTableItems($realm)
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM levelup_items where active=1 and realm=? ORDER BY `id` ASC",array($realm));

        if ($query && $query->getNumRows() > 0)
        {
            $results = $query->getResultArray();

            return (int)$results[0]['total'];
        }
        return false;
    }
    public function addGroup($data)
    {
        $query = $this->db->query("SELECT * FROM levelup_items WHERE entryitem=? LIMIT 1", array($data['entryitem']));
        if($query->getNumRows() > 0)
        {
            return 'This item is stored on the server';
        }
        else
        {
            $query2 =  $this->db->table('levelup_items')->insert($data);
            if($query2)
            {
                return '1';
            }
            else
            {
                return 'Error Insert Data';
            }
        }
    }
    public function getLevelCharacter ($guid,$realmid,$levelconfig)
    {
        $character_database = $this->realms->getRealm($realmid)->getCharacters();
        $character_database->connect();
        $query = $character_database->getConnection()->query("SELECT * FROM characters WHERE guid = ?", array($guid));
        if($query->getNumRows() > 0)
        {
            $result=$query->getResultArray();
            if($result[0]['level'] > $levelconfig)
            {
                return true;
            }
            else
                return false;
        }
        else
            return false;
    }
    public function delete($id)
    {
        $this->db->query("DELETE FROM levelup_items WHERE id = ?", [$id]);
    }

    public function SendMoney($realmid,$name) {

        $title = $this->config->item('title_gold');
        $body = $this->config->item('body_gold');
        $gold = $this->config->item('gold_count');
        if($gold != 0)
        {
            $Money =  $gold * 10000  ;
            $this->realms->getRealm($realmid)->getEmulator()->sendMoney($name, $title, $body, $Money);

        }
        return true;
    }
}