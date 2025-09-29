<?php

spl_autoload_register(function ($class_name) {
    include ($class_name . ".php");
});
class wand_core {
    private $RUN = true;

    public $ROUTES;

    public $logo = " _    _  ___   _   _______ 
| |  | |/ _ \ | \ | |  _  \
| |  | / /_\ \|  \| | | | |
| |/\| |  _  || . ` | | | |
\  /\  / | | || |\  | |/ / 
 \/  \/\_| |_/\_| \_/___/  
                           
                           \n";

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
            case "version":
                $load = new help_handler();
                $load->version_display();
                break;
            case "clear":
                $this->clear_screen();
                break;
            case "create-handler":
                $load = new make_handler();
                $load->make_handler();
                break;
            case "create-agent":
                $load = new make_handler();
                $load->make_agent();
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
            case "connect-test":
                $load = new connect_test();
                $load->run();
                break;
            case "show-routes":
                $load = new management_handler();
                $load->show_routes($this->ROUTES);
                break;
            case "add-route":
                $load = new management_handler();
                $output = $load->create_route($this->ROUTES);
                if($output) {
                    $this->ROUTES = $output;
                }
                break;
            case "rmv-route":
                $load = new management_handler();
                $output = $load->delete_route($this->ROUTES);
                if($output) {
                    $this->ROUTES = $output;
                }
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
        print($this->logo);
        print("Welcome to WAND\n");
        print("Type 'help' to get started\n");
        print("Type 'exit' to exit\n");
        print($this->LINE_BREAK);
    }

    private function screen_render() {
        $history_file = ".wand_history";
        if (file_exists($history_file)) {
            readline_read_history($history_file);
        }
        $this->clear_screen();

        while($this->RUN) {
            $command = readline("> ");
            readline_add_history($command);
            $this->command_handler($command, $history_file);
        }
        readline_write_history($history_file);
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

    public function pecl_check() {
        $pecl_check = system("pecl -V");
        if($pecl_check !== "") {
            return true;
        }
        else {
            return false;
        }
    }

    public function php_dev_check() {
        $dev_check = system("which phpize");
        if($dev_check !== "") {
            return true;
        }
        else {
            return false;
        }
    }

    public function openswoole_check() {
        $osw_check = system("php -m | grep openswoole");
        if($osw_check == "openswoole") {
            return true;
        }
        else {
            return false;
        }
    }

    public function phpenmod_check() {
        $mod_check = system("phpenmod");
        if($mod_check == "") {
            return false;
        }
        return true;
    }

    public function bool_to_str($bool) {
        if($bool) {
            return "true";
        }
        else {
            return "false";
        }
    }

    public function load_routes() {
        if($this->server_files_check()) {
            include_once("Emberwhisk/src/routes/Request_Routes.php");
            $request_routes = new Request_Routes();
            $this->ROUTES = $request_routes->REQUEST_ROUTES;
        }
        else {
            print("\033[31m$this->LINE_BREAK\n");
            print("\033[31mServer files missing:");
            print("\033[31mPlease run the wand 'init' command first to install the server.\n");
            print("\033[31m$this->LINE_BREAK\n");
            print("\033[0m");
            return false;
        }
    }

    public function init() {
        define('ANSI_RESET', "\033[0m");
        define('ANSI_INVERSE', "\033[7m");
        define('ANSI_CLEAR_LINE', "\033[2K");
        define('ANSI_CURSOR_UP', "\033[1A");
        $this->load_routes();
        $this->screen_render();
    }
}