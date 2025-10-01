<?php

class log_handler extends wand_core {
    private $run = true;
    private $file_time;

    public function display_logs() {
        pcntl_signal(SIGINT, function () {
            echo "Caught interrupt signal\n";
            $this->run = false;
        });
        while ($this->run) {
            clearstatcache();
            $file_chk = filemtime("Emberwhisk/src/web_sock.db");
            var_dump($file_chk);
            if($file_chk != $this->file_time) {
                $this->file_time = $file_chk;
                $logs = $this->get_logs();
                $title_row = ["ID", "User ID", "Message Type", "Description", "Timestamp"];
                $this->make_table($title_row, $logs);
            }
            pcntl_signal_dispatch();
            sleep(1);
        }
    }

    private function get_logs() {
        include_once("wand_sqlite.php");
        $db = new Sqlite_DB();
        $ready_query = $db->prepare("SELECT * FROM server_log");
        $run_query = $ready_query->execute();
        $output = [];
        while ($result = $run_query->fetchArray(SQLITE3_ASSOC)) {
            $output[] = $result;
        }
        $db->close();
        $db = null;
        return $output;
    }
}