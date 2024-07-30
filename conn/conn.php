<?php

class connec
{
    public $username="root";
    public $password="";
    public $server_name="localhost";
    public $db_name="cinema";

    public $conn;


    function __construct()
    {
        $this->conn=new mysqli($this->server_name,$this->username,$this->password,$this->db_name);
        if($this->conn->connect_error)
        {
            die("Connection Failed");
        }
        // else
        // {
        //     echo "connected";
        // }
    }

    function select_all($table_name)
    {      
        $sql = "SELECT * FROM $table_name";
        $result=$this->conn->query($sql);
       
        
        return $result;
    }
    
    function select_by_query($query)
    {
        $result=$this->conn->query($query);
        return $result;
    }


    function select_show_dt()
    {      
        $sql="SELECT cinema.show.id, cinema.show.show_date, cinema.show.ticket_price, cinema.show.no_seat,cinema.show.movie_id, movie.name AS 'movie_name', show_time.time, cinema.name FROM cinema.show, movie,show_time, cinema where cinema.show.movie_id=movie.id AND cinema.show.show_time_id =show_time.id AND cinema.show.cinema_id=cinema.id;";
        $result=$this->conn->query($sql);
       
        
        return $result;
    }


    function select_movie($table_name,$date)
    {   if($date=="commingsoon")
        {
            $sql = "SELECT * FROM $table_name Where rel_date > now()";
            $result=$this->conn->query($sql);
            return $result;
        }
        else
        {
            $sql = "SELECT * FROM $table_name Where rel_date < now()";
            $result=$this->conn->query($sql);
            return $result;
        }   
       
    }

    function select($table_name,$id)
    {      
        $sql = "SELECT * FROM $table_name where id=$id";
        $result=$this->conn->query($sql);
        return  $result;
    }

    
    function select_result($result)
    {      
        $sql = "SELECT * FROM $table_name where id=$id";
        $result=$this->conn->query($sql);
        return  $result;
    }

    function select_login($table_name,$email)
    {      
        $sql = "SELECT * FROM $table_name where email='$email'";
        $result=$this->conn->query($sql);
        return  $result;
    }


    function insert($query,$msg)
    { 
        if($this->conn->query($query)===TRUE)
        {
             echo '<script> alert("'.$msg.'");</script>' ;
                //echo "inserted";
        }
        else
        {
             echo '<script> alert("'.$this->conn->error.'");</script>' ;
               // echo $this->conn->error;
        }
    }


    function insert_lastid($query)
    {
        $last_id=0;
        if($this->conn->query($query)===TRUE)
        {
            $last_id=$this->conn->insert_id;
        }
        else
        {
             echo '<script> alert("'.$this->conn->error.'");</script>' ;  
        }
        return $last_id;
    }
    
    
     function update($query,$msg)
    { 
        if($this->conn->query($query)===TRUE)
        {
             echo '<script> alert("'.$msg.'");</script>' ;
                //echo "inserted";
        }
        else
        {
             echo '<script> alert("'.$this->conn->error.'");</script>' ;
               // echo $this->conn->error;
        }
    }
    
    function delete($query,$msg)
    {
        
        if($this->conn->query($query)===TRUE)
        {
             echo '<script> alert("'.$msg.'");</script>' ;
        }
        
         else
        {
             echo '<script> alert("'.$this->conn->error.'");</script>' ;
               // echo $this->conn->error;
        }
    }

}

?>