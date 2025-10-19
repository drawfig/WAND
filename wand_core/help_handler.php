<?php

class help_handler extends wand_core{
    private $GENRAL = [
        "help" => "Displays this help message",
        "version" => "Displays the version information for WAND",
        "exit" => "Exits the program",
        "clear" => "Clears the console",
    ];
    private $SERVER = [
        "add-handler" => "Creates a new handler in the Handlers Directory of your Emberwhisk server",
        "add-agent" => "Creates a new agent in the Agents Directory of your Emberwhisk server",
        "init" => "Generates a new server",
        "gen-env" => "Starts the .env wizard",
        "start" => "Starts the server in development mode and will automatically reload on changes",
        "run-logging" => "Displays the server logs in the console and will automatically reload on changes",
    ];
    private $ROUTING = [
        "show-routes" => "Displays a table of all the routes in your server",
        "add-route" => "Adds a new route to your server",
        "rmv-route" => "Removes a route from your server",
    ];
    private $MIDDLEWARE = [
        "show-middleware" => "Displays a table of all the middleware in your server",
        "add-middleware" => "Adds a new middleware to your server",
        "rmv-middleware" => "Removes a middleware from your server",
        "add-middleware-group" => "Adds a new middleware group",
        "rmv-middleware-group" => "Removes a middleware group",
        "add-middleware-region" => "Adds a new middleware region",
        "rmv-middleware-region" => "Removes a middleware region",
        "add-global-middleware" => "Adds a new global middleware",
        "rmv-global-middleware" => "Removes a global middleware",
        "add-global-bypass" => "Adds a global bypass",
        "rmv-global-bypass" => "Removes a global bypass",
        "add-route-to-group" => "Adds a new route to your group",
        "rmv-route-from-group" => "Removes a route to your group",
        "add-route-to-region" => "Adds a new route to your region",
        "rmv-route-from-region" => "Removes a route from a region",
        "add-middleware-to-group" => "Adds a new middleware to your group",
        "rmv-middleware-from-group" => "Removes a middleware from a group",
        "add-middleware-to-region" => "Adds a new middleware to your region",
        "rmv-middleware-from-region" => "Removes a middleware from a region",
        "add-group-to-region" => "Adds a local group to your region",
    ];
    private $TESTS = [
        "connect-test" => "Runs a test that checks the server can be connected to and that the routes work. (Note: If the default 'bounce' route in the 'default_handler' has been removed this test will fail.)",
    ];

    private $VERSIONS = [
        "version" => "V0.0.3 (Alpha3)",
        "Codename" => "Blazing-Broom",
        "Release Date" => "2025-10-18",
        "Developed By" => "The Emberwhisk Project",
        "Contact me on Discord" => "https://discord.com/invite/gtwuf2A4Hq",
        "Or Find me on Github" => "https://github.com/drawfig",
        ];

    public function help_display() {
        print($this->LINE_BREAK);
        print("Command List\n");
        $this->help_break("General WAND Command List");
        foreach($this->GENRAL as $command => $description) {
            print("{$command} - {$description}\n");
        }
        $this->help_break("Server Control WAND Command List");
        foreach($this->SERVER as $command => $description) {
            print("{$command} - {$description}\n");
        }
        $this->help_break("Routing Control WAND Command List");
        foreach($this->ROUTING as $command => $description) {
            print("{$command} - {$description}\n");
        }
        $this->help_break("Middleware Control WAND Command List");
        foreach($this->MIDDLEWARE as $command => $description) {
            print("{$command} - {$description}\n");
        }
        $this->help_break("Server Test WAND Command List");
        foreach($this->TESTS as $command => $description) {
            print("{$command} - {$description}\n");
        }
        print($this->LINE_BREAK);
    }

    public function version_display() {
        print($this->LINE_BREAK);
        print("Version Information:\n");
        print($this->LINE_LOWER);
        foreach($this->VERSIONS as $key => $value) {
            print("{$key}: {$value}\n");
        }
        print($this->LINE_BREAK);
    }

    private function help_break($title) {
        print($this->LINE_BREAK);
        print($title . "\n");
        print($this->LINE_LOWER);
    }
}