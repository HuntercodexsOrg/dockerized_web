<?php

function _generateDatabaseJS($qty, $data_type) {

    $mixed = '
let total_items = 10000;

let _columns_ = [
    "Id",
    "Product Name",
    "Active",
    "Description",
    "Volumes",
    "Stock",
    "Warranty",
    "Number Ref",
    "Code Ref",
    "SAP Number",
    "Delivery",
    "Payment",
    "Status",
    "Order",
    "Customer",
    "Address",
    "Phone",
    "City",
    "Postal Code",
    "Extras",
    "Additional",
    "Restrict",
    "Others",
    "Send",
    "Anchor",
    "Created",
    "Updated",
    "Canceled"
];

let _range_ = [
    null,
    [ 1, 10 ],
    [ 11, 20 ],
    [ 21, 30 ],
    [ 31, 40 ],
    [ 41, 50 ],
    [ 51, 60 ],
    [ 61, 70 ],
    [ 71, 80 ],
    [ 81, 90 ],
    [ 91, 100 ],
    [ 101, 110 ],
    [ 111, 120 ],
    [ 121, 130 ],
    [ 131, 140 ],
    [ 141, 150 ],
    [ 151, 160 ],
    [ 161, 170 ],
    [ 171, 180 ],
    [ 181, 190 ],
    [ 191, 200 ],
    [ 201, 210 ],
    [ 211, 220 ],
    [ 221, 230 ],
    [ 231, 240 ],
    [ 241, 250 ],
    [ 251, 260 ],
    [ 261, 270 ],
    [ 271, 280 ],
    [ 281, 290 ],
    [ 291, 300 ],
    [ 301, 310 ],
    [ 311, 320 ],
    [ 321, 330 ],
    [ 331, 340 ],
    [ 341, 350 ],
    [ 351, 360 ],
    [ 361, 370 ],
    [ 371, 380 ],
    [ 381, 390 ],
    [ 391, 400 ],
    [ 401, 410 ],
    [ 411, 420 ],
    [ 421, 430 ],
    [ 431, 440 ],
    [ 441, 450 ],
    [ 451, 460 ],
    [ 461, 470 ],
    [ 471, 480 ],
    [ 481, 490 ],
    [ 491, 500 ]
];

let _range2_ = [
    [1,5],
    [6,10],
    [11,15],
    [15,20],
    [91,100],
    [101,110],
    [111,120],
    [121,130],
    [131,140],
    // [501,600],
    [991,1000],
    [1001,1010],
    [20001,20010],
    [100001,100010],
    [1000001, 1000010],
    [9000001, 9000010]
];' . PHP_EOL . PHP_EOL;

$data_object = '{
    id: {{{id}}},
    product_name: "Product {{{id}}}",
    active: "Active",
    description: "Description {{{id}}}",
    volumes: "Volumes",
    stock: "Stock",
    warranty: "Warranty",
    number_ref: "Number Ref",
    code_ref: "Code Ref",
    sap_number: "SAP Number",
    delivery: "Delivery",
    payment: "Payment",
    status: "Status",
    order: "Order",
    customer: "Customer",
    address: "Address",
    phone: "Phone",
    city: "City",
    postal_code: "Postal Code",
    extras: "Extras",
    additional: "Additional",
    restrict: "Restrict",
    others: "Others",
    send: "Send",
    anchor: "Anchor",
    created: "Created",
    updated: "Updated",
    canceled: "Canceled"
}';

$_object = '{{{id}}}: {
    id: {{{id}}},
    product_name: "Product {{{id}}}",
    active: "Active",
    description: "Description {{{id}}}",
    volumes: "Volumes",
    stock: "Stock",
    warranty: "Warranty",
    number_ref: "Number Ref",
    code_ref: "Code Ref",
    sap_number: "SAP Number",
    delivery: "Delivery",
    payment: "Payment",
    status: "Status",
    order: "Order",
    customer: "Customer",
    address: "Address",
    phone: "Phone",
    city: "City",
    postal_code: "Postal Code",
    extras: "Extras",
    additional: "Additional",
    restrict: "Restrict",
    others: "Others",
    send: "Send",
    anchor: "Anchor",
    created: "Created",
    updated: "Updated",
    canceled: "Canceled"
}';

