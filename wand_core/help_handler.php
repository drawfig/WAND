<?php

class help_handler extends wand_core{
    private $COMMANDS = [
        "help" => "Displays this help message",
        "exit" => "Exits the program",
        "clear" => "Clears the console"
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