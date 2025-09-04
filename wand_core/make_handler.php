<?php

class make_handler extends wand_core {

    public function make_handler() {
        $run = true;
        print($this->LINE_BREAK);
        print("Generate a new handler for the server.\n");
        print("Type 'cancel' to exit.\n");;
        print($this->LINE_BREAK);
        while($run) {
            $handler_name = readline("Handler Name: ");
            if(strtolower($handler_name) == "cancel") {
                $this->clear_screen();
                break;
            }

            if($handler_name !== "") {
                $status_chk = $this->generate_handler($handler_name);
            }
            else {
                print("Handler name cannot be empty.\n");
            }
            if($status_chk) {
                $run = false;
            }
        }
    }

    private function generate_handler($handler_name) {
        if(file_exists("Emberwhisk/src/Handlers/{$handler_name}_handler.php")) {
            print("Handler already exists.\n");
            print("Please choose a different name.\n");
            return false;
        }
        else {
            print("Generating handler...\n");
            $file_content = '<?php
spl_autoload_register(function ($class_name) {
    if(file_exists(__DIR__ . "/Utils/" . str_replace("Utils\\\", "", $class_name) . ".php")) {
        require_once (__DIR__ . "/Utils/" . str_replace("Utils\\\", "", $class_name) . ".php");
    }
});

spl_autoload_register(function ($class_name) {
    include ($class_name . ".php");
});

class ' . $handler_name . '_handler {

}';
            $file_create = fopen("Emberwhisk/src/Handlers/{$handler_name}_handler.php", "w");
            fwrite($file_create, $file_content);
            return true;
        }
    }

    public function generate_server() {
        if(file_exists("Emberwhisk")) {
            print("Emberwhisk is already installed.\n");
        }
        else if(!$this->pecl_check()) {
            print("\033[31mMissing dependency:");
            print("\033[31mPECL is not installed.\n");
            print("\033[0m");
        }
        else {
            print ("Installation of Emberwhisk initialized...\n");
            print("Downloading Emberwhisk...\n");
            system("git clone https://github.com/drawfig/Emberwhisk.git");
            print("Emberwhisk downloaded.\n");
            print("Getting dependencies...\n");
            copy('https://getcomposer.org/installer', 'Emberwhisk/composer-setup.php');
            system("php Emberwhisk/composer-setup.php");
            unlink('Emberwhisk/composer-setup.php');
            system("php Emberwhisk/composer.phar install");
            print("Checking for openswoole...\n");
            if($this->openswoole_check()) {
                print("Openswoole is already installed.\n");
            }
            else {
                print("Installing openswoole...\n");
                system("pecl install openswoole");
            }
            print("Emberwhisk installed!\n");
            print("Now just enter the Emberwhisk directory and set up the .env files for the corresponding environment from the example config file or use the 'gen-env' command in WAND to help build them.\n");
            print("Once that is done run the server by either navigating to the Emberwhisk directory and running 'php run.php [environment-var-here]' or using the 'start' command in WAND.\n");
            print("Note: the WAND 'start' command is recommended for development as it will automatically restart the server when changes are made.\n");;
        }
    }

    private function pecl_check() {
        $pecl_check = system("pecl -V");
        if($pecl_check !== "") {
            return true;
        }
        else {
            return false;
        }
    }

    private function openswoole_check() {
        $osw_check = system("php -m | grep openswoole");
        if($osw_check == "openswoole") {
            return true;
        }
        else {
            return false;
        }
    }

    public function gen_env() {
        $options = [
            "dev",
            "local",
            "test",
            "prod",
        ];

        $selected = 0;
        system('stty -echo -icanon');
        $this->menu($options, $selected, "Select an environment config to generate");

        while(true) {
            $key = fread(STDIN,1);
            if($key === "\033") {
                fread(STDIN,1);
                $key_sequence = fread(STDIN,1);
                switch($key_sequence) {
                    case "A":
                        $selected = max(0, $selected - 1);
                        break;
                    case "B":
                        $selected = min(count($options) - 1, $selected + 1);
                        break;
                }
                $this->menu($options, $selected, "Select an environment config to generate");
            }
            else if($key == "\n") {
                system('stty sane');

                $env_type = $options[$selected];
                break;
            }
        }

        system('stty sane');
        $this->make_env_file($env_type);
    }

    private function make_env_file($env_type) {
        $file_lines = [
            "APP_NAME",
            "APP_VERSION",
            "APP_VERSION_NAME",
            "ADDRESS",
            "PORT",
            "PROTOCOL",
            "ENVIRONMENT",
            "API_ADDRESS",
            "API_KEY",
            "API_PROTOCOL",
            "API_AUTH_ADDRESS",
            "API_VERSION",
            "WORKER_COUNT",
            "SECRET",
        ];

        $deploy_lines = [
            "DAEMONIZATION",
            "SSL_CERT",
            "SSL_KEY",
            "SSL_VERIFY_PEER",
            "SSL_ALLOW_SELF_SIGNED",
        ];

        $file_content = "";
        foreach($file_lines as $line) {
            system("clear");
            print("Creating the .env.{$env_type} file\n");
            print("To abort type the command 'exit'\n");
            print($this->LINE_BREAK);
            $value = readline("Enter the value for {$line} (An empty value will result in the default value being used): ");

            if($value == "exit") {
                $file_content = "";
                break;
            }

            if($value == "") {
                $value = $this->default_getters($line, $env_type);
            }

            $file_content .= $line . '="' . $value . '"' . "\n";
        }
        if($file_content == "") {
            print("The .env.{$env_type} file was Aborted.\n");
            readLine("Press enter to continue.");
            $this->clear_screen();
            return;
        }

        if($env_type == "test" || $env_type == "prod") {
            foreach($deploy_lines as $line) {
                system("clear");
                print("Creating the .env.{$env_type} file\n");
                print("To abort type the command 'exit'\n");
                print($this->LINE_BREAK);
                $value = readline("Enter the value for {$line}: ");

                if($value == "exit") {
                    $file_content = "";
                    break;
                }

                $file_content .= $line . '="' . $value . '"' . "\n";
            }
        }
        else {
            $file_content .= 'DAEMONIZATION="false"' . "\n";
            $file_content .= 'SSL_CERT=""' . "\n";
            $file_content .= 'SSL_KEY=""' . "\n";
            $file_content .= 'SSL_VERIFY_PEER="false"' . "\n";
            $file_content .= 'SSL_ALLOW_SELF_SIGNED="false"' . "\n";
        }

        if($file_content !== "") {
            $env_file = "Emberwhisk/.env.{$env_type}";
            $file_create = fopen($env_file, "w");
            fwrite($file_create, $file_content);
            print("The .env.{$env_type} file has been created.\n");
            readLine("Press enter to continue.");
            $this->clear_screen();
        }
        else {
            print("The .env.{$env_type} file was Aborted.\n");
            readLine("Press enter to continue.");
            $this->clear_screen();
        }
    }

    function default_getters($line, $env_type) {
        switch ($env_type) {
            case "dev":
                $environment = "development";
                break;
            case "local":
                $environment = "local";
                break;
            case "test":
                $environment = "testing";
                break;
            case "prod":
                $environment = "production";
                break;
            default:
                $environment = "development";
        }

        switch($line) {
            case "APP_NAME":
                return "Emberwhisk";
            case "APP_VERSION":
            case "API_VERSION":
                return "1.0";
            case "APP_VERSION_NAME":
                return "Braixen";
            case "ADDRESS":
            case "API_ADDRESS":
                return "127.0.0.1";
            case "PORT":
                return "9502";
            case "PROTOCOL":
                return "ws";
            case "ENVIRONMENT":
                return $environment;
            case "API_PROTOCOL":
                return "http";
            case "API_AUTH_ADDRESS":
                return "auth_check";
            case "WORKER_COUNT":
                return "1";
            case "DAEMONIZATION":
            case "SSL_VERIFY_PEER":
            case "SSL_ALLOW_SELF_SIGNED":
                return "false";
            case "SECRET":
            case "API_KEY":
                return $this->gen_random_str(32);
            default:
                return "";
        }
    }


}