$data_array = '{{{id}}}: [{{{id}}},"Product {{{id}}}","Active","Description {{{id}}}","Volumes","Stock","Warranty","Number Ref","Code Ref","SAP Number","Delivery","Payment","Status","Order","Customer","Address","Phone","City","Postal Code","Extras","Additional","Restrict","Others","Send","Anchor","Created","Updated","Canceled"]';

$_array = '[{{{id}}},"Product {{{id}}}","Active","Description {{{id}}}","Volumes","Stock","Warranty","Number Ref","Code Ref","SAP Number","Delivery","Payment","Status","Order","Customer","Address","Phone","City","Postal Code","Extras","Additional","Restrict","Others","Send","Anchor","Created","Updated","Canceled"]';

    if( $data_type == "var") {
        echo $mixed;
    }

    if( $data_type == "object") {
        echo "let _data_object_ = [" . PHP_EOL;
        for ($i = 1; $i <= $qty; $i++) {
            if ($i == $qty) {
                echo str_replace('{{{id}}}', $i, $data_object);
            } else {
                echo str_replace('{{{id}}}', $i, $data_object) . "," . PHP_EOL;
            }
        }
        echo "];" . PHP_EOL;
    }

    if( $data_type == "object2") {
        echo "let _data_object2_ = {" . PHP_EOL;
        for ($i = 1; $i <= $qty; $i++) {
            if ($i == $qty) {
                echo str_replace('{{{id}}}', $i, $_object);
            } else {
                echo str_replace('{{{id}}}', $i, $_object) . "," . PHP_EOL;
            }
        }
        echo "};" . PHP_EOL;
    }

    if( $data_type == "array") {
        echo "let _data_array_ = {" . PHP_EOL;
        for ($i = 1; $i <= $qty; $i++) {
            if ($i == $qty) {
                echo str_replace('{{{id}}}', $i, $data_array);
            } else {
                echo str_replace('{{{id}}}', $i, $data_array) . "," . PHP_EOL;
            }
        }
        echo "};" . PHP_EOL;
    }

    if( $data_type == "array2") {
        echo "let _data_array2_ = [" . PHP_EOL;
        for ($i = 1; $i <= $qty; $i++) {
            if ($i == $qty) {
                echo str_replace('{{{id}}}', $i, $_array);
            } else {
                echo str_replace('{{{id}}}', $i, $_array) . "," . PHP_EOL;
            }
        }
        echo "];" . PHP_EOL;
    }
}

