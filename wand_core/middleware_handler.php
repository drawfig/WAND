<?php

use Middleware\Middleware_Routing_Groups;
use Middleware\Middleware_Software_Groups;

class middleware_handler extends wand_core {
    private $EXCLUDE = [
        ".",
        "..",
        "Middleware_Manager.php",
        "Middleware_Routing_Groups.php",
        "Middleware_Software_Groups.php",
    ];

    public function create_middleware() {
        system('clear');
        if($this->server_files_check()) {
            $run = true;
            print($this->LINE_BREAK);
            print("Generate a new middleware for the server.\n");
            print("Type 'abort' to exit.\n");;
            print($this->LINE_BREAK);
            while ($run) {
                $middleware_name = readline("Middleware Name: ");
                if (strtolower($middleware_name) == "abort") {
                    $this->clear_screen();
                    break;
                }

                if (strlen($middleware_name) > 3 && !$this->check_for_middleware_name($middleware_name)) {
                    $this->generate_middleware($middleware_name);
                }
                else {
                    print("Middleware name must be longer than 3 characters.\n");
                }
            }
        }
        else {
            print("\033[31m$this->LINE_BREAK\n");
            print("\033[31mServer files missing:");
            print("\033[31mPlease run the wand 'init' command first to install the server.\n");
            print("\033[31m$this->LINE_BREAK\n");
            print("\033[0m");
        }
    }

    public function show_middleware() {
        if($this->server_files_check()) {
            $files_raw = scandir("Emberwhisk/src/Middleware");
            $files = [];
            foreach ($files_raw as $file) {
                if (!in_array($file, $this->EXCLUDE)) {
                    $files[] = [$file];
                }
            }

            $this->make_table(["Middleware Name"], $files);
        }
        else {
            print("\033[31m$this->LINE_BREAK\n");
            print("\033[31mServer files missing:");
            print("\033[31mPlease run the wand 'init' command first to install the server.\n");
            print("\033[31m$this->LINE_BREAK\n");
            print("\033[0m");
        }
    }

