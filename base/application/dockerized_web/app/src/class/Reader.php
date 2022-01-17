<?php

namespace Dockerized;

class Reader
{
    /**
     * @description Reader Setup
     * @param string $var_name #Mandatory
     * @param array $opts
     * @return string
     */
    public static function readerSetup(string $var_name, array $opts = []): string
    {
        $setup = 'config/setup.txt';
        if (count($opts) > 0) {
            return in_array(self::get($setup, $var_name), $opts) ? 'style="display: block;"' : 'style="display: none;"';
        } else {
            return (self::get($setup, $var_name) == "true") ? 'style="display: block;"' : 'style="display: none;"';
        }
    }
    /**
     * @description Reader Setup
     * @param string $var_name #Mandatory
     * @return string
     */
    public static function getSetupVar(string $var_name): string
    {
        $setup = 'config/setup.txt';
        return self::get($setup, $var_name);
    }

    /**
     * @description Api Reader Setup
     * @param string $var_name #Mandatory
     * @param string $setup_file #Mandatory
     * @return string
     */
    public static function apiReaderSetup(string $var_name, string $setup_file): string
    {
        return self::get($setup_file, $var_name);
    }

    /**
     * @description Api Reader Setup All
     * @param string $var_name #Mandatory
     * @param string $setup_file #Mandatory
     * @return array
     */
    public static function apiReaderSetupAll(string $var_name, string $setup_file): array
    {
        return self::getAll($setup_file, $var_name);
    }

    /**
     * @description Get
     * @param string $file #Mandatory
     * @param string $var_name #Mandatory
     * @return string
     */
    public static function get(string $file, string $var_name): string
    {
        $env = [];
        $handler = fopen($file, "r");

        while (!feof($handler)) {
            $env_line = fgets($handler, 4096);
            if (preg_match("/^(?!#){$var_name} ?= ?(.*)/", $env_line, $env)) {
                $tmp = preg_replace('/["\']/i', '', explode("=", $env[0])[1]);
                $env = trim($tmp);
                break;
            }
        }

        fclose($handler);

        if (is_array($env)) {
            $env = "";
        }

        return $env;
    }

    /**
     * @description Get All
     * @param string $file #Mandatory
     * @param string $var_name #Mandatory
     * @return array
     */
    public static function getAll(string $file, string $var_name): array
    {
        $env = [];
        $handler = fopen($file, "r");

        while (!feof($handler)) {
            $env_line = fgets($handler, 4096);
            if (preg_match("/^(?!#){$var_name} ?= ?(.*)/", $env_line, $m)) {
                $tmp = preg_replace('/["\']/i', '', explode("=", $m[0])[1]);
                array_push($env, trim($tmp));
            }
        }

        fclose($handler);

        return $env;
    }
}
