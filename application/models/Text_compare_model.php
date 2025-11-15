<?php
class Text_compare_model extends CI_Model {

    public function compare_texts($text1, $text2) {
        $differences = [];

        // Compare character by character
        $length1 = strlen($text1);
        $length2 = strlen($text2);
        $maxLength = max($length1, $length2);

        for ($i = 0; $i < $maxLength; $i++) {
            $char1 = $i < $length1 ? $text1[$i] : '';
            $char2 = $i < $length2 ? $text2[$i] : '';

            if ($char1 !== $char2) {
                $differences[] = [
                    'char1' => $char1,
                    'char2' => $char2,
                    'index' => $i,
                ];
            }
        }

        return $differences;
    }
}