function _generateDatabasePHP($qty) {

$mixed = '
$total_items = 10000;

$_columns_ = [
    "Id",
    "Product Name",
    "Active",
    "Description",
    "Volumes",
    "Stock",
    "Warranty",
    "Number Ref",
    "Code Ref",
    "SAP Number",
    "Delivery",
    "Payment",
    "Status",
    "Order",
    "Customer",
    "Address",
    "Phone",
    "City",
    "Postal Code",
    "Extras",
    "Additional",
    "Restrict",
    "Others",
    "Send",
    "Anchor",
    "Created",
    "Updated",
    "Canceled"
];

$_range_ = [
    null,
    [ 1, 10 ],
    [ 11, 20 ],
    [ 21, 30 ],
    [ 31, 40 ],
    [ 41, 50 ],
    [ 51, 60 ],
    [ 61, 70 ],
    [ 71, 80 ],
    [ 81, 90 ],
    [ 91, 100 ],
    [ 101, 110 ],
    [ 111, 120 ],
    [ 121, 130 ],
    [ 131, 140 ],
    [ 141, 150 ],
    [ 151, 160 ],
    [ 161, 170 ],
    [ 171, 180 ],
    [ 181, 190 ],
    [ 191, 200 ],
    [ 201, 210 ],
    [ 211, 220 ],
    [ 221, 230 ],
    [ 231, 240 ],
    [ 241, 250 ],
    [ 251, 260 ],
    [ 261, 270 ],
    [ 271, 280 ],
    [ 281, 290 ],
    [ 291, 300 ],
    [ 301, 310 ],
    [ 311, 320 ],
    [ 321, 330 ],
    [ 331, 340 ],
    [ 341, 350 ],
    [ 351, 360 ],
    [ 361, 370 ],
    [ 371, 380 ],
    [ 381, 390 ],
    [ 391, 400 ],
    [ 401, 410 ],
    [ 411, 420 ],
    [ 421, 430 ],
    [ 431, 440 ],
    [ 441, 450 ],
    [ 451, 460 ],
    [ 461, 470 ],
    [ 471, 480 ],
    [ 481, 490 ],
    [ 491, 500 ]
];

$_range2_ = [
    [1,10],
    [11,20],
    [91,100],
    [101,110],
    [111,120],
    [121,130],
    [131,140],
    [991,1000],
    [1001,1010],
    [20001,20010],
    [100001,100010],
    [1000001,1000010],
    [9000001,9000010]
];' . PHP_EOL . PHP_EOL;

$data = '[
    "id" => "{{{id}}}",
    "product_name" => "Product {{{id}}}",
    "active" => "Active",
    "description" => "Description {{{id}}}",
    "volumes" => "Volumes",
    "stock" => "Stock",
    "warranty" => "Warranty",
    "number_ref" => "Number Ref",
    "code_ref" => "Code Ref",
    "sap_number" => "SAP Number",
    "delivery" => "Delivery",
    "payment" => "Payment",
    "status" => "Status",
    "order" => "Order",
    "customer" => "Customer",
    "address" => "Address",
    "phone" => "Phone",
    "city" => "City",
    "postal_code" => "Postal Code",
    "extras" => "Extras",
    "additional" => "Additional",
    "restrict" => "Restrict",
    "others" => "Others",
    "send" => "Send",
    "anchor" => "Anchor",
    "created" => "Created",
    "updated" => "Updated",
    "canceled" => "Canceled"
]';

    echo "<?php".PHP_EOL;
    echo $mixed;

    echo '$_data_ = [' . PHP_EOL;
    for ($i = 1; $i <= $qty; $i++) {
        if ($i === $qty) {
            echo str_replace('{{{id}}}', $i, $data);
        } else {
            echo str_replace('{{{id}}}', $i, $data) . "," . PHP_EOL;
        }
    }
    echo "];" . PHP_EOL;
}

function _generateDatabaseCSV($qty) {

    $header = "id;product_name;country;description;price;ratings;warranty;number_ref;code_ref;sap_number;delivery;payment;status;order;customer;address;phone;city;postal_code;extras;additional;restrict;others;send;anchor;created;updated;canceled;";
    $data = "#{{{id}}};Product {{{id}}};icon;Description of the product number {{{id}}};1.000,00;ratings;Warranty;Number Ref;Code Ref;SAP Number;Delivery;Payment;Status;Order;Customer {{{id}}};Address;Phone;City;Postal Code;Extras;Additional;Restrict;Others;Send;Anchor;Created;Updated;Canceled;";

    echo $header.PHP_EOL;
    for ($i = 1; $i <= $qty; $i++) {
        echo str_replace('{{{id}}}', $i, $data) . PHP_EOL;
    }
}

$type = $argv[1];
$qty = $argv[2];
$data_type = $argv[3] ?? "";

if ($type == "js" && $qty && $data_type) {
    _generateDatabaseJS($qty, $data_type);
}

if ($type == "php" && $qty) {
    _generateDatabasePHP($qty);
}

if ($type == "csv" && $qty) {
    _generateDatabaseCSV($qty);
}

?>