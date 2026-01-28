<?php

// ########################################################################
// DB REPORT
// ########################################################################
trait DBReport
{
	
        public function dbData()
    {
        $serverDetails = $this->serverDetails();

        if (in_array($serverDetails->SERVER_ADDR, $serverDetails->development_ip_list)) {

            return (object) (array(
                "dbserver" => "pgsql",
                "dbuser" => "dtprpusr",
                "dbpass" => 'KG0pF_!7dCLIB$U',
                "dbname" => "tndtp",
                "dbport" => "5500",
                "dbhost" => "10.163.75.68"
            ));

        } else if (in_array($serverDetails->SERVER_ADDR, $serverDetails->production_ip_list) || $this->is_cli()) {
           
            return (object) (array(
                "dbserver" => "pgsql",
                "dbuser" => "dtprpusr",
                "dbpass" => 'KG0pF_!7dCLIB$U',
                "dbname" => "tndtp",
                "dbport" => "5500",
                "dbhost" => "10.163.75.68"
            ));
        }
    }

   
}