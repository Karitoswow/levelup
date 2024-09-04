<?php

$config['max_level'] = 80;

$config['title_items'] = "!A GM Level up to Item";
$config['body_items'] = "Hi Dear friend, Sent Items were sent to your online server is Decreased";

$config['title_gold'] = "!A GM Level up to ";
$config['body_gold'] = "Hi Dear friend, Sent Money";
$config['gold_count'] = 5000; // 1000 = 1K   0 = Disable no send gold  soap enbale for  money


$config['description'] = "With this option, you can bring your character to the last Level " . $config['max_level'];

$config['price_lvl'] = [
    60 => 5,
    70 => 8,
    80 => 10
];