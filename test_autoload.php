<?php

require __DIR__ . '/vendor/autoload.php'; // Đảm bảo đường dẫn đúng tới tệp autoload.php

use app\commands\SendEmailJob; // Sử dụng lớp bạn muốn kiểm tra

// Kiểm tra xem lớp có tồn tại không
if (class_exists(SendEmailJob::class)) {
    echo "Lớp SendEmailJob đã được autoload thành công!";
} else {
    echo "Lớp SendEmailJob không thể được autoload.";
}
