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
            default:
                print("Command {$command} not found\n");
        }
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

    public function init() {
        $this->screen_render();
    }
}