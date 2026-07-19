<?php
// Уақыт белдеуін Қазақстанға қою
date_default_timezone_set('Asia/Almaty');

// Мәлімет сақталатын файл
$file = 'messages.json';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Хат мәтінін алу
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    
    // Жаңа хаттың құрылымы
    $new_message = [
        'id' => time(), // Уақытқа негізделген уникалды ID
        'date' => date('d.m.Y H:i'),
        'message' => htmlspecialchars($message),
        'file' => 'Жоқ', // Әзірге файлсыз
        'status' => 'new' // Жаңа хат
    ];

    // Ескі хаттарды оқу
    $data = [];
    if (file_exists($file)) {
        $json_data = file_get_contents($file);
        $data = json_decode($json_data, true) ?: [];
    }

    // Жаңа хатты тізімнің ең басына қосу
    array_unshift($data, $new_message);

    // Файлға қайта сақтау
    file_put_contents($file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    // Сәтті сақталғаны туралы жауап беру
    echo json_encode(["status" => "success"]);
}
?>