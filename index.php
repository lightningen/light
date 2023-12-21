<?php

include_once 'ExternalValidatorException.php';
include_once 'ExternalValidator.php';

$incomingData = json_decode(file_get_contents('php://input'), true);

/*
// Uncomment for local testing

$incomingData = json_decode('{
    "service_id":"9",
    "provider_id":"45",
    "client_id":"8123",
    "start_datetime":"2021-01-11 11:40:00",
    "end_datetime":"2021-01-11 11:45:00",
    "count":1,
    "additional_fields":[
        {
            "id":"ed8f5b7380f7111c592abf6f916fc2d0",
            "name":"Check number",
            "value":"112233445566"
        },
        {
            "id":"68700bfe1ba3d59441c9b14d4f94938b",
            "name":"Some string",
            "value":"simplybook"
        },
        {
            "id":"ac4c3775f20dcfdea531346ee5bc8ea4",
            "name":"Gender",
            "value":"女性"
        },
        {
            "id":"your_age_question_id",
            "name":"Age",
            "value":50
        }
    ]
}', true);
*/

if (!$incomingData) {
    echo json_encode(array());
} else {
    $validator = new ExternalValidator();
    $result = $validator->validate($incomingData);

    // Custom check for "女性" and age greater than or equal to 50
    $genderCheckValue = "";
    $ageCheckValue = "";
    
    foreach ($incomingData['additional_fields'] as $elem) {
        if ($elem["id"] == "f31854d786f955875951edc4bf281a49") { // Replace with your actual gender question ID
            $genderCheckValue = $elem["value"];
        }
        
        if ($elem["id"] == "430244ad49aa54ea5ee1ec48225f82a6") { // Replace with your actual age question ID
            $ageCheckValue = $elem["value"];
        }
    }

    if (mb_strtolower($genderCheckValue, 'UTF-8') == "女性" && $ageCheckValue < 50) {
        // Add an error message to the result array
        $result['errors'][] = "系統維修中，如需退費請聯繫客服申請退費 lightningen@outlook.com"; // Error message in Chinese
    }

    echo json_encode($result);
}

    /*
    foreach ($incomingData['additional_fields'] as $elem) {
        if ($elem["id"] == "f31854d786f955875951edc4bf281a49") { // Replace with your actual gender question ID
            $genderCheckValue = $elem["value"];
        }
        if ($elem["id"] == "430244ad49aa54ea5ee1ec48225f82a6") { // Replace with your actual age question ID
            $ageCheckValue = $elem["value"];
        }
    }

    if (mb_strtolower($genderCheckValue, 'UTF-8') == "女性" && $ageCheckValue < 50) {
        // Add an error message to the result array
        $result['errors'][] = "系統維修中，如需退費請聯繫客服申請退費 lightningen@outlook.com";
    }


    echo json_encode($result);
}
