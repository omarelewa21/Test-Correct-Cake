<?php

echo preg_replace_callback(
    '/\[([0-9]+)\]/i',
    function ($matches) use ($answer) {
        $tag_id = $matches[1]-1; // answers are 0 based
        if(isset($answer[$tag_id])){
            $html = '<span style="color:green;">' . $answer[$tag_id] . '</span>';
            $html .= '<span class="fa fa-question toggleOption" onclick="toggleOption(this);" style="color:orange; cursor: pointer;"></span>';
            return $html;
        } else {
            $html = '<span style="color:red;"> - </span>';
            $html .= '<span class="fa fa-question toggleOption fa-remove" style="color:red;"></span>';
            return $html;
        }
    },
    $question
);