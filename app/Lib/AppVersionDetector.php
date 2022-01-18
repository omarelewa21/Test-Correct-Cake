<?php

abstract class AllowedAppType
{
    const OK = "OK";
    const NEEDSUPDATE = "NEEDSUPDATE";
    const NOTALLOWED = "NOTALLOWED";
}

class AppVersionDetector
{
    private static $osConversion = [
        "windows10" => "windows10OS",
        "windows" => "windowsOS",
        "macbook" => "macOS",
        "ipad" => "iOS",
        "iPhone" => "iOS",
        "chromebook" => "ChromeOS",
        "win32" => "windowsElectron",
        "darwin" => "macosElectron"
    ];

    /* example:
        "iOS" => [
            "ok" => ["2.2", "2.3", "2.4", "2.5", "2.6", "2.8", "2.9"],
            "needsUpdate" => ["2.0","2.1"],
            "needsUpdateDeadline" => ["2.1"=>"1 mei 2022"],
        ],
    */
    private static $allowedVersions = [
        "windows10OS" => [
            "ok" => [],
            "needsUpdate" => [],
        ],
        "windowsOS" => [
            "ok" => [],
            "needsUpdate" => [],
        ],
        "macOS" => [
            "ok" => [],
            "needsUpdate" => [],
        ],
        "iOS" => [
            "ok" => [
                "2.4.1",
                "2.4.2",
                "2.4.3",
                "2.4.4",
                "2.4.5",
                "2.5.0",
                "2.5.1",
                "2.5.2",
                "2.5.3",
                "2.5.4",
                "2.5.5",
                "2.6.0",
                "2.6.1",
                "2.6.2",
                "2.6.3",
                "2.6.4",
                "2.6.5",
                "2.7.0",
                "2.7.1",
                "2.7.2",
                "2.7.3",
                "2.7.4",
                "2.7.5",
                "2.8.0",
                "2.8.1",
                "2.8.2",
                "2.8.3",
                "2.8.4",
                "2.8.5",
                "2.9.0",
                "2.9.1",
                "2.9.2",
                "2.9.3",
                "2.9.4",
                "2.9.5",
                "3.0.0",
                "3.0.1",
                "3.0.2",
                "3.0.3",
                "3.0.4",
                "3.0.5",
                "3.1.0",
            ],
            "needsUpdate" => [
                "2.4.0",
            ],
            "needsUpdateDeadline" => [
                "2.4.0" => "10 februari 2022",
            ],
        ],
        "ChromeOS" => [
            "ok" =>
                [
                    "2.4.1",
                    "2.4.2",
                    "2.4.3",
                    "2.4.4",
                    "2.4.5",
                    "2.5.0",
                    "2.5.1",
                    "2.5.2",
                    "2.5.3",
                    "2.5.4",
                    "2.5.5",
                    "2.6.0",
                    "2.6.1",
                    "2.6.2",
                    "2.6.3",
                    "2.6.4",
                    "2.6.5",
                    "2.7.0",
                    "2.7.1",
                    "2.7.2",
                    "2.7.3",
                    "2.7.4",
                    "2.7.5",
                    "2.8.0",
                    "2.8.1",
                    "2.8.2",
                    "2.8.3",
                    "2.8.4",
                    "2.8.5",
                    "2.9.0",
                    "2.9.1",
                    "2.9.2",
                    "2.9.3",
                    "2.9.4",
                    "2.9.5",
                    "3.0.0",
                    "3.0.1",
                    "3.0.2",
                    "3.0.3",
                    "3.0.4",
                    "3.0.5",
                    "3.1.0",
                ],
            "needsUpdate" => [
            ],
            "needsUpdateDeadline" => [
            ],
        ],
        "windowsElectron" => [
            "ok" => [
                "3.2.0",
                "3.2.0-beta.1",
                "3.2.0-beta.2",
                "3.2.0-beta.3",
                "3.2.0-beta.4",
                "3.2.0-beta.5",
                "3.2.1",
                "3.2.1-beta.1",
                "3.2.1-beta.2",
                "3.2.1-beta.3",
                "3.2.1-beta.4",
                "3.2.1-beta.5",
                "3.2.2",
                "3.2.2-beta.1",
                "3.2.2-beta.2",
                "3.2.2-beta.3",
                "3.2.2-beta.4",
                "3.2.2-beta.5",
                "3.2.3",
                "3.2.3-beta.1",
                "3.2.3-beta.2",
                "3.2.3-beta.3",
                "3.2.3-beta.4",
                "3.2.3-beta.5",
                "3.2.4",
                "3.2.4-beta.1",
                "3.2.4-beta.2",
                "3.2.4-beta.3",
                "3.2.4-beta.4",
                "3.2.4-beta.5",
                "3.2.5",
                "3.2.5-beta.1",
                "3.2.5-beta.2",
                "3.2.5-beta.3",
                "3.2.5-beta.4",
                "3.2.5-beta.5",
                "3.3.0",
                "3.3.0-beta.1",
                "3.3.0-beta.2",
                "3.3.0-beta.3",
                "3.3.0-beta.4",
                "3.3.0-beta.5",
            ],
            "needsUpdate" => [
                "3.1.3",
                "3.1.3-beta.1",
                "3.1.3-beta.2",
                "3.1.3-beta.3",
                "3.1.3-beta.4",
                "3.1.3-beta.5",
            ],
            "needsUpdateDeadline" => [
                "3.1.3" => "6 maart 2022",
            ],
        ],
        "macosElectron" => [
            "ok" => [
                "3.2.0",
                "3.2.0-beta.1",
                "3.2.0-beta.2",
                "3.2.0-beta.3",
                "3.2.0-beta.4",
                "3.2.0-beta.5",
                "3.2.1",
                "3.2.1-beta.1",
                "3.2.1-beta.2",
                "3.2.1-beta.3",
                "3.2.1-beta.4",
                "3.2.1-beta.5",
                "3.2.2",
                "3.2.2-beta.1",
                "3.2.2-beta.2",
                "3.2.2-beta.3",
                "3.2.2-beta.4",
                "3.2.2-beta.5",
                "3.2.3",
                "3.2.3-beta.1",
                "3.2.3-beta.2",
                "3.2.3-beta.3",
                "3.2.3-beta.4",
                "3.2.3-beta.5",
                "3.2.4",
                "3.2.4-beta.1",
                "3.2.4-beta.2",
                "3.2.4-beta.3",
                "3.2.4-beta.4",
                "3.2.4-beta.5",
                "3.2.5",
                "3.2.5-beta.1",
                "3.2.5-beta.2",
                "3.2.5-beta.3",
                "3.2.5-beta.4",
                "3.2.5-beta.5",
                "3.3.0",
                "3.3.0-beta.1",
                "3.3.0-beta.2",
                "3.3.0-beta.3",
                "3.3.0-beta.4",
                "3.3.0-beta.5",
            ],
            "needsUpdate" => [
                "3.1.1",
                "3.1.1-beta.1",
                "3.1.1-beta.2",
                "3.1.1-beta.3",
                "3.1.1-beta.4",
                "3.1.1-beta.5",
            ],
            "needsUpdateDeadline" => [
                "3.1.1" => "6 maart 2022"
            ],
        ]
    ];

