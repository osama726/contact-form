<?php
/**
 * Load environment variables from .env file
 * 
 * - يحط القيم في getenv() عشان تستخدمها في الكود
 * - يتجاهل التعليقات والأسطر الفاضية
 */
function loadEnv($path) {
    if (!file_exists($path)) {
        die("⚠️ .env file not found at: $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        // تجاهل التعليقات
        if ($line === '' || $line[0] === '#') {
            continue;
        }

        // قسم المتغيرات name=value
        list($name, $value) = explode('=', $line, 2);

        $name = trim($name);
        $value = trim($value);

        // لو القيمة بين "" أو '' يشيلها
        $value = trim($value, "\"'");

        // حفظ المتغير في البيئة
        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

/**
 * Get env variable or fail
 * 
 * - يتأكد المتغير موجود
 * - لو ناقص يوقف الكود برسالة واضحة
 */
function env($key, $default = null) {
    $value = getenv($key);

    if ($value === false) {
        if ($default !== null) {
            return $default;
        }
        die("⚠️ Missing environment variable: $key");
    }

    return $value;
}

// --- تحميل ملف الـ .env (بره الhtdocs) ---
loadEnv(__DIR__ . '/.env');
