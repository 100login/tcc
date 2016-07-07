<?php

class ViewDAO extends Database{
    
    public function lista(){
       $sql = "SELECT
                    v.viewname, v.viewowner, v.definition 
               FROM 
                    pg_catalog.pg_views v
               WHERE 
                    v.schemaname NOT IN ('pg_catalog', 'information_schema')";
       
       $recurso =  pg_query($this->dbc, $sql);
       $views = array();
       while ($regView = pg_fetch_assoc($recurso)) {
           $view = new View($regView['viewname']);
           $view->setDefinicao($regView['definition']);
           $view->setDono($regView['viewowner']);
           $views[] = $view;
       }
       
       return $views;
    }
}