    public static function detect($headers = false)
    {
        if (!$headers) {
            $headers = self::getAllHeaders();
        }

        /**
         * Format of TLCTestCorrectVersion header:
         *
         * platform (= OS)|app-version|Test-Correct app|Architecture|OS release|Electron app type
         *
         */

        $appType = [
            "app_version" => "x",
            "os" => "unknown-", // REMARK:: also used in AnswersController::is_taking_inbrowser_test
            "arch" => "",
            "os_release" => "",
            "app_type" => "",
        ];

        if (isset($headers["tlctestcorrectversion"])) {
            $data = explode("|", strtolower($headers["tlctestcorrectversion"]));
            $appType["os"] = isset(self::$osConversion[$data[0]])
                ? self::$osConversion[$data[0]]
                : "unknown-" . $data[0];
            $appType["app_version"] = isset($data[1]) ? $data[1] : "x";

            if (isset($data[3])) {
                $appType["arch"] = $data[3];
            }

            if (isset($data[4])) {
                $appType["os_release"] = $data[4];
            }

            if (isset($data[5])) {
                $appType["app_type"] = $data[5];
            }
        } else {
            // only for windows 2.0 and 2.1
            if (array_key_exists("user-agent", $headers)) {
                $parts = explode("|", $headers["user-agent"]);
                $lowerPart0 = strtolower($parts[0]);
                if ($lowerPart0 == "windows" || $lowerPart0 == "chromebook") {
                    $appType["os"] = self::$osConversion[$lowerPart0];
                    $appType["app_version"] = $parts[1];
                }
            }
        }

        return $appType;
    }

    public static function isInBrowser($headers = false)
    {
        if (!$headers) {
            $headers = self::getAllHeaders();
        }
        if(!isset($headers["tlctestcorrectversion"])){
            return true;
        }
        $data = explode("|", strtolower($headers["tlctestcorrectversion"]));
        if(!isset(self::$osConversion[$data[0]])){
            return true;
        }
        return false;
    }

    public static function needsUpdateDeadline($headers = false)
    {
        if (!$headers) {
            $headers = self::getAllHeaders();
        }
        $version = self::detect($headers);
        if(!isset(self::$allowedVersions[$version["os"]])){
            return false;
        }
        if(!isset(self::$allowedVersions[$version["os"]]["needsUpdateDeadline"])){
            return false;
        }
        if(array_key_exists($version["app_version"],self::$allowedVersions[$version["os"]]["needsUpdateDeadline"])){
            return self::$allowedVersions[$version["os"]]["needsUpdateDeadline"][$version["app_version"]];
        }
        return false;
    }

    public static function isVersionAllowed($headers = false)
    {
        $version = self::detect($headers);

        if (
            isset(self::$allowedVersions[$version["os"]]["ok"]) &&
            in_array(
                $version["app_version"],
                self::$allowedVersions[$version["os"]]["ok"]
            )
        ) {
            return AllowedAppType::OK;
        } elseif (
            isset(self::$allowedVersions[$version["os"]]["needsUpdate"]) &&
            in_array(
                $version["app_version"],
                self::$allowedVersions[$version["os"]]["needsUpdate"]
            )
        ) {
            return AllowedAppType::NEEDSUPDATE;
        } else {
            return AllowedAppType::NOTALLOWED;
        }
    }

    public static function getAllHeaders()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == "HTTP_") {
                $headers[
                    strtolower(
                        str_replace(
                            " ",
                            "-",
                            str_replace("_", " ", substr($name, 5))
                        )
                    )
                ] = $value;
            }
        }

        return $headers;
    }
}