    public function create_middleware_group($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass) {
        if($this->server_files_check()) {
            return $this->generate_middleware_group($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass);
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

    public function create_middleware_region($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass)
    {
        if($this->server_files_check()) {
            return $this->generate_middleware_region($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass);
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

    public function create_middleware_bypass($group_routes, $region_routes, $global_bypass, $routes) {
        if($this->server_files_check()) {
            return $this->generate_middleware_bypass($group_routes, $region_routes, $global_bypass, $routes);
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

    public function create_global_middleware($group_middleware, $region_software, $global_middleware) {
        if($this->server_files_check()) {
            return $this->generate_global_middleware($group_middleware, $region_software, $global_middleware);
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

    public function add_route_to_group($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass, $routes) {
        if($this->server_files_check()) {
            return $this->adding_route_to_group($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass, $routes);
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
    public function add_middleware_to_group($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass) {}

    private function adding_route_to_group($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass, $routes) {
        if(!$group_routes) {
            include_once("Emberwhisk/src/Middleware/Middleware_Routing_Groups.php");
            $routing_groups = new Middleware_Routing_Groups();
            include_once("Emberwhisk/src/Middleware/Middleware_Software_Groups.php");
            $software_groups = new Middleware_Software_Groups();
            $group_routes = $routing_groups->LOCAL_GROUPS;
            $region_routes = $routing_groups->REGIONAL_GROUPS;
            $global_bypass = $routing_groups->GLOBAL_BYPASS_ROUTES;
            $group_software = $software_groups->LOCAL_GROUP_MIDDLEWARE;
            $region_software = $software_groups->REGIONAL_MIDDLEWARE;
            $global_middleware = $software_groups->GLOBAL_MIDDLEWARE;
        }
        else {

        }
    }

    private function generate_global_middleware($group_middleware, $region_software, $global_middleware) {
        if(!$global_middleware) {
            include_once("Emberwhisk/src/Middleware/Middleware_Software_Groups.php");
            $software_groups = new Middleware_Software_Groups();
            $group_middleware = $software_groups->LOCAL_GROUP_MIDDLEWARE;
            $region_software = $software_groups->REGIONAL_MIDDLEWARE;
            $global_middleware = $software_groups->GLOBAL_MIDDLEWARE;
        }

        $files_raw = scandir("Emberwhisk/src/Middleware");
        $files = [];
        foreach ($files_raw as $file) {
            $file_prep = str_replace(".php", "", $file);
            if (!in_array($file, $this->EXCLUDE) && !in_array($file_prep, $global_middleware)) {
                $files[] = $file_prep;
            }
        }
        $files[] = "Abort";

        $selected = 0;
        system('stty -echo -icanon');
        $this->menu($files, $selected, "Select a middleware to add to the global middleware list");

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
                        $selected = min(count($files) - 1, $selected + 1);
                        break;
                }
                $this->menu($files, $selected, "Select a middleware to add to the global middleware list");
            }
            else if($key == "\n") {
                system('stty sane');

                $software = $files[$selected];
                break;
            }
        }
        system('stty sane');

        if($software == "Abort") {
            $this->clear_screen();
            return false;
        }
        $global_middleware[] = $software;
        $this->gen_middleware_software_group($group_middleware, $region_software, $global_middleware);
        print("Global middleware created.\n");
        readLine("Press enter to continue.");
        $this->clear_screen();
        return true;

    }

    private function generate_middleware_bypass($group_routes, $region_routes, $global_bypass, $routes) {
        if(!$global_bypass) {
            include_once("Emberwhisk/src/Middleware/Middleware_Routing_Groups.php");
            $routing_groups = new Middleware_Routing_Groups();
            $group_routes = $routing_groups->LOCAL_GROUPS;
            $region_routes = $routing_groups->REGIONAL_GROUPS;
            $global_bypass = $routing_groups->GLOBAL_BYPASS_ROUTES;
        }

        $routes_out = [];
        foreach ($routes as $key => $route) {
            if(!in_array($key, $global_bypass)) {
                $routes_out[] = $key;
            }
        }

        $routes_out[] = "Abort";

        $selected = 0;
        system('stty -echo -icanon');
        $this->menu($routes_out, $selected, "Select a route to add to the global bypass list");

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
                        $selected = min(count($routes_out) - 1, $selected + 1);
                        break;
                }
                $this->menu($routes_out, $selected, "Select a route to add to the global bypass list");
            }
            else if($key == "\n") {
                system('stty sane');

                $bypass_name = $routes_out[$selected];
                break;
            }
        }
        system('stty sane');

        if($bypass_name == "Abort") {
            $this->clear_screen();
            return false;
        }

        $global_bypass[] = $bypass_name;
        $this->gen_middleware_route_group($group_routes, $region_routes, $global_bypass);
        print("Middleware bypass created.\n");
        readLine("Press enter to continue.");
        $this->clear_screen();
        return true;

    }

    private function generate_middleware_region($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass)
    {
        if(!$region_routes) {
            include_once("Emberwhisk/src/Middleware/Middleware_Routing_Groups.php");
            $routing_groups = new Middleware_Routing_Groups();
            include_once("Emberwhisk/src/Middleware/Middleware_Software_Groups.php");
            $software_groups = new Middleware_Software_Groups();
            $group_routes = $routing_groups->LOCAL_GROUPS;
            $region_routes = $routing_groups->REGIONAL_GROUPS;
            $global_bypass = $routing_groups->GLOBAL_BYPASS_ROUTES;
            $group_software = $software_groups->LOCAL_GROUP_MIDDLEWARE;
            $region_software = $software_groups->REGIONAL_MIDDLEWARE;
            $global_middleware = $software_groups->GLOBAL_MIDDLEWARE;
        }

        $region_name = readline("Middleware Region Name: ");

        if (array_key_exists($region_name, $region_routes)) {
            print("Middleware region already exists.\n");
            return false;
        }

        if(strlen($region_name) > 3) {
            $region_routes[$region_name] = [];
            $region_software[$region_name] = [];
            $this->gen_middleware_route_group($group_routes, $region_routes, $global_bypass);
            $this->gen_middleware_software_group($group_software, $region_software, $global_middleware);
            print("Middleware region created.\n");
            readLine("Press enter to continue.");
            $this->clear_screen();
            return true;
        }
        else {
            print("Middleware region name must be longer than 3 characters.\n");
            return false;
        }
    }

    private function generate_middleware_group($group_routes, $group_software, $region_routes, $region_software, $global_middleware, $global_bypass) {
        if(!$group_routes) {
            include_once("Emberwhisk/src/Middleware/Middleware_Routing_Groups.php");
            $routing_groups = new Middleware_Routing_Groups();
            include_once("Emberwhisk/src/Middleware/Middleware_Software_Groups.php");
            $software_groups = new Middleware_Software_Groups();
            $group_routes = $routing_groups->LOCAL_GROUPS;
            $region_routes = $routing_groups->REGIONAL_GROUPS;
            $global_bypass = $routing_groups->GLOBAL_BYPASS_ROUTES;
            $group_software = $software_groups->LOCAL_GROUP_MIDDLEWARE;
            $region_software = $software_groups->REGIONAL_MIDDLEWARE;
            $global_middleware = $software_groups->GLOBAL_MIDDLEWARE;
        }

         $group_name = readline("Middleware Group Name: ");

        if (array_key_exists($group_name, $group_routes)) {
            print("Middleware group already exists.\n");
            return false;
        }

        if(strlen($group_name) > 3) {
            $group_routes[$group_name] = [];
            $group_software[$group_name] = [];
            $this->gen_middleware_route_group($group_routes, $region_routes, $global_bypass);
            $this->gen_middleware_software_group($group_software, $region_software, $global_middleware);
            print("Middleware group created.\n");
            readLine("Press enter to continue.");
            $this->clear_screen();
            return true;
        }
        else {
            print("Middleware group name must be longer than 3 characters.\n");
            return false;
        }
    }

    private function gen_middleware_software_group($group_software, $region_software, $global_middleware) {
        $group_list = "";
        $region_list = "";
        $global_list = "";

        foreach ($group_software as $group => $middleware_list) {
            $group_list .= "        '{$group}' => {$this->list_format($middleware_list)},\n";
        }

        foreach ($region_software as $region => $middleware_list) {
            $region_list .= "        '{$region}' => {$this->list_format($middleware_list)},\n";
        }

        foreach ($global_middleware as $middleware) {
            $global_list .= "        '{$middleware}',\n";
        }

        $file_content = '<?php
namespace Middleware;

class Middleware_Software_Groups {
    public $GLOBAL_MIDDLEWARE = [
' . $global_list . '
    ];

    public $REGIONAL_MIDDLEWARE = [
' . $region_list . '
    ];

    public $LOCAL_GROUP_MIDDLEWARE = [
' . $group_list . '
    ];
}';

        $file_create = fopen("Emberwhisk/src/Middleware/Middleware_Software_Groups.php", "w");
        fwrite($file_create, $file_content);
    }

    private function gen_middleware_route_group($group_routes, $region_routes, $global_bypass) {
        $route_groups = "";
        $route_region = "";
        $global_bypass_routes = "";

        foreach ($group_routes as $group => $route_list) {
            $group_list = $this->list_format($route_list);
            $route_groups .= "        '{$group}' => {$group_list},\n";
        }

        foreach ($region_routes as $region => $route_list) {
            $group_list = $this->list_format($route_list);
            $route_region .= "        '{$region}' => {$group_list},\n";
        }

        foreach ($global_bypass as $route) {
            $global_bypass_routes .= "        '{$route}',\n";
        }

        $file_content = '<?php
namespace Middleware;

class Middleware_Routing_Groups {
    public $REGIONAL_GROUPS =[
' . $route_region . '
    ];

    public $LOCAL_GROUPS = [
' . $route_groups . '
    ];

    public $GLOBAL_BYPASS_ROUTES = [
' . $global_bypass_routes . '
    ];

    public function get_group($group) {
        if(array_key_exists($group, $this->LOCAL_GROUPS)) {
            return $this->LOCAL_GROUPS[$group];
        }
        else {
            return false;
        }
    }

    public function get_region($region) {
        $raw = $this->REGIONAL_GROUPS[$region];
        $output = [];
        foreach ($raw as $row) {
            $group = $this->group_check($row);
            if($group) {
                foreach ($group as $inner_row) {
                    $output[] = $inner_row;
                }
            }
            else {
                $output[] = $row;
            }
        }

        return $output;
    }

    private function group_check($group) {
        $group_chk = explode(":", $group);
        if(sizeof($group_chk) != 2) {
            return false;
        }

        if($group_chk[0] !== "GROUP") {
            return false;
        }

        if(array_key_exists($group_chk[1], $this->LOCAL_GROUPS)) {
            return $this->LOCAL_GROUPS[$group_chk[1]];
        }
        else {
            return false;
        }
    }

}';
        $file_create = fopen("Emberwhisk/src/Middleware/Middleware_Routing_Groups.php", "w");
        fwrite($file_create, $file_content);
    }

    private function check_for_middleware_name($name) {
        if(file_exists("Emberwhisk/src/Middleware/". $name . ".php")) {
            print("Middleware already exists.\n");
            return true;
        }
        return false;
    }

    private function generate_middleware($middleware_name) {
        $file_content = '<?php
namespace Middleware;

spl_autoload_register(function ($class_name) {
    if(file_exists(__DIR__ . "/Utils/" . str_replace("Utils\\\", "", $class_name) . ".php")) {
        require_once (__DIR__ . "/Utils/" . str_replace("Utils\\\", "", $class_name) . ".php");
    }
});

spl_autoload_register(function ($class_name) {
    include ($class_name . ".php");
});

class ' . $middleware_name . ' {
    public function run($data, $server, $db) {}
}';

        $file_create = fopen("Emberwhisk/src/Middleware/{$middleware_name}.php", "w");
        fwrite($file_create, $file_content);
        print("Middleware created.\n");
        readLine("Press enter to continue.");
        $this->clear_screen();
        $this->clear_screen();
    }

    private function list_format($list) {
        $output = "[\n";
        foreach ($list as $row) {
            $output .= "            '{$row}',\n";
        }
        $output .= "        ]";
        return $output;
    }
}
