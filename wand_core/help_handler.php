<?php

class help_handler extends wand_core{
    private $COMMANDS = [
        "help" => "Displays this help message",
        "version" => "Displays the version information for WAND",
        "exit" => "Exits the program",
        "clear" => "Clears the console",
        "create-handler" => "Creates a new handler in the Handlers Directory of your Emberwhisk server",
        "create-agent" => "Creates a new agent in the Agents Directory of your Emberwhisk server",
        "init" => "Generates a new server",
        "gen-env" => "Starts the .env wizard",
        "start" => "Starts the server in development mode and will automatically reload on changes",
        "connect-test" => "Runs a test that checks the server can be connected to and that the routes work. (Note: If the default 'bounce' route in the 'default_handler' has been removed this test will fail.)"
    ];

    private $VERSIONS = [
        "version" => "V0.0.2 (Alpha2)",
        "Codename" => "Astral-Amulet",
        "Release Date" => "2025-09-24",
        "Developed By" => "The Emberwhisk Project",
        "Contact me on Discord" => "https://discord.com/invite/gtwuf2A4Hq",
        "Or Find me on Github" => "https://github.com/drawfig",
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

    public function version_display() {
        print($this->LINE_BREAK);
        print("Version Information:\n");
        print($this->LINE_BREAK);
        foreach($this->VERSIONS as $key => $value) {
            print("{$key}: {$value}\n");
        }
        print($this->LINE_BREAK);
    }
}