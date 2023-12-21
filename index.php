<?php

include_once 'ExternalValidatorException.php';
include_once 'ExternalValidator.php';

$incomingData = json_decode(file_get_contents('php://input'), true);

if (!$incomingData) {
    echo json_encode(array());
} else {
    $validator = new ExternalValidator();
    $result = $validator->validate($incomingData);

    // Custom check for female gender and age less than 50
    $genderCheckValue = "";
    $ageCheckValue = "";
    
    foreach ($incomingData['additional_fields'] as $elem) {
        if ($elem["id"] == "f31854d786f955875951edc4bf281a49") { // Gender ID
            $genderCheckValue = $elem["value"];
        }
        
        if ($elem["id"] == "430244ad49aa54ea5ee1ec48225f82a6") { // Age ID
            $ageCheckValue = $elem["value"];
        }
    }

    // Check if the gender is female and the age is less than 50
    if (strtolower($genderCheckValue) == "女性" && intval($ageCheckValue) < 50) {
        // Add an error message to the result array
        $result['errors'][] = "系統維修中，如需退費請聯繫客服申請退費 lightningen@outlook.com"; // Error message in Chinese
    }

    echo json_encode($result);
}
