<?php

spl_autoload_register(function ($class_name) {
    include ($class_name . ".php");
});
class wand_core {
    private $RUN = true;

    public $LINE_BREAK = "=======================================================================\n";

    private function command_handler($command) {
        switch (strtolower($command)) {
            case "exit":
                $this->RUN = false;
                break;
            case "help":
                $load = new help_handler();
                $load->help_display();
                break;
            case "clear":
                $this->clear_screen();
                break;
            case "create-handler":
                $load = new make_handler();
                $load->make_handler();
                break;
            case "init":
                $load = new make_handler();
                $load->generate_server();
                break;
            case "gen-env":
                $load = new make_handler();
                $load->gen_env();
                break;
            case "start":
                $load = new start_handler();
                $load->start_server();
                break;
            default:
                print("Command {$command} not found\n");
        }
    }

    public function gen_random_str($length) {
        return bin2hex(random_bytes($length));
    }

    public function clear_screen() {
        system("clear");
        print("Welcome to WAND\n");
        print("Type 'help' to get started\n");
        print("Type 'exit' to exit\n");
        print($this->LINE_BREAK);
    }

    private function screen_render() {
        $this->clear_screen();

        while($this->RUN) {
            $command = readline("> ");
            $this->command_handler($command);
        }
        print("Goodbye!\n");;
    }

    public function server_files_check() {
        return file_exists("Emberwhisk");
    }

    public function sqlite3_check() {
        $out = system("php -m | grep sqlite3");

        if($out == "") {
            return false;
        }
        else {
            return true;
        }
    }

    public function menu($options, $selected, $text) {
        system('clear');


        if($selected > 0) {
            echo str_repeat(ANSI_CURSOR_UP, count($options));
        }
        print($text . ": \n");
        print($this->LINE_BREAK);

        foreach($options as $index => $option) {

            $option_padded = str_pad($option, 40);

            if($index == $selected) {
                echo ANSI_INVERSE . $option_padded . ANSI_RESET . "\n";
            }
            else {
                echo $option_padded . "\n";
            }
        }
    }

    public function init() {
        define('ANSI_RESET', "\033[0m");
        define('ANSI_INVERSE', "\033[7m");
        define('ANSI_CLEAR_LINE', "\033[2K");
        define('ANSI_CURSOR_UP', "\033[1A");
        $this->screen_render();
    }
}