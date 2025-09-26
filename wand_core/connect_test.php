<?php
use OpenSwoole\Coroutine;
use OpenSwoole\Coroutine\Http\Client;


class connect_test extends wand_core {
    private $ADDRESS;
    private $PORT;


    public function run() {
        $vars = [
            "Address",
            "Port",
        ];
        $output = [];

        foreach ($vars as $var) {
            system("clear");
            print($this->LINE_BREAK);
            print("Enter the value of {$var}\n");
            print("Enter the 'abort' to cancel\n");
            print($this->LINE_BREAK);
            $val = readline("> ");
            if (strtolower($val) == "abort") {
                $output = [];
                break;
            }
            else {
                $output[$var] = $val;
            }
        }

        if (sizeof($output) > 0) {
            $this->test($output['Address'], $output['Port']);
        }
        else {
            $this->clear_screen();
        }
    }
    private function test($address, $port) {
        $this->ADDRESS = $address;
        $this->PORT = $port;
        co::run(function() {
            $client = new Client($this->ADDRESS, $this->PORT);

            $client->set(['timeout' => 5]);

            $is_upgrade = $client->upgrade('/');

            if ($is_upgrade) {
                $message = json_encode(["message_type" => "bounce", "test_data" => bin2hex(random_bytes(32))]);
                $client->push($message);

                $response = $client->recv(5);

                if ($response) {
                    var_dump($response);
                }
                else {
                    print("No response\n");
                }
            }
            else {
                print("Failed to connect to server\n");
            }
        });
    }
}