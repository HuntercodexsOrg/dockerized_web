<?php

namespace Dockerized;

class Data
{
    /**
     * @description Get Languages
     * @return array
     */
    public static function getLanguages(): array
    {
        return [
            "PHP", "JAVA", "PYTHON", "NODEJS", "CSHARP"
        ];
    }

    /**
     * @description Get Languages
     * @param string $lang
     * @return array
     */
    public static function getLanguagesVersion(string $lang = ""): array
    {
        return match ($lang) {
            "PHP" => ["7.1", "7.2", "7.3", "7.4", "8.0"],
            "JAVA" => ["1.6.0", "1.7.0", "1.8.0"],
            "PYTHON" => ["2.0", "2.8", "3.0", "3.7"],
            "NODEJS" => ["10.0.1", "10.19.0"],
            "CSHARP" => ["1", "2", "3"],
            default => [],
        };
    }

    /**
     * @description Get Servers
     * @return array
     */
    public static function getServers(): array
    {
        return [
            "NGINX", "APACHE", "TOMCAT", "TOMCAT ON JAVA/SPRING", "NODE", "WEB_CONFIG"
        ];
    }

    /**
     * @description Get Languages
     * @param string $server
     * @return array
     */
    public static function getServersVersion(string $server = ""): array
    {
        return match ($server) {
            "NGINX" => ["7.1", "7.2", "7.3", "7.4", "8.0"],
            "APACHE" => ["1.6.0", "1.7.0", "1.8.0"],
            "TOMCAT" => ["2.0", "2.8", "3.0", "3.7"],
            "NODE" => ["10.0.1", "10.19.0"],
            "WEB_CONFIG" => ["1", "2", "3"],
            default => [],
        };
    }
}
