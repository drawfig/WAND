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
                $test_data = bin2hex(random_bytes(32));
                $message = json_encode(["message_type" => "bounce", "test_data" => $test_data]);
                $client->push($message);

                $response = $client->recv(5);

                if ($response) {
                    $response_data = json_decode($response->data, true);
                    if ($response_data['message_type'] == "bounce" && $response_data['test_data'] == $test_data) {
                        print("\033[32m" . $this->LINE_BREAK);
                        print("\033[32mConnection Successful\n");
                        print("\033[32m" . $this->LINE_BREAK);
                        print("\033[0m");
                    }
                    else {
                        print("\033[31m" . $this->LINE_BREAK);
                        print("\033[31mBad Response From Server\n");
                        print("\033[31m" . $this->LINE_BREAK);
                        print("\033[0m");
                    }
                }
                else {
                    print("\033[31m" . $this->LINE_BREAK);
                    print("\033[31mServer is connected but\n");
                    print("\033[31mThere was No Response From the Server\n");
                    print("\033[31mMake sure that 'bounce' is in the route.\n");
                    print("\033[31m" . $this->LINE_BREAK);
                    print("\033[0m");
                }
            }
            else {
                print("\033[31m" . $this->LINE_BREAK);
                print("\033[31mFailed to connect to server\n");
                print("\033[31m" . $this->LINE_BREAK);
                print("\033[0m");
            }
        });
    }
}