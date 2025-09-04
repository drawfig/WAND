<?php

class help_handler extends wand_core{
    private $COMMANDS = [
        "help" => "Displays this help message",
        "exit" => "Exits the program",
        "clear" => "Clears the console",
        "create-handler" => "Creates a new handler",
        "init" => "Generates a new server",
        "gen-env" => "Starts the .env wizard",
        "start" => "Starts the server in development mode and will automatically reload on changes",
    ];

    public function help_display() {
        print($this->LINE_BREAK);
        print("Command List\n");
        print($this->LINE_BREAK);
        foreach($this->COMMANDS as $command => $description) {
            print("{$command} - {$description}\n");
        }
        print($this->LINE_BREAK);
    }
}