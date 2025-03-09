<?php

namespace Manager\Repositories;


class ScriptsRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getScript($name)
    {
        $this->con->bind('namee',$name);
        return $this->con->row('SELECT content from adgsoft_managersdm.scripts where name = :namee');
    }

    public function saveScript($name,$content)
    {
        return $this->con->query(
            'INSERT INTO adgsoft_managersdm.scripts(name,content) VALUES (:name,:content)
ON DUPLICATE KEY UPDATE content = :content2',
            ['content' => $content, 'content2' => $content,'name'=> $name]
            );
    }

}