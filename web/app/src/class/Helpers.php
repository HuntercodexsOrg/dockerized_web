<?php

namespace Dockerized;

class Helpers
{
    /**
     * @description Build Select HTMLElement
     * @param string $initial
     * @param array $data
     * @param array $opts
     * @return string
     */
    public static function buildSelectHtmlElement(string $initial, array $data = [], array $opts = []): string
    {
        $id = $opts['id'] ?? "";
        $counter = $opts['counter'] ?? "";

        $initial_value = preg_replace("/([Ss][Ee][Ll][Ee][Cc][Tt]) ?/", "", $initial);

        $select = "<select id='{$id}-{$counter}' data-select-{$id} data-content='{$counter}'>";
        $select .= "<option value=''>Select {$initial_value}</option>";
        for ($i = 0; $i < count($data); $i++) {
            $select .= "<option value='{$data[$i]}'>".$data[$i]."</option>";
        }
        $select .= "</select>";
        return $select;
    }

}
