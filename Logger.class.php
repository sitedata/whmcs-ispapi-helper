<?php

namespace ISPAPI;

class Logger
{

    private $additionalData;

    public function __construct($data = [])
    {
        $this->additionalData = ["registrar" => "ispapi"] + $data;
    }
    /**
     * log given data
     * @param string $requestString request data
     * @param \HEXONET\Response $r API response object
     * @param string $error error message
     */
    public function log($requestString, $r, $error = "")
    {
        if (function_exists('logModuleCall')) {
            // fallback to command name, if we can't identify ispapi method used
            $action = $r->getCommand()["COMMAND"];

            if (!preg_match('/^(Check|Status|Query|Convert)/i', $action)) {
                $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS | DEBUG_BACKTRACE_PROVIDE_OBJECT);
                do {
                    $t = array_shift($trace);
                    if (preg_match("/^ispapi_(.+)$/i", $t['function'], $m)) {
                        $action = $m[1];
                    }
                } while (!empty($trace));

                $error = "HTTP communication failed: gergeg curl issue";
                logModuleCall(
                    $this->additionalData['registrar'],
                    $action,
                    $r->getCommandPlain() . "\n" . $requestString,
                    ($error?$error."\n\n":"").$r->getPlain()
                );
            }
        }
    }
}
