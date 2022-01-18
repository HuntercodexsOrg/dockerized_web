<?php

namespace Dockerized;

class Generator
{
    const FILENAME_CONF = "configuration.conf";

    /**
     * @description Data Change Values
     * @param string $setup #Mandatory
     * @param string $template #Mandatory
     * @return string|bool
     */
    private static function checkFileExists(string $setup, string $template): string|bool {
        if (!file_exists($setup)) return "File not Found: " . $setup;
        if (!file_exists($template)) return "File not found: " . $template;
        return true;
    }

    /**
     * @description Data Change Values
     * @param string $setup_file #Mandatory
     * @param string $str #Mandatory
     * @param string $target #Mandatory
     * @return string|bool
     */
    private static function dataChange(string $setup_file, string $str, string $target): string|bool
    {
        $target = str_replace("{", "", $target);
        $target = str_replace("}", "", $target);
        return str_replace("{{{".$target."}}}", Reader::apiReaderSetup($target, $setup_file), $str);
    }

    /**
     * @description Data Change Values
     * @param string $setup_file #Mandatory
     * @return string|bool
     */
    private static function getGitProjects(string $setup_file): string|bool
    {
        $git_projects = "";
        $projects = Reader::apiReaderSetupAll('GIT_PROJECT', $setup_file);
        for ($i = 0; $i < count($projects); $i++) {
            $git_projects .= "USE_PROJECT = ".$projects[$i].PHP_EOL;
        }

        return $git_projects;
    }

    /**
     * @description Header Generator
     * @param string $setup_file #Mandatory
     * @param string $template #Mandatory
     * @return string|bool
     */
    public static function headerGenerator(string $setup_file, string $template): string|bool
    {
        $check = Generator::checkFileExists($setup_file, $template);
        if ($check != true) {
            return $check;
        }

        $location_header = dirname($setup_file);
        $filepath_header = $location_header."/".self::FILENAME_CONF;

        try {
            $fhr = fopen($template, "r");
            $fhw = fopen($filepath_header, "w+");

            while(!feof($fhr)) {
                $line = fgets($fhr, 4096);

                if (preg_match("/{{{GIT_PROJECT}}}/", $line, $m, PREG_OFFSET_CAPTURE)) {
                    $line = Generator::getGitProjects($setup_file);
                } else if (preg_match("/{{{([0-9A-Z_]+)}}}/", $line, $m, PREG_OFFSET_CAPTURE)) {
                    $line = Generator::dataChange($setup_file, $line, $m[0][0]);
                }

                try {
                    fwrite($fhw, $line);
                } catch (\Exception $ex) {
                    return "Exception on write file: " . $ex;
                }
            }

            fclose($fhw);
            fclose($fhr);
        } catch (\Exception $e) {
            return "Exception on create file: " . $e;
        }

        return true;
    }

    /**
     * @description Services Generator
     * @param string $setup_file #Mandatory
     * @param string $template #Mandatory
     * @return string|bool
     */
    public static function servicesGenerator(string $setup_file, string $template): string|bool
    {
        $check = Generator::checkFileExists($setup_file, $template);
        if ($check != true) {
            return $check;
        }

        $location_services = dirname($setup_file);
        $filepath_service = $location_services."/".self::FILENAME_CONF;
        $services_quantity = Reader::apiReaderSetup('SERVICES_QUANTITY', $setup_file);
        $resources_database = explode(",", Reader::apiReaderSetup('RESOURCES_DOCKERIZED', $setup_file));
        $projects_target = Reader::apiReaderSetupAll('GIT_PROJECT', $setup_file);

        for ($i = 0; $i < count($projects_target); $i++) {

            $project_name = "";
            if (strpos($projects_target[$i], "@")) {
                $project_name = explode("@", $projects_target[$i])[1];
            } else if (strpos($projects_target[$i], "/")) {
                $project_name = explode("/", $projects_target[$i])[2];
            }

            try {
                $fhr = fopen($template, "r");
                $fhw = fopen($filepath_service, "a+");

                while(!feof($fhr)) {
                    $line = fgets($fhr, 4096);

                    $line = str_replace("{{{MANDATORY: SERVICE_NUMBER}}}", $i, $line);
                    $line = str_replace("{{{MANDATORY: SERVICE_NAME}}}", $project_name, $line);
                    $line = str_replace("{{{MANDATORY: SERVICE_NAME}}}", $project_name, $line);

                    try {
                        fwrite($fhw, $line);
                    } catch (\Exception $ex) {
                        return "Exception on write file: " . $ex;
                    }
                }

                fclose($fhw);
                fclose($fhr);
            } catch (\Exception $e) {
                return "Exception on create file: " . $e;
            }
        }

        for ($i = 0; $i < count($resources_database); $i++) {

            try {
                $fhr = fopen($template, "r");
                $fhw = fopen($filepath_service, "a+");

                while(!feof($fhr)) {
                    $line = fgets($fhr, 4096);

                    $line = str_replace("{{{MANDATORY: SERVICE_NUMBER}}}", $i+count($projects_target), $line);
                    $line = str_replace("{{{MANDATORY: SERVICE_NAME}}}", $resources_database[$i], $line);
                    $line = str_replace("{{{MANDATORY: SERVICE_NAME}}}", $resources_database[$i], $line);

                    try {
                        fwrite($fhw, $line);
                    } catch (\Exception $ex) {
                        return "Exception on write file: " . $ex;
                    }
                }

                fclose($fhw);
                fclose($fhr);
            } catch (\Exception $e) {
                return "Exception on create file: " . $e;
            }
        }

        return true;
    }

    /**
     * @description Extras Services Generator
     * @param string $setup_file #Mandatory
     * @param string $template #Mandatory
     * @return string|bool
     */
    public static function extrasGenerator(string $setup_file, string $template): string|bool
    {
        return true;
    }

    /**
     * @description Footer Generator
     * @param string $setup_file #Mandatory
     * @param string $template #Mandatory
     * @return string|bool
     */
    public static function footerGenerator(string $setup_file, string $template): string|bool
    {
        $check = Generator::checkFileExists($setup_file, $template);
        if ($check != true) {
            return $check;
        }

        $location_footer = dirname($setup_file);
        $filepath_footer = $location_footer."/".self::FILENAME_CONF;

        try {
            $fhr = fopen($template, "r");
            $fhw = fopen($filepath_footer, "a+");

            while(!feof($fhr)) {
                $line = fgets($fhr, 4096);

                try {
                    fwrite($fhw, $line);
                } catch (\Exception $ex) {
                    return "Exception on write file: " . $ex;
                }
            }

            fclose($fhw);
            fclose($fhr);
        } catch (\Exception $e) {
            return "Exception on create file: " . $e;
        }

        return true;
    }

}
