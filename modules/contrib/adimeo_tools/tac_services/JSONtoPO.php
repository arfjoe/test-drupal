<?php

// Get an array of the translation files
$jsonDirectory = "./translations/json/";
$jsonFiles = scandir($jsonDirectory);

// Remove ./ & ../
unset($jsonFiles[0]);
unset($jsonFiles[1]);

// Buildings traduction files from template
foreach($jsonFiles as $jsonFile){
    $languagueString = str_replace('.json', '', $jsonFile);
    $fileContent = file_get_contents($jsonDirectory . $jsonFile);

    // Traduction array
    $array = json_decode($fileContent, true);

    // Open template
    $template = file_get_contents('./translations/tac_services.template.po');
    
    foreach($array as $key => $value){
        if(!is_array($value)){
            $toReplace = '{' . $key . '}';
            $replacement = $value;
            $template = str_replace($toReplace, $replacement, $template);
        }
        if(is_array($value)){
            foreach($value as $subKey => $subValue){
                $toReplace =  '{' . $key . '.' . $subKey . '}';
                $replacement = $subValue;
                $template = str_replace($toReplace, $replacement, $template);
            }
        }
    }

    // Write PO files with filled template
    file_put_contents('./translations/tac_services.' . $languagueString . '.po', $template);
}