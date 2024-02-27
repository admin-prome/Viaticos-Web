<?php

    function tieneAlerta($id){
        
        $sql = "SELECT * FROM alertas WHERE id_solicitud=" . $id . " AND alerta_vista=0";
        
        $rs = ejecutarConsulta($sql);
        
        if($rs){
            
            return true;
        }
        else{
            
            return false;
            
        }
        

    }
    
    function yaVioAlerta($id){
        
        
         $sql = "UPDATE alertas SET alerta_vista=1 WHERE id_solicitud=" . $id;
        
        $rs = ejecutarConsulta($sql);
        
        if($rs){
            
            return true;
        }
        else{
            
            return false;
            
        }
        
        
    }
    
      function aletasPendientes(){
          
        $sql = "SELECT * FROM alertas WHERE id_solicitud=" . $id . " AND alerta_vista=0";
        
        $rs = ejecutarConsulta($sql);
          
          
          
      }
    
    