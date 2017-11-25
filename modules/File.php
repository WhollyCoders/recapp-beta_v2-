<?php 

    class File{
        public $connection;
        public $File_array;
        public $header;
// *** Class Constructor ***
        public function __construct($connection, $file_array)
        {
            $this->connection = $connection;
            $this->File_array = $file_array;
        }
// *** Check if file is a CSV FIle ***
        public function check_for_csv()
        {
            if($this->get_file_type() == 'csv')
            {
                return true;
            }else
            {
                return false;
            }
        }
// *** Create Contacts Table If it does NOT exist ***
        // public function create_table()
        // {
        //     $sql = "CREATE TABLE IF NOT EXISTS `whollycoder`.`table_contacts`(
        //         `contact_ID` INT NOT NULL AUTO_INCREMENT , 
        //         `contact_firstname` VARCHAR(50) NOT NULL , 
        //         `contact_lastname` VARCHAR(50) NOT NULL , 
        //         `contact_phone` VARCHAR(20) NOT NULL , 
        //         `contact_email` VARCHAR(100) NOT NULL , 
        //         `contact_date_added` DATETIME NOT NULL , 
        //         PRIMARY KEY (`contact_ID`)
        //         ) ENGINE = InnoDB;";
        //         // prewrap($sql);
        //     $result = $this->process_query($sql);
        // }
// *** Get File Name ***
        public function get_file_name()
        {
            return $this->File_array['file']['name'];
        }
// *** Get File Type ***
        public function get_file_type()
        {
            $filename = $this->get_file_name();
            $filename = explode(".", $filename);
            if($filename[1])
            {
                return $filename[1];
            }else
            {
                return false;
            }
        }
// *** Get Temporary File ***
        public function get_tmp_file()
        {
            return $this->File_array['file']['tmp_name'];
        }
// *** Import CSV Data ***
        public function import_csv()
        {
            if($this->check_for_csv())
            {
                
                $filename = $this->get_tmp_file();
                $handle = fopen($filename, 'r');
                // $this->create_table();
                $processed = false;
                while($data = fgetcsv($handle))
                {
                    if($processed == false)
                    {
                        $this->header = $data;
                        $processed = true;
                        continue;
                    }
                    $team_id               = trim(mysqli_real_escape_string($this->connection, $data[0]), "T");
                    $competitor_id         = ucfirst(mysqli_real_escape_string($this->connection, $data[1]));
                    $competitor_name       = competitor(ucfirst(mysqli_real_escape_string($this->connection, $data[2])));
                    $competition_id        = ucfirst(mysqli_real_escape_string($this->connection, $data[3]));
                    $competition_name      = ucfirst(mysqli_real_escape_string($this->connection, $data[4]));
                    $team_name             = ucfirst(mysqli_real_escape_string($this->connection, $data[5]));
                    $week                  = mysqli_real_escape_string($this->connection, $data[6]);
                    $weight                = mysqli_real_escape_string($this->connection, $data[7]);

                    $sql = "INSERT INTO weighins(
                        weigh_in_ID, 
                        weigh_in_competitor_ID, 
                        weigh_in_competitor_name, 
                        weigh_in_competition_ID,
                        weigh_in_competition_name, 
                        weigh_in_team_ID, 
                        weigh_in_team_name, 
                        weigh_in_week,
                        weigh_in_weight,
                        weigh_in_date_entered
                    ) values(
                        NULL,
                        '$competitor_id',
                        '$competitor_name',
                        '$competition_id',
                        '$competition_name',
                        '$team_id',
                        '$team_name',
                        '$week',
                        '$weight',
                        CURRENT_TIMESTAMP
                    );";

                            // echo('<pre>');
                            // print_r($sql);
                            // echo('</pre>');

                    $result = $this->process_query($sql);
                }
                echo('Import Complete...<br>');
                // prewrap($this->header);
            }else
            {
                echo('Unable to upload file...<br>');
            }
        }
// *** Process Query Method ***
        public function process_query($sql)
        {
            return $result = mysqli_query($this->connection, $sql);
        }
    }

?>