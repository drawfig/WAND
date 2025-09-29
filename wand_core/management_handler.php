<?php

class management_handler extends wand_core {

    public function create_route($routes) {
        system('clear');
        if($routes) {
            $new_route_data = $this->get_new_route_data($routes);

            if($new_route_data) {
                $routes[$new_route_data[0]] = ["class" => $new_route_data[1], "method" => $new_route_data[2], "protected" => $new_route_data[3]];
                return $this->gen_routes_file($routes, "The Route has been added.\n");
            }
        }
        return false;
    }

    public function delete_route($routes) {
        if($routes) {
            while(true) {
                system('clear');
                print($this->LINE_BREAK);
                print("Creating a new route.\n");
                print("To quit type the command 'abort'\n");
                print($this->LINE_BREAK);
                $route_name = readline("What route do you want to delete? (example: 'bounce'): ");
                if($route_name == "abort") {
                    $this->clear_screen();
                    return false;
                }

                if(array_key_exists($route_name, $routes)) {
                    unset($routes[$route_name]);
                    return $this->gen_routes_file($routes, "The Route has been Removed.\n");
                }
                else {
                    print("The route {$route_name} does not exist.\n");
                    print("Please try again.\n");
                }
            }
        }
        return false;
    }

    public function show_routes($routes) {
        if($routes) {
            $title_row = ["Route Name", "Route Handler", "Route Method", "Route Protection"];
            $table_rows = [];

            system('clear');
            print("$this->LINE_BREAK\n");
            print("                        Routing Layout Table     \n");
            print("$this->LINE_BREAK\n");
            foreach($routes as $route_key => $route_data) {
                $table_rows[] = [$route_key, $route_data["class"], $route_data["method"], $route_data["protected"]];
            }
            $this->make_table($title_row, $table_rows);
            print("$this->LINE_BREAK\n");
            print("\n");
        }
    }

    private function make_table($title_row, $table_rows) {
        $col_length = [];
        foreach($title_row as $title) {
            $col_length[] = strlen($title);
        }

        foreach($table_rows as $row) {
            $pos = 0;
            foreach($row as $col) {
                if($col_length[$pos] < strlen($col)) {
                    $col_length[$pos] = strlen($col);
                }
                $pos++;
            }
        }

        $pos = 0;
        foreach ($col_length as $length) {
            if($length % 2 == 1) {
                $col_length[$pos] = $length++;
            }
            $pos++;
        }


        print($this->title_row_formating($col_length, $title_row));
        foreach($table_rows as $row) {
            print($this->table_row_formating($col_length, $row));
            print($this->gen_table_break($col_length));
        }

    }

    private function column_spacing($col_length, $text) {
        $text_length = strlen($text);

        if($text_length == $col_length) {
            return $text;
        }

        $diff = $col_length - $text_length;

        $back_space_count = floor($diff / 2);
        $front_space_count = $diff - $back_space_count;

        for($i = 0; $i < $front_space_count; $i++) {
            $text = " " . $text;
        }
        for($i = 0; $i < $back_space_count; $i++) {
            $text = $text . " ";
        }

        return $text;
    }

    private function table_row_formating($sizes, $row) {
        $pos = 0;
        $line_out = "|   ";
        foreach($row as $col) {
            $length = $sizes[$pos];
            $line_out .= $this->column_spacing($length, $this->column_processing($col));
            if($pos < sizeof($row) - 1) {
                $line_out .= "   |   ";
            }
            $pos++;
        }
        return $line_out . "   |\n";
    }

    private function column_processing($col) {
        if(is_bool($col)) {
            if($col) {
                return "true";
            }
            else {
                return "false";
            }
        }

        return $col;

    }

    private function gen_table_break($col_sizes ) {
        $line_out = "+---";
        $pos = 0;
        foreach($col_sizes as $size) {
            $line_out .= str_repeat("-", $size);
            if($pos < sizeof($col_sizes) - 1) {
                $line_out .= "---+---";
            }
            $pos++;
        }
        $line_out .= "---+\n";
        return $line_out;
    }

    private function title_row_formating($sizes, $title_row) {
        $pos = 0;
        $line_out = "";
        foreach($title_row as $title) {
            $length = $sizes[$pos];
            $line_out .= $this->column_spacing($length, $title);
            if($pos < sizeof($title_row) - 1) {
                $line_out .= "   |   ";
            }
            $pos++;
        }
        return ANSI_INVERSE . "    " . $line_out . "    " . ANSI_RESET . "\n";
    }

    private function get_new_route_data($current_routes)
    {
        $menu_questions = [
            "What should the route be called? (example: 'bounce')",
            "What class should the route be handled by? (example: 'Default_Handler')",
            "What method should the route be handled by? (example: 'bounce')",
        ];

        $route_data = [];

        print($this->LINE_BREAK);
        print("Creating a new route.\n");
        print("To quit type the command 'abort'\n");
        print($this->LINE_BREAK);

        foreach ($menu_questions as $question) {
            while (true) {
                $answer = readline($question . ": ");
                if ($answer == "abort") {
                    return false;
                }
                if (strlen($answer) > 2) {
                    $route_data[] = $answer;
                    break;
                } else {
                    print("Answer must be longer than 3 characters.\n");
                }
            }
        }

        if (!array_key_exists($route_data[0], $current_routes)) {
            $protected_status = $this->true_false_display("Do you want this route to be protected?");

            if ($protected_status == "exit") {
                return false;
            }

            if ($protected_status == "true") {
                $route_data[] = true;
            }
            else {
                $route_data[] = false;
            }

            return $route_data;
        }
        else {
            print("\033[31m$this->LINE_BREAK\n");
            print("\033[31mThe {$route_data[0]} Route already exists.\n");
            print("\033[31m$this->LINE_BREAK\n");
            print("\033[0m");
            return false;
        }
    }

    private function true_false_display($line)
    {
        $options = [
            "false",
            "true",
            "exit"
        ];

        $selected = 0;
        system('stty -echo -icanon');
        $this->menu($options, $selected, "Select the config value for {$line}");

        while (true) {
            $key = fread(STDIN, 1);
            if ($key === "\033") {
                fread(STDIN, 1);
                $key_sequence = fread(STDIN, 1);
                switch ($key_sequence) {
                    case "A":
                        $selected = max(0, $selected - 1);
                        break;
                    case "B":
                        $selected = min(count($options) - 1, $selected + 1);
                        break;
                }
                $this->menu($options, $selected, "Select the config value for {$line}");
            } else if ($key == "\n") {
                system('stty sane');

                return $options[$selected];
            }
        }
    }

    private function gen_routes_file($routes, $end_mess) {
        $route_entries = "";
        foreach ($routes as $key => $route) {
            $route_entries .= "        '{$key}' => ['class' => '{$route['class']}', 'method' => '{$route['method']}', 'protected' => {$this->bool_to_str($route['protected'])}],\n";
        }

        $file_lines = '<?php
class Request_Routes {
    public $REQUEST_ROUTES = [' . "\n" .
    $route_entries . '
    ];
}';
        if(strlen($file_lines) > 0) {
            $file_address = "Emberwhisk/src/routes/Request_Routes.php";
            $file_create = fopen($file_address, "w");
            fwrite($file_create, $file_lines);
            print($end_mess);
            readLine("Press enter to continue.");
            $this->clear_screen();
            return $routes;
        }
        return false;
    }
}