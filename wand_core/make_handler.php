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
        }
    }
}