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
        "chromebook" => "ChromeOS",
        "win32" => "windowsElectron",
    ];

    private static $allowedVersions = [
        "windows10OS" => [
            "ok" => [],
            "needsUpdate" => ["2.2", "2.3", "2.4", "2.5", "2.6", "2.8", "2.9"],
        ],
        "windowsOS" => [
            "ok" => [],
            "needsUpdate" => [
                "2.0",
                "2.1",
                "2.2",
                "2.3",
                "2.4",
                "2.5",
                "2.6",
                "2.8",
                "2.9",
            ],
        ],
        "macOS" => [
            "ok" => ["2.4", "2.5", "2.6", "2.8", "2.9"],
            "needsUpdate" => ["2.0", "2.1", "2.2", "2.3"],
        ],
        "iOS" => [
            "ok" => ["2.2", "2.3", "2.4", "2.5", "2.6", "2.8", "2.9"],
            "needsUpdate" => ["2.0", "2.1"],
        ],
        "ChromeOS" => [
            "ok" => ["2.3", "2.4", "2.5", "2.6", "2.8", "2.9"],
            "needsUpdate" => [],
        ],
        "windowsElectron" => [
            "ok" => [
                "3.0.2",
                "3.0.3",
                "3.1.0",
                "3.0.2-beta.1",
                "3.0.2-beta.2",
                "3.0.2-beta.3",
                "3.0.2-beta.4",
                "3.0.4",
                "3.0.5",
            ],
            "needsUpdate" => ["2.300.2-beta.2", "3.0.0-beta.5", "3.0.0", "3.0.1"],
        ],
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
