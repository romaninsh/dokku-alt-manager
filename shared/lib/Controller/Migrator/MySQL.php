<?php
/**
 * Implements a generic migration controller.
 */
class Controller_Migrator_MySQL extends AbstractController {

    public $db=null;
    function migrate(){
        // find migrations

        if(is_null($this->db))$this->db=$this->app->db;
        if(!$this->db)$this->db = $this->app->dbConnect();

        $folders = $this->app->pathfinder->search('dbupdates');

        $this->db->dsql()->expr('create table if not exists `[table]` '.
            '(id int not null primary key auto_increment, name varchar(255), unique key(name), status enum("ok","fail"))',
            ['table'=>'_db_update'])->execute();

        foreach($folders as $dir){

            $files = scandir($dir);
            sort($files);
            foreach($files as $name) {
                if(strtolower(substr($name,-4))!='.sql')continue;

                $q = $this->db->dsql()
                    ->table('_db_update')
                    ->where('name',strtolower($name))
                    ->field('status');

                if($q->getOne()==='ok')continue;

                echo "migrating $name...\n";

                $migration=file_get_contents($filename);

                $q->set('name',strtolower($name));
                try {
                    $this->db->dbh->exec($migration);
                    $q->set('status','ok')->replace();
                }catch(Exception $e){
                    $q->set('status','fail')->replace();
                    if(!$e instanceof BaseException){
                        $e = $this->exception()
                            ->addMoreInfo('Original error', $e->getMessage());
                    }
                    throw $e->addMoreInfo('file',$name);
                }
            }
        }
    }

}
