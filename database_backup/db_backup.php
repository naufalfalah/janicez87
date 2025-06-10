<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);

class DBbackup
{
    public $suffix;
    public $dirs;
    protected $dbInstance;
    public function __construct()
    {
        try {
            $this->dbInstance = new PDO(
                "mysql:host=localhost;dbname=dbmudbaf00gr1e",
                "uhjdojqel1xrp",
                "3yfrehsrblkl"
            );
        } catch (Exception $e) {
            die("Error " . $e->getMessage());
        }
        $this->suffix = date('Y_m_d_His');
    }

    public function backup($tables = '*')
    {
        $output = "-- database backup - " . date('Y-m-d H:i:s') . PHP_EOL;
        $output .= "SET NAMES utf8;" . PHP_EOL;
        $output .= "SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';" . PHP_EOL;
        $output .= "SET foreign_key_checks = 0;" . PHP_EOL;
        $output .= "SET AUTOCOMMIT = 0;" . PHP_EOL;
        $output .= "START TRANSACTION;" . PHP_EOL;
        //get all table names
        if ($tables == '*') {
            $tables = [];
            $query = $this->dbInstance->prepare('SHOW TABLES');
            $query->execute();
            while ($row = $query->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }
            $query->closeCursor();
        } else {
            $tables = is_array($tables) ? $tables : explode(',', $tables);
        }

        foreach ($tables as $table) {

            $query = $this->dbInstance->prepare("SELECT * FROM `$table`");
            $query->execute();
            $output .= "DROP TABLE IF EXISTS `$table`;" . PHP_EOL;

            $query2 = $this->dbInstance->prepare("SHOW CREATE TABLE `$table`");
            $query2->execute();
            $row2 = $query2->fetch(PDO::FETCH_NUM);
            $query2->closeCursor();
            $output .= PHP_EOL . $row2[1] . ";" . PHP_EOL;

            while ($row = $query->fetch(PDO::FETCH_NUM)) {
                $output .= "INSERT INTO `$table` VALUES(";
                for ($j = 0; $j < count($row); $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j]))
                        $output .= "'" . $row[$j] . "'";
                    else $output .= "''";
                    if ($j < (count($row) - 1))
                        $output .= ',';
                }
                $output .= ");" . PHP_EOL;
            }
        }
        $output .= PHP_EOL . PHP_EOL;

        $output .= "COMMIT;";
        //save filename

        $backupFolder = 'db_backup/';
        if (!is_dir($backupFolder)) {
            mkdir($backupFolder, 0777, true); // Create the directory if it doesn't exist
            chmod($backupFolder, 0777);
        } 

        $filename = 'db_backup_' . $this->suffix . '.sql';
        $backupFile = $backupFolder . $filename;
        $this->writeUTF8filename($backupFile, $output);

        if (file_exists($backupFile)) {
            echo "Backup created and sent successfully!";
        }else{
            echo "Backup creation failed.";
        }
    }

    private function writeUTF8filename($fn, $c)
    {  /* save as utf8 encoding */
        $f = fopen($fn, "w+");
        # Now UTF-8 - Add byte order mark
        fwrite($f, pack("CCC", 0xef, 0xbb, 0xbf));
        fwrite($f, $c);
        fclose($f);
    }
}

$Backup = new DBbackup();
$Backup->backup();
 
